<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ClientRegistrationType;
use App\Form\ServiceProviderRegistrationType;
use App\Form\VerificationCodeType;
use App\Form\PhoneVerificationType;
use App\Form\PasswordResetType;
use App\Repository\UserRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/auth')]
class AuthController extends AbstractController
{
    private EmailService $emailService;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EmailService $emailService, UserPasswordHasherInterface $passwordHasher)
    {
        $this->emailService = $emailService;
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/client/register', name: 'auth_client_register', methods: ['GET', 'POST'])]
    public function clientRegister(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(ClientRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set user role
            $user->setRole('ROLE_CLIENT');
            
            // Hash password
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getMotdepasse());
            $user->setMotdepasse($hashedPassword);
            
            // Set terms and privacy acceptance
            $user->setAcceptTerms(true);
            $user->setAcceptPrivacy(true);
            
            // Generate verification code
            $verificationCode = $user->generateVerificationCode();
            
            // Persist user
            $entityManager->persist($user);
            $entityManager->flush();
            
            // Send verification email
            $this->emailService->sendEmailVerification($user, $verificationCode);
            
            // Store user ID in session for verification process
            $request->getSession()->set('registration_user_id', $user->getId());
            
            $this->addFlash('success', 'Inscription réussie! Veuillez vérifier votre email.');
            return $this->redirectToRoute('auth_verify_email');
        }

        return $this->render('auth/client_register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/provider/register', name: 'auth_provider_register', methods: ['GET', 'POST'])]
    public function providerRegister(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(ServiceProviderRegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Set user role
            $user->setRole('ROLE_PROVIDER');
            
            // Hash password
            $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getMotdepasse());
            $user->setMotdepasse($hashedPassword);
            
            // Set terms and privacy acceptance
            $user->setAcceptTerms(true);
            $user->setAcceptPrivacy(true);
            
            // Generate verification code
            $verificationCode = $user->generateVerificationCode();
            
            // Persist user
            $entityManager->persist($user);
            $entityManager->flush();
            
            // Send verification email
            $this->emailService->sendEmailVerification($user, $verificationCode);
            
            // Store user ID in session for verification process
            $request->getSession()->set('registration_user_id', $user->getId());
            
            $this->addFlash('success', 'Inscription réussie! Veuillez vérifier votre email.');
            return $this->redirectToRoute('auth_verify_email');
        }

        return $this->render('auth/provider_register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify-email', name: 'auth_verify_email', methods: ['GET', 'POST'])]
    public function verifyEmail(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $userId = $request->getSession()->get('registration_user_id');
        if (!$userId) {
            return $this->redirectToRoute('auth_login');
        }

        $user = $userRepository->find($userId);
        if (!$user) {
            return $this->redirectToRoute('auth_login');
        }

        $form = $this->createForm(VerificationCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedCode = $form->get('code')->getData();
            
            if ($user->isVerificationCodeValid($submittedCode)) {
                $user->setEmailVerified(true);
                $user->clearVerificationCode();
                $entityManager->flush();
                
                $request->getSession()->remove('registration_user_id');
                $this->addFlash('success', 'Email vérifié avec succès! Vous pouvez maintenant vous connecter.');
                return $this->redirectToRoute('auth_login');
            } else {
                $this->addFlash('error', 'Code invalide ou expiré. Veuillez demander un nouveau code.');
            }
        }

        return $this->render('auth/verify_email.html.twig', [
            'form' => $form->createView(),
            'user_email' => $user->getEmail(),
        ]);
    }

    #[Route('/resend-verification', name: 'auth_resend_verification', methods: ['POST'])]
    public function resendVerification(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): JsonResponse
    {
        $userId = $request->getSession()->get('registration_user_id');
        if (!$userId) {
            return new JsonResponse(['success' => false, 'message' => 'Session expirée']);
        }

        $user = $userRepository->find($userId);
        if (!$user) {
            return new JsonResponse(['success' => false, 'message' => 'Utilisateur non trouvé']);
        }

        // Generate new verification code
        $verificationCode = $user->generateVerificationCode();
        $entityManager->flush();
        
        // Send verification email
        $this->emailService->sendEmailVerification($user, $verificationCode);

        return new JsonResponse(['success' => true, 'message' => 'Code de vérification renvoyé']);
    }

    #[Route('/login', name: 'auth_login', methods: ['GET', 'POST'])]
    public function login(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('identifiant', TextType::class, [
                'label' => 'Identifiant ou Email',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'identifiant est obligatoire'])
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le mot de passe est obligatoire'])
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $identifiant = $form->get('identifiant')->getData();
            $password = $form->get('password')->getData();

            // Find user by email or identifiant
            $user = $userRepository->findOneBy(['email' => $identifiant]) 
                    ?? $userRepository->findOneBy(['identifiant' => $identifiant]);

            if ($user && $this->passwordHasher->isPasswordValid($user, $password)) {
                if (!$user->isEmailVerified()) {
                    $this->addFlash('error', 'Veuillez d\'abord vérifier votre email.');
                    return $this->render('auth/login.html.twig', ['form' => $form->createView()]);
                }

                // Store user in session and redirect to phone verification
                $request->getSession()->set('login_user_id', $user->getId());
                return $this->redirectToRoute('auth_verify_phone');
            } else {
                $this->addFlash('error', 'Identifiants invalides.');
            }
        }

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/verify-phone', name: 'auth_verify_phone', methods: ['GET', 'POST'])]
    public function verifyPhone(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $userId = $request->getSession()->get('login_user_id');
        if (!$userId) {
            return $this->redirectToRoute('auth_login');
        }

        $user = $userRepository->find($userId);
        if (!$user) {
            return $this->redirectToRoute('auth_login');
        }

        // Check if phone verification step
        $step = $request->query->get('step', 'phone');

        if ($step === 'phone') {
            $form = $this->createForm(PhoneVerificationType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $telephone = $form->get('telephone')->getData();
                $user->setTelephone($telephone);
                
                // Generate phone verification code
                $verificationCode = $user->generatePhoneVerificationCode();
                $entityManager->flush();
                
                // Send phone verification code (via email for demo)
                $this->emailService->sendPhoneVerification($user, $verificationCode);
                
                return $this->redirectToRoute('auth_verify_phone', ['step' => 'code']);
            }

            return $this->render('auth/verify_phone.html.twig', [
                'form' => $form->createView(),
                'step' => 'phone'
            ]);
        } else {
            $form = $this->createForm(VerificationCodeType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $submittedCode = $form->get('code')->getData();
                
                if ($user->isPhoneVerificationCodeValid($submittedCode)) {
                    $user->setPhoneVerified(true);
                    $user->clearPhoneVerificationCode();
                    $user->setLastLoginAt(new \DateTime());
                    $entityManager->flush();
                    
                    $request->getSession()->remove('login_user_id');
                    
                    // Redirect based on user role
                    if (in_array('ROLE_ADMIN', $user->getRoles())) {
                        return $this->redirectToRoute('app_user_index');
                    } elseif (in_array('ROLE_PROVIDER', $user->getRoles())) {
                        return $this->redirectToRoute('provider_dashboard');
                    } else {
                        return $this->redirectToRoute('client_dashboard');
                    }
                } else {
                    $this->addFlash('error', 'Code invalide ou expiré.');
                }
            }

            return $this->render('auth/verify_phone.html.twig', [
                'form' => $form->createView(),
                'step' => 'code',
                'phone' => $user->getTelephone()
            ]);
        }
    }

    #[Route('/forgot-password', name: 'auth_forgot_password', methods: ['GET', 'POST'])]
    public function forgotPassword(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'email est obligatoire']),
                    new Assert\Email(['message' => 'Veuillez entrer un email valide'])
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $email]);

            if ($user) {
                $resetCode = $user->generatePasswordResetCode();
                $entityManager->flush();
                
                $this->emailService->sendPasswordReset($user, $resetCode);
                $request->getSession()->set('reset_user_id', $user->getId());
            }
            
            // Always show success message for security
            $this->addFlash('success', 'Si cet email existe, un code de réinitialisation a été envoyé.');
            return $this->redirectToRoute('auth_reset_password_verify');
        }

        return $this->render('auth/forgot_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset-password-verify', name: 'auth_reset_password_verify', methods: ['GET', 'POST'])]
    public function resetPasswordVerify(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $userId = $request->getSession()->get('reset_user_id');
        if (!$userId) {
            return $this->redirectToRoute('auth_forgot_password');
        }

        $user = $userRepository->find($userId);
        if (!$user) {
            return $this->redirectToRoute('auth_forgot_password');
        }

        $form = $this->createForm(VerificationCodeType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $submittedCode = $form->get('code')->getData();
            
            if ($user->isPasswordResetCodeValid($submittedCode)) {
                return $this->redirectToRoute('auth_reset_password_new');
            } else {
                $this->addFlash('error', 'Code invalide ou expiré. Veuillez demander un nouveau code.');
            }
        }

        return $this->render('auth/reset_password_verify.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset-password-new', name: 'auth_reset_password_new', methods: ['GET', 'POST'])]
    public function resetPasswordNew(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $userId = $request->getSession()->get('reset_user_id');
        if (!$userId) {
            return $this->redirectToRoute('auth_forgot_password');
        }

        $user = $userRepository->find($userId);
        if (!$user) {
            return $this->redirectToRoute('auth_forgot_password');
        }

        $form = $this->createForm(PasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('password')->getData();
            $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
            
            $user->setMotdepasse($hashedPassword);
            $user->clearPasswordResetCode();
            $entityManager->flush();
            
            $request->getSession()->remove('reset_user_id');
            
            $this->addFlash('success', 'Mot de passe réinitialisé avec succès! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('auth_login');
        }

        return $this->render('auth/reset_password_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/logout', name: 'auth_logout')]
    public function logout(): void
    {
        // This method can be blank - it will be intercepted by the logout key on your firewall
    }
}
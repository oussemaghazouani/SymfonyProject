<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\ReCaptchaService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


#[Route('/user')]
final class UserController extends AbstractController
{
    

    public function __construct(ReCaptchaService $recaptchaService, ParameterBagInterface $parameterBag)
{
    $this->recaptchaService = $recaptchaService;
    $this->googleRecaptchaSiteKey = "6LcSn5sqAAAAAAXOuiGVl_xR_nhfOR7c4bRnlBUi";  // Correct parameter name
}


    #[Route('/signup', name: 'app_signup', methods: ['GET', 'POST'])]
    public function signup(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $recaptchaResponse = $request->request->get('g-recaptcha-response');
    
            if (!$recaptchaResponse) {
                $this->addFlash('error', 'Please complete the reCAPTCHA.');
                return $this->render('user/signup.html.twig', [
                    'form' => $form->createView(),
                    'site_key' => $this->googleRecaptchaSiteKey,
                ]);
            }
    
            $isValid = $this->recaptchaService->verify($recaptchaResponse, $request->getClientIp());
    
            if ($isValid) {
                $this->addFlash('error', 'reCAPTCHA verification failed.');
                return $this->render('user/signup.html.twig', [
                    'form' => $form->createView(),
                    'site_key' => $this->googleRecaptchaSiteKey, 
                ]);
            }
            $user->setRole("ROLE_CLIENT");
            $entityManager->persist($user);
            $entityManager->flush();
            
            $this->addFlash('success', 'Your account has been created successfully!');
            return $this->redirectToRoute('auth_ysf');
        }
        
        return $this->render('user/signup.html.twig', [
            'form' => $form->createView(),
            'site_key' => $this->googleRecaptchaSiteKey, 
        ]);
    }
    

    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository,Request $request): Response
    {
        $role = $request->getSession()->get('role');
        if($role == "ROLE_ADMIN")
        {
        $username = $request->getSession()->get('username');

        return $this->render('user/index.html.twig', [
            "username" =>$username,
            'users' => $userRepository->findAll(),
        ]);
        }
        else         
        return $this->redirectToRoute('app_produit_front_index');
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $role = $request->getSession()->get('role');
        if($role == "ROLE_ADMIN")
        {
            $username = $request->getSession()->get('username');
            dump($username); // Displays the username
            dump($role);     // Displays the role
                $isAdmin = true;
                $user = new User();
        
                $form = $this->createForm(UserType::class, $user, [
                    'is_admin' => $isAdmin,
                ]);
                $form->handleRequest($request);
        
                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager->persist($user);
                    $entityManager->flush();
        
                    return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
                }
        
                return $this->render('user/new.html.twig', [
                    'user' => $user,
                    'form' => $form->createView(),
                ]);
        }
        else         
        return $this->redirectToRoute('app_produit_front_index');

       
    }

    #[Route('/{email}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user, [
            'is_admin' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
    
    #[Route('/auth', name: 'app_login')]
    public function login(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        return $this->render('user/login.html.twig');
    }


}

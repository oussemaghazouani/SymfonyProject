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
    $this->googleRecaptchaSiteKey = $parameterBag->get('google_recaptcha_site_key');  // Correct parameter name
}


    #[Route('/signup', name: 'app_signup', methods: ['GET', 'POST'])]
    public function signup(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the reCAPTCHA token from the form submission
            $recaptchaToken = $request->request->get('recaptcha_token');

            // Verify the reCAPTCHA token with Google's API
            $isValid = $this->recaptchaService->verify($recaptchaToken, $request->getClientIp());

            if (!$isValid) {
                $this->addFlash('error', 'reCAPTCHA verification failed.');
                return $this->render('user/signup.html.twig', [
                    'form' => $form->createView(),
                    'site_key' => $this->googleRecaptchaSiteKey, // Pass the site key
                ]);
            }

            // Continue with the user registration process
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Your account has been created successfully!');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/signup.html.twig', [
            'form' => $form->createView(),
            'site_key' => $this->googleRecaptchaSiteKey, // Pass the site key
        ]);
    }

    #[Route(name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
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

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
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
}

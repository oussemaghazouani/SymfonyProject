<?php

namespace App\Controller;

use App\Service\ReCaptchaService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

class Security1Controller extends AbstractController
{
    private ReCaptchaService $recaptchaService;

    // Inject services via the constructor
    public function __construct(ReCaptchaService $recaptchaService)
    {
        $this->recaptchaService = $recaptchaService;
    }

    /**
     * @Route("/signup", name="app_signup")
     */
    public function signup(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the reCAPTCHA response from the form submission
            $recaptchaResponse = $request->request->get('g-recaptcha-response');

            if (!$recaptchaResponse) {
                $this->addFlash('error', 'Please complete the reCAPTCHA.');
                return $this->render('user/signup.html.twig', [
                    'form' => $form->createView(),
                    'site_key' => $this->googleRecaptchaSiteKey,
                ]);
            }

            // Verify the reCAPTCHA response with Google's API
            $isValid = $this->recaptchaService->verify($recaptchaResponse, $request->getClientIp());

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
            'site_key' => $this->googleRecaptchaSiteKey,
        ]);
    }
}

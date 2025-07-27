<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // If user is already logged in, redirect to appropriate dashboard
        if ($this->getUser()) {
            $user = $this->getUser();
            if (in_array('ROLE_ADMIN', $user->getRoles())) {
                return $this->redirectToRoute('app_user_index');
            } elseif (in_array('ROLE_PROVIDER', $user->getRoles())) {
                return $this->redirectToRoute('provider_dashboard');
            } else {
                return $this->redirectToRoute('client_dashboard');
            }
        }

        return $this->render('home/index.html.twig');
    }
}
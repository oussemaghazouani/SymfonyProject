<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/client')]
#[IsGranted('ROLE_CLIENT')]
class ClientDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'client_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('client/dashboard.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
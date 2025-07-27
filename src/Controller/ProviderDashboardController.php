<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/provider')]
#[IsGranted('ROLE_PROVIDER')]
class ProviderDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'provider_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('provider/dashboard.html.twig', [
            'user' => $this->getUser(),
            'message' => 'Pendant la période d\'essai gratuit, certaines fonctionnalités seront limitées. Pour y accéder, vous devrez souscrire à un pack payant comme la gestion d\'équipe.'
        ]);
    }
}
<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ButtonsController extends AbstractController
{
    #[Route('/buttons', name: 'app_buttons')]
    public function index(): Response
    {
        return $this->render('buttons/index.html.twig', [
            'controller_name' => 'ButtonsController',
        ]);
    }
}

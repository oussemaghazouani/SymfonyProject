<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TableController extends AbstractController
{
    #[Route('/table', name: 'app_tables')]
    public function index(): Response
    {
        return $this->render('table.html.twig', [
            'controller_name' => 'TableController',
        ]);
    }
}

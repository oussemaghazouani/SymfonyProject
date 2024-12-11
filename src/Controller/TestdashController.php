<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TestdashController extends AbstractController
{
    #[Route('/testdash', name: 'app_testdash')]
    public function index(): Response
    {
        return $this->render('testdash/index.html.twig', [
            'controller_name' => 'TestdashController',
        ]);
    }
}

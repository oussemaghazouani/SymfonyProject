<?php

namespace App\Controller;

use App\Entity\TypeCompetitions;
use App\Form\TypeCompetitionsType;
use App\Repository\TypeCompetitionsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type/competitions')]
final class TypeCompetitionsController extends AbstractController
{
    #[Route(name: 'app_type_competitions_index')]
    public function index(TypeCompetitionsRepository $typeCompetitionsRepository,Request $request): Response
    {
        $role = $request->getSession()->get('role');
        if($role == "ROLE_ADMIN")
        {
        return $this->render('type_competitions/index.html.twig', [
            'type_competitions' => $typeCompetitionsRepository->findAll(),
        ]);
    }

    return $this->redirectToRoute('app_produit_front_index');
    }

    #[Route('/new', name: 'app_type_competitions_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $role = $request->getSession()->get('role');
        if($role == "ROLE_ADMIN")
        {
        $typeCompetition = new TypeCompetitions();
        $form = $this->createForm(TypeCompetitionsType::class, $typeCompetition);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $entityManager->persist($typeCompetition);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_competitions_index');
        }

        return $this->render('type_competitions/new.html.twig', [
            'type_competition' => $typeCompetition,
            'form' => $form,
        ]);
    
    
    }
    return $this->redirectToRoute('app_produit_front_index');
}

    #[Route('/{id}', name: 'app_type_competitions_show')]
    public function show(TypeCompetitions $typeCompetition): Response
    {
        return $this->render('type_competitions/show.html.twig', [
            'type_competition' => $typeCompetition,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_competitions_edit', )]
    public function edit(Request $request, TypeCompetitions $typeCompetition, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeCompetitionsType::class, $typeCompetition);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_competitions_index');
        }

        return $this->render('type_competitions/edit.html.twig', [
            'type_competition' => $typeCompetition,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_type_competitions_delete')]
    public function delete($id, TypeCompetitionsRepository $rep, EntityManagerInterface $entityManager): Response
    {
        $typeCompetition=$rep->find($id);
    
            $entityManager->remove($typeCompetition);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_type_competitions_index');
    }
}

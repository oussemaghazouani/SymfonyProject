<?php

namespace App\Controller;

use App\Entity\TypeMateriels;
use App\Form\TypeMaterielsType;
use App\Repository\TypeMaterielsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type/materiels')]
final class TypeMaterielsController extends AbstractController
{
    #[Route(name: 'app_type_materiels_index', methods: ['GET'])]
    public function index(TypeMaterielsRepository $typeMaterielsRepository): Response
    {
        return $this->render('type_materiels/index.html.twig', [
            'type_materiels' => $typeMaterielsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_materiels_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeMateriel = new TypeMateriels();
        $form = $this->createForm(TypeMaterielsType::class, $typeMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeMateriel);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_materiels_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_materiels/new.html.twig', [
            'type_materiel' => $typeMateriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_materiels_show', methods: ['GET'])]
    public function show(TypeMateriels $typeMateriel): Response
    {
        return $this->render('type_materiels/show.html.twig', [
            'type_materiel' => $typeMateriel,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_materiels_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeMateriels $typeMateriel, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeMaterielsType::class, $typeMateriel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_materiels_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_materiels/edit.html.twig', [
            'type_materiel' => $typeMateriel,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_materiels_delete', methods: ['POST'])]
    public function delete(Request $request, TypeMateriels $typeMateriel, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeMateriel->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeMateriel);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_materiels_index', [], Response::HTTP_SEE_OTHER);
    }
}

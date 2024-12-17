<?php

namespace App\Controller;

use App\Entity\TypeProduit;
use App\Form\TypeProduit1Type;
use App\Repository\TypeProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/type/produitt')]
final class TypeProduittController extends AbstractController
{
    #[Route(name: 'app_type_produitt_index', methods: ['GET'])]
    public function index(TypeProduitRepository $typeProduitRepository): Response
    {
        return $this->render('type_produitt/index.html.twig', [
            'type_produits' => $typeProduitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_produitt_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeProduit = new TypeProduit();
        $form = $this->createForm(TypeProduit1Type::class, $typeProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeProduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_produitt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_produitt/new.html.twig', [
            'type_produit' => $typeProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_produitt_show', methods: ['GET'])]
    public function show(TypeProduit $typeProduit): Response
    {
        return $this->render('type_produitt/show.html.twig', [
            'type_produit' => $typeProduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_produitt_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeProduit $typeProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeProduit1Type::class, $typeProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_produitt_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('type_produitt/edit.html.twig', [
            'type_produit' => $typeProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_produitt_delete', methods: ['POST'])]
    public function delete(Request $request, TypeProduit $typeProduit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeProduit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($typeProduit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_produitt_index', [], Response::HTTP_SEE_OTHER);
    }
}

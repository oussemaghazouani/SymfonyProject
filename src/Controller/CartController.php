<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;


class CartController extends AbstractController
{
    private ProduitRepository $produitRepository;

    public function __construct(ProduitRepository $produitRepository)
    {
        $this->produitRepository = $produitRepository;
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function addToCart(int $id, Request $request): Response
    {
        $session = $request->getSession();
        
        $cart = $session->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_produit_front_index');
    }

    #[Route('/cart', name: 'cart_view')]
    public function viewCart(Request $request): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        $products = [];

        foreach ($cart as $productId => $quantity) {
            $product = $this->produitRepository->find($productId);
            if ($product) {
                $products[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }

        return $this->render('cart/view.html.twig', [
            'products' => $products,
        ]);
    }


#[Route('/cart/remove/{id}', name: 'cart_remove')]
public function removeFromCart(int $id, Request $request): RedirectResponse
{
    $session = $request->getSession();
    $cart = $session->get('cart', []);
    if (isset($cart[$id])) {
        unset($cart[$id]);
    }

    $session->set('cart', $cart);

    return new RedirectResponse($this->generateUrl('cart_view')); 
}

}

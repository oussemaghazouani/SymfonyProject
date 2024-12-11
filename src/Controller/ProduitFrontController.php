<?php
namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\ProduitRepository;
use App\Repository\CommentRepository;
use App\Repository\TypeProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;



#[Route('/produit_front')]
class ProduitFrontController extends AbstractController

{
    private $httpClient;
    private ProduitRepository $produitRepository;

    private TypeProduitRepository $typeProduitRepository;
    private EntityManagerInterface $entityManager;

    // Injecting the repositories and entity manager via the constructor
    public function __construct(

        ProduitRepository $produitRepository,

        TypeProduitRepository $typeProduitRepository,
        EntityManagerInterface $entityManager
    ) {
   
        $this->produitRepository = $produitRepository;

        $this->typeProduitRepository = $typeProduitRepository;
        $this->entityManager = $entityManager;
    }

    #[Route(name: 'app_produit_front_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        
        $typeProduitId = $request->query->get('typeProduit');
        $minPrice = $request->query->get('minPrice');
        $maxPrice = $request->query->get('maxPrice');

        $queryBuilder = $this->produitRepository->createQueryBuilder('p');

        if ($typeProduitId) {
            $queryBuilder->andWhere('p.TypeP = :typeProduit')
                         ->setParameter('typeProduit', $typeProduitId);
        }

        if ($minPrice) {
            $queryBuilder->andWhere('p.prix >= :minPrice')
                         ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice) {
            $queryBuilder->andWhere('p.prix <= :maxPrice')
                         ->setParameter('maxPrice', $maxPrice);
        }

        $produits = $queryBuilder->getQuery()->getResult();

        return $this->render('produit_front/index.html.twig', [
            'produits' => $produits,
            'typeProduits' => $this->typeProduitRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_produit_front_show', methods: ['GET','POST'])]
    public function show(Produit $produit, Request $request, EntityManagerInterface $entityManager, CommentRepository $rep): Response
    {
        // Create a new comment object
        $comment = new Comment();
        $comment->setProduit($produit);  // Associate the comment with the current product

        // Create the form for adding a comment
        $form = $this->createForm(CommentType::class, $comment);

        // Handle the form submission
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $description = $comment->getContent();
            $filteredDescription = $rep->filterBadWords($description);
                $comment->setContent($filteredDescription);
            // Persist the comment to the database
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirect to the same page to show the new comment
            return $this->redirectToRoute('app_produit_front_show', ['id' => $produit->getId()]);
        }
        // Delete comment handling
        if ($request->isMethod('POST') && $request->get('delete_comment_id')) {
            $commentId = $request->get('delete_comment_id');
            $comment = $this->entityManager->getRepository(Comment::class)->find($commentId);

            if ($comment && $comment->getProduit() === $produit) {
                $this->entityManager->remove($comment);
                $this->entityManager->flush();

                // Redirect to the same page to update the comments list
                return $this->redirectToRoute('app_produit_front_show', ['id' => $produit->getId()]);
            }
        }

        // Fetch all comments for the current product
        $comments = $produit->getComments();

        // Render the page with the product, comments, and form
        return $this->render('produit_front/show.html.twig', [
            'produit' => $produit,
            'comments' => $comments,
            'form' => $form->createView(),
        ]);
    }


    
    
}

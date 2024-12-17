<?php
namespace App\Controller;

use App\Entity\User;
use Firebase\JWT\JWT; // Ensure you import JWT library
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class AuthYsfController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/auth_ysf', name: 'auth_ysf')]
    public function index(): Response
    {
        return $this->render('auth_ysf/index.html.twig');
    }

    #[Route('/authenticate', name: 'authenticate', methods: ['POST'])]
    public function authenticate(Request $request): Response
    {
        $email = $request->request->get('email');
        $password = $request->request->get('motdepasse');

        // Validate credentials logic
        if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => $email, 'motdepasse' => $password])) {
            
            $userFound = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email, 'motdepasse' => $password]);

            if ($userFound) {
                // Simulate the token creation and storage
                $payload = [
                    'id' => $userFound->getId(),
                    'exp' => time() + (5 * 24 * 60 * 60), // 5 days expiration
                ];
                $token = JWT::encode($payload, 'YSF', 'HS256');
                $this->storeTokenInSession($request, $token);
                $role = $userFound->getRole();

                echo $role;
                if($role == "ROLE_ADMIN")
                {
                    return $this->redirectToRoute('app_signup');

                }
                else
                {
                    return $this->redirectToRoute('app_user_new');

                }
            } else {
                return $this->redirectToRoute('authenticate', ['error' => 'User not found!']);
            }
        } else {
            return $this->redirectToRoute('authenticate', ['error' => 'Password or Email is incorrect!']);
        }
    }

    // You may need a method to verify the credentials (example)
 
    private function storeTokenInSession(Request $request, string $token)
    {
        $request->getSession()->set('token', $token);
    }
}

<?php
namespace App\ArgumentResolver;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Validator\Constraints\Email;

class UserByEmailValueResolver implements ArgumentValueResolverInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === User::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        // Get the 'email' parameter from the request (you can adjust this as necessary)
        $email = $request->attributes->get('email');
        
        // Make sure it's a valid email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthenticationException('Invalid email format');
        }

        // Find the user by email
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['EMAIL' => $email]);

        if (!$user) {
            throw new AuthenticationException('User not found');
        }

        yield $user;
    }
}

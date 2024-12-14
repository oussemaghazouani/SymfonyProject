<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string')]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    private ?string $EMAIL = null;

    #[ORM\Column(type: 'string')]
    private ?string $motdepasse = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $role = null;

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getEMAIL(): ?string
    {
        return $this->EMAIL;
    }

    public function setEMAIL(string $EMAIL): static
    {
        $this->EMAIL = $EMAIL;
        return $this;
    }

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse;
    }

    public function setMotdepasse(string $motdepasse): static
    {
        $this->motdepasse = $motdepasse;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;
        return $this;
    }

    // Implement UserInterface methods:

    /**
     * @return string[] The roles granted to the user.
     */
    public function getRoles(): array
    {
        // You can return more roles, depending on your application logic.
        return array($this->role);
    }

    /**
     * Returns the username used to authenticate the user.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->EMAIL;  // or any other unique identifier (e.g., $this->nom)
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials(): void
    {
        // In most cases, there is no sensitive data to clear
        // but you can add your own logic if needed
    }
    public function __construct()
    {
       
    }
}

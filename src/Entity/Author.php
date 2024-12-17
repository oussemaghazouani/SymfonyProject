<?php

namespace App\Entity;

use App\Repository\AuthorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuthorRepository::class)]
class Author
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

<<<<<<< HEAD
=======
    #[ORM\Column(length: 50)]
    private ?string $username = null;

>>>>>>> competition
    public function getId(): ?int
    {
        return $this->id;
    }
<<<<<<< HEAD
=======

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
>>>>>>> competition
}

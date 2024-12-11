<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "Le nom ne peut pas être vide.")]
    #[Assert\Type(type: 'string', message: "Le nom doit être une chaîne de caractères.")]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z\s]*$/',
        message: "Le nom doit commencer par une majuscule et contenir uniquement des lettres."
    )]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le prix ne peut pas être vide.")]
    #[Assert\Type(type: 'integer', message: "Le prix doit être un nombre entier.")]
    private ?int $prix = null;

    #[ORM\Column]
    private ?bool $EstDisponible = null;

    // For the uploaded file during form handling
    private ?File $imageFile = null;

    // For the stored filename in the database
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "La description ne peut pas être vide.")]
    private ?string $Description = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeProduit $TypeP = null;

    #[ORM\OneToMany(mappedBy: 'produit', targetEntity: Comment::class)]
    private iterable $comments;


    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }
    
    public function getComments(): iterable
    {
        return $this->comments;
    }
    


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

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;
        return $this;
    }

    public function isEstDisponible(): ?bool
    {
        return $this->EstDisponible;
    }

    public function setEstDisponible(bool $EstDisponible): static
    {
        $this->EstDisponible = $EstDisponible;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): static
    {
        $this->Description = $Description;
        return $this;
    }

    public function getTypeP(): ?TypeProduit
    {
        return $this->TypeP;
    }

    public function setTypeP(?TypeProduit $TypeP): static
    {
        $this->TypeP = $TypeP;
        return $this;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile): static
    {
        $this->imageFile = $imageFile;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

        return $this;
    }
}

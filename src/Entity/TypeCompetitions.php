<?php

namespace App\Entity;

use App\Repository\TypeCompetitionsRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: TypeCompetitionsRepository::class)]
class TypeCompetitions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'typeCompetition', targetEntity: Competitions::class, cascade: ['persist', 'remove'])]
    private Collection $competitions;

    public function __construct()
    {
        $this->competitions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getCompetitions(): Collection
    {
        return $this->competitions;
    }

    // Optional: Add methods to manage the competitions collection
    public function addCompetition(Competitions $competition): static
    {
        if (!$this->competitions->contains($competition)) {
            $this->competitions->add($competition);
            $competition->setTypeCompetition($this);
        }

        return $this;
    }

    public function removeCompetition(Competitions $competition): static
    {
        if ($this->competitions->removeElement($competition)) {
            if ($competition->getTypeCompetition() === $this) {
                $competition->setTypeCompetition(null);
            }
        }

        return $this;
    }
}

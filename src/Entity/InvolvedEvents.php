<?php

namespace App\Entity;

use App\Repository\InvolvedEventsRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Competitions; 

#[ORM\Entity(repositoryClass: InvolvedEventsRepository::class)]
class InvolvedEvents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Competitions::class, inversedBy: 'involvedEvents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Competitions $competition = null;

    #[ORM\Column(type: "boolean")]
    private bool $isParticipating = false; 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompetition(): ?Competitions
    {
        return $this->competition;
    }

    public function setCompetition(?Competitions $competition): static
    {
        $this->competition = $competition;
        return $this;
    }

    public function isParticipating(): bool
    {
        return $this->isParticipating;
    }

    public function setIsParticipating(bool $isParticipating): static
    {
        $this->isParticipating = $isParticipating;
        return $this;
    }
}

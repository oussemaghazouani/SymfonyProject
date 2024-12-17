<?php

namespace App\Entity;

use App\Repository\CompetitionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\TypeCompetitions; 
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity(repositoryClass: CompetitionsRepository::class)]
class Competitions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]  
    #[Assert\NotBlank(message: "Le nom de la compétition est obligatoire.")]
    #[Assert\Length(
    min: 3,
    max: 12,
    minMessage: "Le nom doit contenir au moins {{ limit }} caractères.",
    maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères."
)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateC = null;
   
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: TypeCompetitions::class)]
    #[ORM\JoinColumn(name: "type_competition_id", referencedColumnName: "id")]
    private ?TypeCompetitions $typeCompetition = null;

    #[ORM\OneToMany(mappedBy: 'competition', targetEntity: InvolvedEvents::class, cascade: ['remove'])]
    private Collection $involvedEvents;

    public function __construct()
    {
        $this->involvedEvents = new ArrayCollection();
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

    public function getDateC(): ?\DateTimeInterface
    {
        return $this->dateC;
    }

    public function setDateC(\DateTimeInterface $dateC): static
    {
        $this->dateC = $dateC;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getTypeCompetition(): ?TypeCompetitions
    {
        return $this->typeCompetition;
    }

    public function setTypeCompetition(?TypeCompetitions $typeCompetition): static
    {
        $this->typeCompetition = $typeCompetition;
        return $this;
    }

    public function getInvolvedEvents(): Collection
    {
        return $this->involvedEvents;
    }
    #[Assert\Callback]
    public function validateTypeAndCompetition(ExecutionContextInterface $context): void
    {
        if ($this->type === 'bodybuilding' && $this->typeCompetition->getName() !== 'non martial art') {
            $context->buildViolation('Bodybuilding requires "nonmartial art" as type competition.')
                ->atPath('typeCompetition')
                ->addViolation();
        }

        if (in_array($this->type, ['kickboxing', 'boxing']) && $this->typeCompetition->getName() !== 'martial art') {
            $context->buildViolation('Boxing or Kickboxing requires "martial" as type competition.')
                ->atPath('typeCompetition')
                ->addViolation();
        }
    }
}



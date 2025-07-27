<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $identifiant = null;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 6)]
    private ?string $motdepasse = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $userType = null; // 'personne_physique' or 'entreprise'

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $nomEntreprise = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $domaineActivite = null; // For service providers only

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $role = null;

    #[ORM\Column(type: 'boolean')]
    private bool $acceptTerms = false;

    #[ORM\Column(type: 'boolean')]
    private bool $acceptPrivacy = false;

    #[ORM\Column(type: 'boolean')]
    private bool $emailVerified = false;

    #[ORM\Column(type: 'string', length: 6, nullable: true)]
    private ?string $verificationCode = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $verificationCodeExpiry = null;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(type: 'boolean')]
    private bool $phoneVerified = false;

    #[ORM\Column(type: 'string', length: 6, nullable: true)]
    private ?string $phoneVerificationCode = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $phoneVerificationCodeExpiry = null;

    #[ORM\Column(type: 'string', length: 6, nullable: true)]
    private ?string $passwordResetCode = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $passwordResetCodeExpiry = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $lastLoginAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    // Getters and Setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifiant(): ?string
    {
        return $this->identifiant;
    }

    public function setIdentifiant(?string $identifiant): static
    {
        $this->identifiant = $identifiant;
        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
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

    public function getPassword(): string
    {
        return $this->motdepasse;
    }

    public function setPassword(string $password): static
    {
        $this->motdepasse = $password;
        return $this;
    }

    public function getUserType(): ?string
    {
        return $this->userType;
    }

    public function setUserType(string $userType): static
    {
        $this->userType = $userType;
        return $this;
    }

    public function getNomEntreprise(): ?string
    {
        return $this->nomEntreprise;
    }

    public function setNomEntreprise(?string $nomEntreprise): static
    {
        $this->nomEntreprise = $nomEntreprise;
        return $this;
    }

    public function getDomaineActivite(): ?string
    {
        return $this->domaineActivite;
    }

    public function setDomaineActivite(?string $domaineActivite): static
    {
        $this->domaineActivite = $domaineActivite;
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

    public function isAcceptTerms(): bool
    {
        return $this->acceptTerms;
    }

    public function setAcceptTerms(bool $acceptTerms): static
    {
        $this->acceptTerms = $acceptTerms;
        return $this;
    }

    public function isAcceptPrivacy(): bool
    {
        return $this->acceptPrivacy;
    }

    public function setAcceptPrivacy(bool $acceptPrivacy): static
    {
        $this->acceptPrivacy = $acceptPrivacy;
        return $this;
    }

    public function isEmailVerified(): bool
    {
        return $this->emailVerified;
    }

    public function setEmailVerified(bool $emailVerified): static
    {
        $this->emailVerified = $emailVerified;
        return $this;
    }

    public function getVerificationCode(): ?string
    {
        return $this->verificationCode;
    }

    public function setVerificationCode(?string $verificationCode): static
    {
        $this->verificationCode = $verificationCode;
        return $this;
    }

    public function getVerificationCodeExpiry(): ?\DateTime
    {
        return $this->verificationCodeExpiry;
    }

    public function setVerificationCodeExpiry(?\DateTime $verificationCodeExpiry): static
    {
        $this->verificationCodeExpiry = $verificationCodeExpiry;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function isPhoneVerified(): bool
    {
        return $this->phoneVerified;
    }

    public function setPhoneVerified(bool $phoneVerified): static
    {
        $this->phoneVerified = $phoneVerified;
        return $this;
    }

    public function getPhoneVerificationCode(): ?string
    {
        return $this->phoneVerificationCode;
    }

    public function setPhoneVerificationCode(?string $phoneVerificationCode): static
    {
        $this->phoneVerificationCode = $phoneVerificationCode;
        return $this;
    }

    public function getPhoneVerificationCodeExpiry(): ?\DateTime
    {
        return $this->phoneVerificationCodeExpiry;
    }

    public function setPhoneVerificationCodeExpiry(?\DateTime $phoneVerificationCodeExpiry): static
    {
        $this->phoneVerificationCodeExpiry = $phoneVerificationCodeExpiry;
        return $this;
    }

    public function getPasswordResetCode(): ?string
    {
        return $this->passwordResetCode;
    }

    public function setPasswordResetCode(?string $passwordResetCode): static
    {
        $this->passwordResetCode = $passwordResetCode;
        return $this;
    }

    public function getPasswordResetCodeExpiry(): ?\DateTime
    {
        return $this->passwordResetCodeExpiry;
    }

    public function setPasswordResetCodeExpiry(?\DateTime $passwordResetCodeExpiry): static
    {
        $this->passwordResetCodeExpiry = $passwordResetCodeExpiry;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getLastLoginAt(): ?\DateTime
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTime $lastLoginAt): static
    {
        $this->lastLoginAt = $lastLoginAt;
        return $this;
    }

    // Verification code methods
    public function generateVerificationCode(): string
    {
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->setVerificationCode($code);
        $this->setVerificationCodeExpiry(new \DateTime('+15 minutes'));
        return $code;
    }

    public function generatePhoneVerificationCode(): string
    {
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->setPhoneVerificationCode($code);
        $this->setPhoneVerificationCodeExpiry(new \DateTime('+15 minutes'));
        return $code;
    }

    public function generatePasswordResetCode(): string
    {
        $code = str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->setPasswordResetCode($code);
        $this->setPasswordResetCodeExpiry(new \DateTime('+15 minutes'));
        return $code;
    }

    public function isVerificationCodeValid(string $code): bool
    {
        return $this->verificationCode === $code && 
               $this->verificationCodeExpiry !== null && 
               $this->verificationCodeExpiry > new \DateTime();
    }

    public function isPhoneVerificationCodeValid(string $code): bool
    {
        return $this->phoneVerificationCode === $code && 
               $this->phoneVerificationCodeExpiry !== null && 
               $this->phoneVerificationCodeExpiry > new \DateTime();
    }

    public function isPasswordResetCodeValid(string $code): bool
    {
        return $this->passwordResetCode === $code && 
               $this->passwordResetCodeExpiry !== null && 
               $this->passwordResetCodeExpiry > new \DateTime();
    }

    public function clearVerificationCode(): void
    {
        $this->verificationCode = null;
        $this->verificationCodeExpiry = null;
    }

    public function clearPhoneVerificationCode(): void
    {
        $this->phoneVerificationCode = null;
        $this->phoneVerificationCodeExpiry = null;
    }

    public function clearPasswordResetCode(): void
    {
        $this->passwordResetCode = null;
        $this->passwordResetCodeExpiry = null;
    }

    // Implement UserInterface methods:

    /**
     * @return string[] The roles granted to the user.
     */
    public function getRoles(): array
    {
        return array($this->role);
    }

    /**
     * Returns the username used to authenticate the user.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials(): void
    {
        // In most cases, there is no sensitive data to clear
        // but you can add your own logic if needed
    }
}

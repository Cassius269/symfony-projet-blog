<?php

namespace App\Entity;

use App\Repository\DemandRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DemandRepository::class)]
#[Vich\Uploadable]
class Demand
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le prénom doit être renseigné')]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de famille doit être renseigné')]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le mail doit être renseigné')]
    #[Assert\Email(
        message: 'Le mail {{ value }} n\'est pas valid'
    )]
    private ?string $email = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Parlez-nous de vous')]
    #[Assert\Length(
        min: 100,
        minMessage: 'Veuillez developper votre présentation et votre demande'
    )]
    private ?string $message = null;

    #[ORM\Column(length: 255)]
    private ?string $cv = null; // propriété qui stocke le nom et l'adresse du fichier uplaodé


    #[Vich\UploadableField(mapping: 'cv', fileNameProperty: 'cv')]
    #[Assert\NotNull(
        message: 'Le CV est obligatoire'
    )]
    #[Assert\File(
        mimeTypes: [
            'application/pdf'
        ],
        mimeTypesMessage: 'Votre fichier n\'est pas au bon format'
    )]
    private ?File $cvFile = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Choice([true, false, null])] // Valeurs attendues
    private ?bool $decision = null;

    #[ORM\ManyToOne(inversedBy: 'demands')]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'demands')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Status $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function isDecision(): ?bool
    {
        return $this->decision;
    }

    public function setDecision(bool $decision): static
    {
        $this->decision = $decision;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of cvFile
     */
    public function getCvFile()
    {
        return $this->cvFile;
    }

    /**
     * Set the value of cvFile
     *
     * @return  self
     */
    public function setCvFile($cvFile)
    {
        $this->cvFile = $cvFile;
        if (null !== $cvFile) { // Pour éviter des erreurs, le bundle Vich Uploader recommande de mettre une propriété en même temps que la mutation d'une valeur de CV à une demande
            $this->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * Get the value of cv
     */
    public function getCv()
    {
        return $this->cv;
    }

    /**
     * Set the value of cv
     *
     * @return  self
     */
    public function setCv($cv)
    {
        $this->cv = $cv;

        return $this;
    }
}

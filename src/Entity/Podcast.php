<?php

namespace App\Entity;

use App\Repository\PodcastRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PodcastRepository::class)]
class Podcast
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom de l\'émission doit être renseignée')]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'podcasts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $producer = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Une image d\'illustration du podcast doit être renseignée')]
    private ?string $imageIllustration = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La date de création doit être renseignée')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

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

    public function getProducer(): ?User
    {
        return $this->producer;
    }

    public function setProducer(?User $producer): static
    {
        $this->producer = $producer;

        return $this;
    }

    public function getImageIllustration(): ?string
    {
        return $this->imageIllustration;
    }

    public function setImageIllustration(string $imageIllustration): static
    {
        $this->imageIllustration = $imageIllustration;

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
}

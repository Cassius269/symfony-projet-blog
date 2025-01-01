<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EpisodeRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: EpisodeRepository::class)]
class Episode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le titre de l\'épisode')]
    #[Assert\Length(
        min: 10,
        minMessage: 'Votre titre est trop court'
    )]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Podcastor $podcaster = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Veuillez charger une image d\'illustration de \'épisode')]
    private ?string $imageIllustration = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Veuillez renseigner un gros resumé de l\'émission')]
    #[Assert\Length(
        min: 200,
        minMessage: 'Le résumé est trop court'
    )]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'episodes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Podcast $podcast = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Guest>
     */
    #[ORM\ManyToMany(targetEntity: Guest::class, inversedBy: 'episodes')]
    private Collection $guest;

    #[ORM\Column(length: 255)]
    private ?string $audio = null;

    public function __construct()
    {
        $this->guest = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPodcaster(): ?Podcastor
    {
        return $this->podcaster;
    }

    public function setPodcaster(?Podcastor $podcaster): static
    {
        $this->podcaster = $podcaster;

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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getPodcast(): ?Podcast
    {
        return $this->podcast;
    }

    public function setPodcast(?Podcast $podcast): static
    {
        $this->podcast = $podcast;

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

    /**
     * @return Collection<int, Guest>
     */
    public function getGuest(): Collection
    {
        return $this->guest;
    }

    public function addGuest(Guest $guest): static
    {
        if (!$this->guest->contains($guest)) {
            $this->guest->add($guest);
        }

        return $this;
    }

    public function removeGuest(Guest $guest): static
    {
        $this->guest->removeElement($guest);

        return $this;
    }

    public function getAudio(): ?string
    {
        return $this->audio;
    }

    public function setAudio(string $audio): static
    {
        $this->audio = $audio;

        return $this;
    }
}

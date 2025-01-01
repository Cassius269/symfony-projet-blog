<?php

namespace App\Entity;

use App\Repository\PodcastRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?Podcastor $podcaster = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: 'Une image d\'illustration du podcast doit être renseignée')]
    private ?string $imageIllustration = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'La date de création doit être renseignée')]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    /**
     * @var Collection<int, Episode>
     */
    #[ORM\OneToMany(targetEntity: Episode::class, mappedBy: 'podcast', orphanRemoval: true)]
    private Collection $episodes;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'podcast')]
    private Collection $notifications;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $slogan = null;

    public function __construct()
    {
        $this->episodes = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    public function getPodcaster(): ?Podcastor
    {
        return $this->podcaster;
    }

    public function setPodcaster(?Podcastor $podcaster): static
    {
        $this->podcaster = $podcaster;

        return $this;
    }

    /**
     * @return Collection<int, Episode>
     */
    public function getEpisodes(): Collection
    {
        return $this->episodes;
    }

    public function addEpisode(Episode $episode): static
    {
        if (!$this->episodes->contains($episode)) {
            $this->episodes->add($episode);
            $episode->setPodcast($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getPodcast() === $this) {
                $episode->setPodcast(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setPodcast($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getPodcast() === $this) {
                $notification->setPodcast(null);
            }
        }

        return $this;
    }

    public function getSlogan(): ?string
    {
        return $this->slogan;
    }

    public function setSlogan(string $slogan): static
    {
        $this->slogan = $slogan;

        return $this;
    }
}

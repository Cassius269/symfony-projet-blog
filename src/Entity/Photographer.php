<?php

namespace App\Entity;

use App\Repository\PhotographerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotographerRepository::class)]
class Photographer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudonyme = null;

    /**
     * @var Collection<int, MainImageIllustration>
     */
    #[ORM\OneToMany(targetEntity: MainImageIllustration::class, mappedBy: 'photographer')]
    private Collection $mainImageIllustrations;

    public function __construct()
    {
        $this->mainImageIllustrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPseudonyme(): ?string
    {
        return $this->pseudonyme;
    }

    public function setPseudonyme(?string $pseudonyme): static
    {
        $this->pseudonyme = $pseudonyme;

        return $this;
    }

    /**
     * @return Collection<int, MainImageIllustration>
     */
    public function getMainImageIllustrations(): Collection
    {
        return $this->mainImageIllustrations;
    }

    public function addMainImageIllustration(MainImageIllustration $mainImageIllustration): static
    {
        if (!$this->mainImageIllustrations->contains($mainImageIllustration)) {
            $this->mainImageIllustrations->add($mainImageIllustration);
            $mainImageIllustration->setPhotographer($this);
        }

        return $this;
    }

    public function removeMainImageIllustration(MainImageIllustration $mainImageIllustration): static
    {
        if ($this->mainImageIllustrations->removeElement($mainImageIllustration)) {
            // set the owning side to null (unless already changed)
            if ($mainImageIllustration->getPhotographer() === $this) {
                $mainImageIllustration->setPhotographer(null);
            }
        }

        return $this;
    }
}

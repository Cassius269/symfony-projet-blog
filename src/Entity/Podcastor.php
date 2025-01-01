<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\PodcastorRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PodcastorRepository::class)]
class Podcastor extends User
{
    public function __construct()
    {
        $this->podcasts = new ArrayCollection();
        $this->episodes = new ArrayCollection();
    }

    /**
     * @var Collection<int, Podcast>
     */
    #[ORM\OneToMany(targetEntity: Podcast::class, mappedBy: 'podcaster')]
    private Collection $podcasts;

    /**
     * @var Collection<int, Episode>
     */
    #[ORM\OneToMany(targetEntity: Episode::class, mappedBy: 'podcaster', orphanRemoval: true)]
    private Collection $episodes;

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
            $episode->setPodcaster($this);
        }

        return $this;
    }

    public function removeEpisode(Episode $episode): static
    {
        if ($this->episodes->removeElement($episode)) {
            // set the owning side to null (unless already changed)
            if ($episode->getPodcaster() === $this) {
                $episode->setPodcaster(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Podcast>
     */
    public function getPodcasts(): Collection
    {
        return $this->podcasts;
    }

    public function addPodcast(Podcast $podcast): static
    {
        if (!$this->podcasts->contains($podcast)) {
            $this->podcasts->add($podcast);
            $podcast->setPodcaster($this);
        }

        return $this;
    }

    public function removePodcast(Podcast $podcast): static
    {
        if ($this->podcasts->removeElement($podcast)) {
            // set the owning side to null (unless already changed)
            if ($podcast->getPodcaster() === $this) {
                $podcast->setPodcaster(null);
            }
        }

        return $this;
    }
}

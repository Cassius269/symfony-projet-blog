<?php
// Ce trait permet de réutiliser la propriété slug et ses méthodes dans des entités comme Article
namespace App\Entity\Trait; // Déclaration de l'espace de nom des traits des entités

use Doctrine\ORM\Mapping as ORM;

// Déclaration du trait SlugTrait pour slugifier le titre
trait SlugTrait
{

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}

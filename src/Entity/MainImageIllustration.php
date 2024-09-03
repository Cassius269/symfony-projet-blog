<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use App\Repository\MainImageIllustrationRepository;
use Symfony\Component\Serializer\Annotation\Ignore;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: MainImageIllustrationRepository::class)]
#[Vich\Uploadable]
class MainImageIllustration
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'articleIllustration', fileNameProperty: 'imageName')]
    #[Ignore] // Ignorer la propriété $imageFile lors de la sérialisation car gérée par AWS S3 et Vich Uploader
    private ?File $imageFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $source = null;

    #[ORM\ManyToOne(inversedBy: 'mainImageIllustrations', cascade: ['persist'])]
    private ?Photographer $photographer = null;

    /**
     * @var Collection<int, Article>
     */
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'mainImageIllustration')]
    private Collection $articles;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    // ... other methods ...

    public function __serialize(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'imageName' => $this->imageName,
            'source' => $this->source,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            // $this->imageFile n'est pas serialisée avec l'attribut ignore
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        $this->imageName = $data['imageName'];
        $this->source = $data['source'];
        $this->createdAt = $data['createdAt'];
        $this->updatedAt = $data['updatedAt'];
        // $this->imageFile reste null après la déserialization
    }
}

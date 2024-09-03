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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;

        return $this;
    }

    public function getPhotographer(): ?Photographer
    {
        return $this->photographer;
    }

    public function setPhotographer(?Photographer $photographer): static
    {
        $this->photographer = $photographer;

        return $this;
    }

    /**
     * @return Collection<int, Article>
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->articles->contains($article)) {
            $this->articles->add($article);
            $article->setMainImageIllustration($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->articles->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getMainImageIllustration() === $this) {
                $article->setMainImageIllustration(null);
            }
        }

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
     * Get the value of imageFile
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * Set the value of imageFile
     *
     * @return  self
     */
    public function setImageFile($imageFile)
    {
        $this->imageFile = $imageFile;
        if (null !== $imageFile) {

            // It is required that at least one field changes if you are using doctrine 

            // otherwise the event listeners won't be called and the file is lost 

            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;

        return $this;
    }

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

<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le titre de l\'article')]
    #[Assert\Length(
        min: 12,
        minMessage: 'Le titre est trop court'
    )]
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\Column(length: 255)]
    private ?string $imageIllustration = null;

    #[Vich\UploadableField(mapping: 'articleiIlustration', fileNameProperty: 'imageIllustration')]
    private ?File $imageIllustrationFile = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contenu d\'un article ne peut pas être vide')]
    #[Assert\Length(
        min: 400,
        minMessage: 'L\'article est trop court'
    )]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbreOfViews = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $abstract = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getImageIllustration(): ?string
    {
        return $this->imageIllustration;
    }

    public function setImageIllustration(?string $imageIllustration): static
    {
        $this->imageIllustration = $imageIllustration;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

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

    public function getNbreOfViews(): ?int
    {
        return $this->nbreOfViews;
    }

    public function setNbreOfViews(?int $nbreOfViews): static
    {
        $this->nbreOfViews = $nbreOfViews;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getAbstract(): ?string
    {
        return $this->abstract;
    }

    public function setAbstract(string $abstract): static
    {
        $this->abstract = $abstract;

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
     * Get the value of imageIllustrationFile
     */
    public function getImageIllustrationFile()
    {
        return $this->imageIllustrationFile;
    }

    /**
     * Set the value of imageIllustrationFile
     *
     * @return  self
     */
    public function setImageIllustrationFile($imageIllustrationFile)
    {
        $this->imageIllustrationFile = $imageIllustrationFile;
        if (null !== $imageIllustrationFile) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
}

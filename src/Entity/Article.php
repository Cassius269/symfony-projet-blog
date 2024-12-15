<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Entity\Trait\SlugTrait;
use Doctrine\ORM\Mapping as ORM;
// use Symfony\Component\HttpFoundation\File\File;
// use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
// Vérifier séparemment si la donnée titre d'un article est unique dans la base de données
#[UniqueEntity(
    fields: ['title'],
    message: 'Un titre similaire existe déjà'
)]
// Vérifier séparemment si la donnée contenu d'un article est unique dans la base de données
#[UniqueEntity(
    fields: ['content'],
    message: 'Un contenu similaire existe déjà'
)]
class Article
{
    use SlugTrait; // Utilisation du trait pour ajouter une propriété Slug et ses getteur et setteur

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['articles.index', 'articles.show', 'articles.create'])] // Groupe de sérialization pour l'API
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Veuillez renseigner le titre de l\'article')]
    #[Assert\Length(
        min: 12,
        minMessage: 'Le titre est trop court'
    )]
    #[Groups(['articles.index', 'articles.show', 'article.create'])] // Groupe de sérialization pour l'API
    private ?string $title = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['articles.index', 'articles.show'])] // Groupe de sérialization pour l'API
    private ?User $author = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contenu d\'un article ne peut pas être vide')]
    #[Assert\Length(
        min: 400,
        minMessage: 'L\'article est trop court'
    )]
    #[Groups(['articles.show', 'article.create'])] // Groupe de sérialization pour l'API
    private ?string $content = null;

    #[ORM\Column]
    #[Groups(['articles.index', 'articles.show', 'article.create'])] // Groupe de sérialization pour l'API
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['articles.index', 'articles.show'])] // Groupe de sérialization pour l'API
    private ?int $nbreOfViews = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['articles.index', 'articles.show', 'article.create'])] // Groupe de sérialization pour l'API
    private ?Category $category = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['articles.show', 'article.create'])] // Groupe de sérialization pour l'API
    private ?string $abstract = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'articles', cascade: ['persist', 'remove'], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['articles.index', 'articles.show', 'article.create'])] // Groupe de sérialization pour l'API
    private ?MainImageIllustration $mainImageIllustration = null;

    private ?string $image = null;

    /**
     * @var Collection<int, Notification>
     */
    #[ORM\OneToMany(targetEntity: Notification::class, cascade: ["remove"], mappedBy: 'article')]
    private Collection $notifications;


    public function __construct()
    {
        $this->notifications = new ArrayCollection();
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

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

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

    public function getMainImageIllustration(): ?MainImageIllustration
    {
        return $this->mainImageIllustration;
    }

    public function setMainImageIllustration(?MainImageIllustration $mainImageIllustration): static
    {
        $this->mainImageIllustration = $mainImageIllustration;

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
            $notification->setArticle($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getArticle() === $this) {
                $notification->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * Get the value of image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @return  self
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }
}

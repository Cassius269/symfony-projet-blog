<?php

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Article::class)]
class ArticleListener
{

    // Injection des dépendances 
    public function __construct(private SluggerInterface $slugger) {}

    public function prePersist(Article $article, PrePersistEventArgs $event)
    {
        $title = $article->getTitle();
        $slugTitle = $this->slugger->slug($title)->lower(); // Slugfication du titre de l'article avant persistance en base de données

        $article->setCreatedAt(new \DateTimeImmutable()) // Insérer la date du jour comme date de création de l'article au moment de l'envoi de la donnée en BDD
            ->setSlug($slugTitle);

        $entityManager = $event->getObjectManager();
        $entityManager->flush();
    }
}

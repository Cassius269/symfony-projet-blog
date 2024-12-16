<?php

namespace App\EventListener;

use App\Entity\Article;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Article::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Article::class)]
class ArticleListener
{

    // Injection des dépendances 
    public function __construct(private SluggerInterface $slugger) {}

    public function prePersist(Article $article, PrePersistEventArgs $event)
    {
        //Sluggifier le titre de l'article
        $title = $article->getTitle();
        $slugTitle = $this->slugger->slug($title)->lower(); // Slugfication du titre de l'article avant persistance en base de données

        // Insérer la date du jour comme date de création de l'article au moment de l'envoi de la donnée en BDD
        $article->setCreatedAt(new \DateTimeImmutable())
            ->setSlug($slugTitle);
    }

    public function preUpdate(Article $article, PreUpdateEventArgs $event)
    {
        //Sluggifier le titre de l'article
        $title = $article->getTitle();
        $slugTitle = $this->slugger->slug($title)->lower(); // Slugfication du titre de l'article avant persistance en base de données


        // Insérer la date du jour comme date de création de l'article au moment de l'envoi de la donnée en BDD
        $article->setSlug($slugTitle)
            ->setUpdatedAt(new \DateTime());
    }
}

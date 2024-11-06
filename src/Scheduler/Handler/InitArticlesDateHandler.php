<?php

namespace App\Scheduler\Handler;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Scheduler\Message\InitArticlesDate;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class InitArticlesDateHandler
{
    public function __construct(private EntityManagerInterface $entityManager, private ArticleRepository $articleRepository) {}

    public function __invoke(InitArticlesDate $InitArticlesDate)
    {
        dump('hello');
        $articles = $this->articleRepository->findAll(); // Rechercher les articles disponibles

        // Si il y a au moins un article, le réinitialiser à la date du jour
        if ($articles) {
            foreach ($articles as $article) {
                $article->setCreatedAt(new \DateTimeImmutable())
                    ->setUpdatedAt(new \DateTime());

                // Persister chaque article trouvé mise à jour
                $this->entityManager->persist($article);
            }
            // Envoi de tous les articles trouvés et mis à jour vers la base de données
            $this->entityManager->flush();
        }
    }
}

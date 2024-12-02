<?php

namespace App\Scheduler\Handler;

use IntlDateFormatter;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Scheduler\Message\InitArticlesDate;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler()]
class InitArticlesDateHandler
{
    public function __construct(private EntityManagerInterface $entityManager, private ArticleRepository $articleRepository, private LoggerInterface $logger) {}

    public function __invoke(InitArticlesDate $InitArticlesDate) // on invoque le message dans cette méthode en faisant une ingection de dépendance dans la méthode
    {
        // dump('hello');
        // Etape d'écriture de la date du jour dans les logs personnalisés de Scheduler
        $time = time(); // le timestamp de la date du jour

        $formateur = new IntlDateFormatter('fr_fr', IntlDateFormatter::FULL, IntlDateFormatter::FULL);
        $formateur->setTimeZone('Europe/Paris');
        $formatedDate =  $formateur->format($time); // formattage de la date à partir du timestamp

        // Journanlisation de la date d'initilisatiation des articles via le plannificateur de tâches
        $this->logger->info('Initialisation des dates des articles :' . $formatedDate);

        $schedularlogFile = dirname(__DIR__, 3) . '/var/log/logScheduler.log'; // Sélection du chemin absolu du fichier "logScheduler.log"
        $currentContent = file_get_contents($schedularlogFile); // Récupération du contenu du fichier de log de Scheduler
        $currentContent .= $formatedDate . ": réinitilisation des dates de créations et de mise à jour des articles lancée \n"; // Concaténation du contenu présent avec le contenu actuel du rapport de réinitilisation des dates de création et de mise à jour des articles

        file_put_contents($schedularlogFile, $currentContent); // écriture des logs de Scheduler 

        //Etape de réinitilisation des articles 
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

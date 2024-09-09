<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Services\AwsManager;
use App\Services\Notificator; // Service personnalisé de création de notifiications instantannées grâce à Mercure
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'home', methods: 'GET')]
    public function index(ArticleRepository $articleRepository, AwsManager $awsManager): Response
    {
        // Récuperer l'article top 1, le plus lu par les utilisateurs
        $topArticle = $articleRepository->findTopArticle();
        // dd($topArticle);

        if (!$topArticle) { // Si il n'y pas d'article top 1, envoyer un message d'erreur
            throw  $this->createNotFoundException("Aucun article datant de moins de 7 jours disponible");
        }

        // Récuperer le fichier image de l'article principal
        $file = $awsManager->readFile($topArticle);
        $topArticle->setImage($file);

        // Récupérer le top 3 des Artciles dans l'ordre décroissant (du plus grand nombre de vues au plus petit nombre de vues)
        $topArticles = $articleRepository->getTopArticles(3);
        // dd($topArticles);
        // Mettre à jour à la vue la source du fichier image de chaque article, sans mise à jour de la base de données
        foreach ($topArticles as $article) {
            // Obtenir le fichier image d'illustration de l'article
            $file = $awsManager->readFile($article);
            $article->setImage($file);
        }
        // dd($topArticles);

        return $this->render('home/index.html.twig', [
            'topArticles' => $topArticles,
            'topArticle' => $topArticle,
        ]);
    }

    // Test du service personnalisé d'envoi de notifications instantannnées à l'aide de mercure
    #[Route('/publish', name: 'publish')]
    #[IsGranted("ROLE_ADMIN")] // Protégér la route de test contre toute tentative d'autres utilisateurs
    public function publish(Notificator $notif): Response
    {
        $notif->send('Une demande a été envoyée', 'demand', 20, 10);

        return new Response('Notification envoyée!');
    }
}

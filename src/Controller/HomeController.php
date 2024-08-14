<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Services\AwsManager;
use App\Services\Notificator; // Service personnalisé de création de notifiications instantannées grâce à Mercure
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ArticleRepository $articleRepository, AwsManager $awsManager): Response
    {
        // Récuperer l'article top 1, le plus lu par les utilisateurs
        $topArticle = $articleRepository->findOneBy(
            [],
            ['nbreOfViews' => 'DESC']
        );

        $file = $awsManager->readFile($topArticle);
        $topArticle->setImage($file);

        // dd($topArticle);
        // Récupérer le top 3 des Artciles dans l'ordre décroissant (du plus grand nombre de vues au plus petit nombre de vues)
        $topArticles = $articleRepository->getTopArticles(3);

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
        $notif->send('Un mail a été envoyé', 'demand', 20);

        return new Response('Notification envoyée!');
    }
}

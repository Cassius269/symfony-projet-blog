<?php

namespace App\Controller\API;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(name: 'api_')]
class ArticleController extends AbstractController
{

    #[Route(
        path: '/api/articles',
        name: 'get_all_articles',
        methods: ['GET'] //Ce point de terminaison est accessible en méthode GET
    )]
    public function index(ArticleRepository $articleRepository): Response
    {
        // Récupération de tous les articles
        $data = $articleRepository->findAll();

        // Retourner la réponse JSON contenant toutes les ressources articles
        return $this->json($data, 200, [], [
            'groups' => ['articles.index']
        ]);
    }

    #[Route(
        path: '/api/articles/{id}',
        name: 'get_article_by_id',
        methods: ['GET']
    )]
    public function show(#[MapEntity(id: 'id')] ?Article $article): Response
    {
        if (!$article) {
            throw $this->createNotFoundException('Ressource non trouvable');
        }

        return $this->json($article, 200, [], [
            'groups' => ['articles.show']
        ]);
    }
}

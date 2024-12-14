<?php

namespace App\Controller\API;

use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(name: 'articles_')]
class ArticleController extends AbstractController
{

    #[Route(
        path: '/api/articles',
        name: 'getArticles',
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
}

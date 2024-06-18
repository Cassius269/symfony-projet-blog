<?php

namespace App\Controller;

use App\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'articles')]
    public function index(): Response
    {
        return $this->render('articles/allArticles.html.twig', []);
    }

    #[Route(
        path: '/articles/{id}',
        name: 'article_detail'
    )]
    public function showDetailledArticle(?Article $article): Response
    {

        return $this->render('articles/articleDetail.html.twig', [
            'article' => $article,
        ]);
    }
}

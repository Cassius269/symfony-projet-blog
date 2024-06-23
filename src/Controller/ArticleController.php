<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/articles', name: 'articles_')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function index(): Response
    {
        return $this->render('articles/allArticlesPage.html.twig', []);
    }

    #[Route(
        path: '/{id}',
        name: 'showDetailedArticle'
    )]
    public function showDetailledArticle(?Article $article): Response
    {

        return $this->render('articles/articleDetail.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route(
        path: '/author/create-article',
        name: 'createNewArticle'
    )]
    public function createArticle(Request $request): Response
    {
        // Création d'un objet vide Article
        $article = new Article();

        // Création du formulaire
        $form = $this->createForm(ArticleType::class, $article);

        // Recuillir la requête
        $form->handleRequest($request);

        // Vérifier la soumission du formulaire et les donneés soumises
        if ($form->isSubmitted() && $form->isValid()) {
            dump($article);
        }

        return $this->render(
            'articles/createNewArticle.html.twig',
            [
                'form' => $form
            ]
        );
    }
}

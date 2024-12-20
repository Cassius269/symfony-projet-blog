<?php

namespace App\Controller\API;

use Exception;
use App\Entity\Article;
use App\Repository\UserRepository;
use App\Entity\MainImageIllustration;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(name: 'api_')]
class ArticleController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager, private ValidatorInterface $validator) {}

    #[Route(
        path: '/api/articles',
        name: 'get_all_articles',
        methods: ['GET'] //Ce point de terminaison est accessible en méthode GET
    )]
    public function index(ArticleRepository $articleRepository): Response
    {
        // Récupération de tous les articles
        $data = $articleRepository->getAllArticlesFromNewest();

        // Obtenir l'extrait de chaque article
        foreach ($data as $article) {
            $article->setContent(substr($article->getContent(), 0, 100) . '...'); // Mettre à jour le contenu de chaque article avec un extrait de 100 caractères
        }

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

    #[Route(
        path: '/api/articles/',
        name: 'create_new_article',
        methods: ['POST']
    )]
    public function create(Request $request, CategoryRepository $categoryRepository, UserRepository $userRepository): Response
    {
        // Récupérer l'article envoyé au serveur et le convertir en objet classique
        $content = json_decode($request->getContent());

        // Création d'un nouvel objet article
        $category = $categoryRepository->findOneBy(
            ['name' => $content->category->name]
        );

        $article = new Article();
        $article->setTitle($content->title)
            ->setContent($content->content)
            ->setAbstract($content->abstract)
            ->setCategory($category);

        // Rechercher si l'auteur de l'article existe via son mail: 1er volet de sécurité
        $searchSimilarUser = $userRepository->findOneBy(
            [
                'email' => $content->author->email
            ]
        );

        if ($searchSimilarUser) {
            $article->setAuthor($searchSimilarUser);
        } else {
            throw new \Exception('Auteur inconnu au bataillon des auteurs du blog');
        }

        // Création d'un objet image d'illustration comportant les informations de l'image associé à l'article
        $mainImageIllustration =  new MainImageIllustration;
        $mainImageIllustration->setCreatedAt(new \DateTimeImmutable())
            ->setTitle($content->mainImageIllustration->title)
            ->setImageName($content->mainImageIllustration->imagename)
            ->setSource($content->mainImageIllustration->source);

        $article->setMainImageIllustration($mainImageIllustration);

        // Gestion des erreurs
        $errors = $this->validator->validate($article);

        if (count($errors) > 0) {
            // Renvoyer chaque erreur rencontrée (cela implique un arrêt de script)
            foreach ($errors as $error) {
                throw new Exception($error->getPropertyPath() . ' : ' . $error->getMessage());
            }
        }

        // Persistance des données et envoi en base de données
        $this->entityManager->persist($article);
        $this->entityManager->flush();

        return $this->json($article, 201, [], [
            'groups' => ['article.create']
        ]);
    }

    #[Route(
        path: '/api/articles/{id}',
        name: 'delete_an_article',
        methods: ["DELETE"]
    )]
    public function delete(#[MapEntity(id: 'id')] ?Article $article): Response
    {
        // Vérifier si la ressource de type article à supprimer est bien présente dans le serveur
        if (!$article) {
            throw $this->createNotFoundException('La ressource à supprimer n\'existe pas');
        }


        $this->entityManager->remove($article);
        $this->entityManager->flush();

        // Envoyer une réponse vide 
        return $this->json(null, 204);
    }

    #[Route(
        path: '/api/articles/{id}',
        name: 'update_an_article',
        methods: ['PUT']
    )]
    public function update(?Article $article, Request $request): Response
    {
        // Gestion des erreurs
        if (!$article) {
            throw $this->createNotFoundException('La ressource à mettre à jour n\'existe pas');
        }

        // Récuperer le body de la requête contenant la charge utile
        $content = json_decode($request->getContent());

        // Modifier l'article se trouvant sur le serveur en substituant les valeurs modifiés des champs 
        $article->setTitle($content->title)
            ->setContent($content->content);

        // Valider les données
        $errors = $this->validator->validate($article);

        if (count($errors) > 0) {
            // Renvoyer chaque erreur rencontrée (cela implique un arrêt de script)
            foreach ($errors as $error) {
                throw new Exception($error->getPropertyPath() . ' : ' . $error->getMessage());
            }
        }

        // Envoyer la donnée modifiée au serveur
        $this->entityManager->flush();

        return $this->json($article, 200, [], [
            'groups' => ['articles.update']
        ]);
    }
}

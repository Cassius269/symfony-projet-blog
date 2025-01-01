<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Entity\Notification;
use App\Services\AwsManager;
use App\Services\Notificator;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NotificationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// Préfixation des routes du controller ArticleController
#[Route(path: '/articles', name: 'articles_')]
class ArticleController extends AbstractController
{
    // Déclaration des actions sur les articles (CRUD)

    #[Route(
        path: '/',
        name: 'showAll',
        methods: 'GET'
    )]
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator, AwsManager $awsManager): Response
    {
        // Récupération des articles du récent au plus ancien
        $data = $articleRepository->findBy([], [
            'createdAt' => 'DESC',
        ]);

        // dd($data);
        // Assignation des fichiers images stockés en objets chez AWS S3
        foreach ($data as $article) {
            // Obtenir le fichier image d'illustration de l'article
            $file = $awsManager->readFile($article);
            $article->setImage($file);
        }
        // Mise en place de la logique de pagination
        $articles = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1), // 1 est le numéro de page initial
            6 // 6 est la limite de résultat par page
        );


        return $this->render('articles/all_articles_pages.html.twig', [
            'articles' => $articles
        ]);
    }

    #[Route(
        path: '/{slug}',
        name: 'showDetailedArticle',
        methods: 'GET'
    )]
    public function showDetailledArticle(
        #[MapEntity(mapping: ['slug' => 'slug'])] ?Article $article,
        EntityManagerInterface $entityManager,
        AwsManager $awsManager,
        Request $request,
        NotificationRepository $notificationRepository,
    ): Response {
        // dd($idNotif);
        // dd($article);
        // dd($this->getUser());

        if (!$article) { // Si il n'y pas d'article trouvé, envoyer un message d'exception
            throw $this->createNotFoundException('Article introuvable');
        } else { // Dans le cas où l'article existe, chercher son image d'illustration depuis le service S3 d'Amazon AWS
            $mainImageIllustration = $article->getMainImageIllustration();
            $file = $awsManager->readFile($article); // Récuperer en lecture le fichier image d'illustration sur AWS S3 en utilisant le service personnalisé AwsManager
        }

        // Changer l'état d'une notification en déjà lue au clic par l'Admin
        if ($this->getUser() && $this->getUser()->getRoles() === ["ROLE_ADMIN"]) { // Si c'est un utilisateur connecté qui accède et qu'il est Admin
            $idNotif = $request->get('id_notif');
            // dd($idNotif);

            // dump('L\'utilisateur connecté est un Admin');
            // Chercher la notification à l'origine de l'action de notification
            // Plusieurs objets notifications peuvent êre reliées à un même objet article pour differentes actions (update, delete, remove)
            if ($idNotif) { // Si l'Admin accède à l'URL avec un paramètre "id_notification" disponible
                $notification = $notificationRepository->findById($idNotif)[0]; // Chercher la notification
                // dd($notification);
                $notification->setRead(true); // Mettre à jour la notification à déjà lue
                $entityManager->flush(); // Mettre à jour la base de données

                // Stocker dans la session à nouveau les notifications non lues réactualisées
                $unReadNotifications = $notificationRepository->getUnreadNotifications();
                $session = $request->getSession(); // Obtenir la session
                $session->set('unReadNotifications', $unReadNotifications);
            }
        }


        // Mettre à jour le compteur du nombre de vue d'un article
        $actualNumberOfViews = $article->getNbreOfViews();
        $article->setNbreOfViews($actualNumberOfViews + 1); // Incrémenter le nombre de vues à chaque lecture d'un article
        $entityManager->flush();

        return $this->render('articles/article_detail.html.twig', [
            'article' => $article,
            'mainImageIllustration' => $mainImageIllustration,
            'file' => $file
        ]);
    }

    #[Route(
        path: '/author/create-article',
        name: 'createNewArticle',
        methods: ['GET', 'POST']
    )]
    #[IsGranted("ROLE_AUTHOR")]
    public function createArticle(Request $request, Security $security, EntityManagerInterface $entityManager, HtmlSanitizerInterface $htmlSanitizer, Notificator $notif, ArticleRepository $articleRepository): Response
    {
        /** @var Author $author */
        $author = $security->getUser();

        // dump($author);
        if ($author->isAccepted() == true) {
            // Création d'un objet vide Article
            $article = new Article();

            // Création du formulaire
            $form = $this->createForm(ArticleType::class, $article);

            // Recuillir la requête
            $form->handleRequest($request);

            // Vérifier la soumission du formulaire et les donneés soumises
            if ($form->isSubmitted() && $form->isValid()) {
                // dd($article);
                // Nettoyage des données contre les injections de code malveillantes
                $unsafeContentArticle = $form->get('content')->getData();
                $safeContentArticle = $htmlSanitizer->sanitize($unsafeContentArticle);

                // Vérifier si l'article n'est pas déjà publié
                $similarArticle = $articleRepository->findOneBy([
                    "title" => $form->get("title")->getData(),
                    "content" => $safeContentArticle // recherche que le contenu safe
                ]);

                // Si l'article existe déjà, relancer la route de création d'article

                if ($similarArticle) {
                    $this->addFlash('error', "Un article similaire existe");
                    return $this->redirectToRoute('articles_createNewArticle');
                }

                $article->setContent($safeContentArticle)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setAuthor($author)
                    ->setNbreOfViews(0); // Iniitialiser le compteur du nombre de vues d'un articles à zéro

                // Gestion de la photo
                $article->getMainImageIllustration()->setCreatedAt(new \DateTimeImmutable()); // L'assignation de la date de création à l'image principale a permis de débogguer l'ereur de non persistance de l'article en base de données

                // Enregistrer en base de donnée le nouvel article ayant été soumis et validé
                $entityManager->persist($article);
                $this->addFlash('success', 'Votre article a été publié avec succès');
                $entityManager->flush();

                // Créer un objet de notification lié à la création de l'article, à stocker en base de données
                $type = "article";
                $idObject =  $article->getId(); // récuperer la clé primaire de l'objet article nouvellement créé
                $message = 'Un article a été créé';

                $notification = new Notification();
                $notification->setCreatedAt(new \DateTimeImmutable())
                    ->setAuthor($author)
                    ->setType($type)
                    ->setRead(false)
                    ->setContent($message)
                    ->setArticle($article);
                $entityManager->persist($notification);
                $entityManager->flush();

                // Envoyer une notification à l'Admin avant de redirigier l'utilisateur
                $notif->send($message, $type, $idObject, $notification->getId());

                return $this->redirectToRoute('articles_showAll');
            }

            return $this->render(
                'articles/create_or_edit_article.html.twig',
                [
                    'form' => $form
                ]
            );
        } else {
            // Envoyer un message d'erreur
            $this->addFlash('error', 'Vous n\'êtes pas autorisé(e) à écrire un nouvel article');


            return new RedirectResponse($this->generateUrl('login')); // rediriger l'utilisateur vers la page de connexion         }
        }
    }

    #[Route(
        path: '/remove/{id}',
        name: 'remove',
        methods: 'POST'
    )]
    public function removeArticle(
        #[MapEntity(id: 'id')] ?Article $article,
        EntityManagerInterface $entityManager,
        Request $request
    ): Response {
        // Garantir que l'action de suppresion d'un article ne sera autorisée que par l'Admin ou l'auteur de l'article lui-même
        $this->denyAccessUnlessGranted('REMOVE_ARTICLE', $article); // Gestion de la permission de suppresion par un voter


        if (!$article) {
            throw new NotFoundHttpException('Article introuvable');
        }

        // Vérifier la validité du token CSRF avant de procéder à la suppresion de l'article
        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete-article-' . $article->getId(), $submittedToken)) {
            if ($article) {
                $entityManager->remove($article);
                $entityManager->flush();
                $this->addFlash("success", "Article supprimé avec succès"); // Envoi d'un message de succès
            }
        } else {
            $this->addFlash('error', 'Token CSRF invalide');
        }

        // Rediriger l'utilsateur vers la page d'accueil de l'application si suppression réussi
        return $this->redirectToRoute('home');
    }

    #[Route(
        path: '/author/update-article/{id}',
        name: 'update',
        methods: ['GET', 'POST']
    )]
    #[IsGranted("ROLE_AUTHOR")]
    public function updateArticle(#[MapEntity(id: 'id')] ?Article $article, Request $request, EntityManagerInterface $entityManager, HtmlSanitizerInterface $htmlSanitizer, AwsManager $awsManager): Response
    {
        $this->denyAccessUnlessGranted('UPDATE_ARTICLE', $article); // Gestion de la permission de modification d'un artickle via un voter

        if (!$article) { // Si l'article n'existe pas renvoyer une exception de ressource non trouvée
            throw $this->createNotFoundException('L\'article n\'existe pas');
        }

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            // dd($form->getData());

            // Nettoyage du contenu de l'article
            $unsafeContentArticle = $form->get('content')->getData();
            $safeContentArticle = $htmlSanitizer->sanitize($unsafeContentArticle);

            $article->setContent($safeContentArticle)
                ->setUpdatedAt(new \DateTime());

            $entityManager->flush(); // Envoyer l'article mise à jour en base de donnnées

            $this->addFlash('success', 'Votre article a été mise à jour');
            return $this->redirectToRoute('home');
        }

        // Réutiliser le même template que la création d'article d'article 
        return $this->render(
            'articles/create_or_edit_article.html.twig',
            [
                'form' => $form,
                'article' => $article,
                'mainImageIllustrationFile' =>  $awsManager->readFile($article) // Obtenir le fichier image d'illustration de l'article

            ]
        );
    }
}

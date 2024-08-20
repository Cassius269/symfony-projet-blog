<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Notification;
use App\Form\ArticleType;
use App\Repository\NotificationRepository;
use App\Services\AwsManager;
use App\Services\Notificator;
use Doctrine\ORM\EntityManagerInterface;
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

#[Route(path: '/articles', name: 'articles_')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'showAll')]
    public function index(): Response
    {
        return $this->render('articles/all_articles_page.html.twig', []);
    }

    #[Route(
        path: '/{id}',
        name: 'showDetailedArticle',
    )]
    public function showDetailledArticle(
        Article $article,
        EntityManagerInterface $entityManager,
        AwsManager $awsManager,
        Request $request,
        NotificationRepository $notificationRepository,
        Notificator $notificator
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

            dump('L\'utilisateur connecté est un Admin');
            // Chercher la notification à l'origine de l'action de notification
            // Plusieurs objets notifications peuvent êre reliées à un même objet article pour differentes actions (update, delete, remove)
            if ($idNotif) { // Si l'Admin accède à l'URL avec un paramètre "id_notification" disponible
                $notification = $notificationRepository->findById($idNotif)[0]; // Chercher la notification
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
        name: 'createNewArticle'
    )]
    #[IsGranted("ROLE_AUTHOR")]
    public function createArticle(Request $request, Security $security, EntityManagerInterface $entityManager, HtmlSanitizerInterface $htmlSanitizer, Notificator $notif): Response
    {
        /** @var User $user */
        $user = $security->getUser();

        dump($user);
        if ($user->isAccepted() == true) {
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

                $article->setContent($safeContentArticle)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setAuthor($user)
                    ->setNbreOfViews(0); // Iniitialiser le compteur du nombre de vues d'un articles à zéro

                // Gestion de la photo
                $article->getMainImageIllustration()->setCreatedAt(new \DateTimeImmutable());

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
                    ->setAuthor($user)
                    ->setType($type)
                    ->setRead(false)
                    ->setContent($message)
                    ->setArticle($article);
                $entityManager->persist($notification);

                // Envoyer une notification à l'Admin avant de redirigier l'utilisateur
                $notif->send($message, $type, $idObject);

                $entityManager->flush();

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
            $this->addFlash('error', 'Vous n\'est pas autorisé(e) à écrire');


            $security->logout('false'); // Déconnecter l'utilisateur
            return new RedirectResponse($this->generateUrl('login')); // rediriger l'utilisateur vers la page de connexion         }
        }
    }

    #[Route(
        path: '/remove/{id}',
        name: 'remove'
    )]
    public function removeArticle(Article $article, EntityManagerInterface $entityManager): Response
    {
        // Garantir que l'action de suppresion d'un article ne sera autorisée que par l'Admin ou l'auteur de l'article lui-même
        $this->denyAccessUnlessGranted('REMOVE_ARTICLE', $article); // Gestion de la permission de suppresion par un voter

        if (!$article) {
            throw new NotFoundHttpException('Article introuvable');
        }

        if ($article) {
            $entityManager->remove($article);
            $entityManager->flush();
            $this->addFlash("success", "article supprimé");
        }

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/author/update-article/{id}', name: 'update')]
    #[IsGranted("ROLE_AUTHOR")]
    public function updateArticle(Article $article, Request $request, EntityManagerInterface $entityManager, HtmlSanitizerInterface $htmlSanitizer, AwsManager $awsManager): Response
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

            $article->setContent($safeContentArticle);
            $article->setUpdatedAt(new \DateTime());
            $entityManager->flush();

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

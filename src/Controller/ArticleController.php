<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Validator\Constraints as Assert;
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
        name: 'showDetailedArticle'
    )]
    public function showDetailledArticle(Article $article, EntityManagerInterface $entityManager): Response
    {
        // dd($article);
        if (!$article) { // Si il n'y pas d'article trouvé, envoyer un message d'exception
            throw $this->createNotFoundException('Article introuvable');
        } else { // Dans le cas où l'article existe, chercher son image d'illustration
            $mainImageIllustration = $article->getMainImageIllustration();
        }

        // Mettre à jour le compteur du nombre de vue d'un article
        $actualNumberOfViews = $article->getNbreOfViews();
        $article->setNbreOfViews($actualNumberOfViews + 1);
        $entityManager->flush($article);

        return $this->render('articles/article_detail.html.twig', [
            'article' => $article,
            'mainImageIllustration' => $mainImageIllustration
        ]);
    }

    #[Route(
        path: '/author/create-article',
        name: 'createNewArticle'
    )]
    #[IsGranted("ROLE_AUTHOR")]
    public function createArticle(Request $request, Security $security, EntityManagerInterface $entityManager, HtmlSanitizerInterface $htmlSanitizer): Response
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
                $entityManager->flush();

                $this->addFlash('success', 'Votre article a été publié avec succès');
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
    public function updateArticle(Article $article, Request $request, EntityManagerInterface $entityManager, HtmlSanitizerInterface $htmlSanitizer): Response
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
                'article' => $article
            ]
        );
    }
}

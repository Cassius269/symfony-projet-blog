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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
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
    #[IsGranted("ROLE_AUTHOR")]
    public function createArticle(Request $request, Security $security, EntityManagerInterface $entityManager): Response
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
                dump($article);

                $article->setCreatedAt(new DateTimeImmutable())
                    ->setAuthor($user);

                $file = $form['imageIllustration']->getData();
                $validExtensions = ['png', 'jpeg', 'jpg'];
                $extension = $file->guessExtension();

                // dd($file);
                if (in_array($extension, $validExtensions)) { // Si l'extension est effectivement valide
                    $filename = 'images/illustrations' . $article->getTitle(); // nom du fichier
                    $article->setImageIllustration($filename);
                    $file->move('images/illustrations', $filename);
                }
                // Enregistrer en base de donnée le nouvel article ayant été soumis et validé
                $entityManager->persist($article);
                $entityManager->flush($article);

                $this->addFlash('success', 'Votre article a été publié avec succès');
                return $this->redirectToRoute('articles_showAll');
            }

            return $this->render(
                'articles/createNewArticle.html.twig',
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
}

<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Action pour enregistrer un utilisateur en base de données
    #[Route(
        path: '/register',
        name: 'register'
    )]
    #[IsGranted("ROLE_ADMIN")]
    public function register(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $user->setFirstname('Fahami')
            ->setLastname('MOHAMED ALI')
            ->setEmail('fahamygaston@gmail.com')
            ->setRoles(['ROLE_AUTHOR'])
            ->setPassword($userPasswordHasher->hashPassword($user, '123456789')) // Par défaut utiliser un algo de sécurité comme bin2hex(random_bytes(10))
            ->setAccepted(true)
            ->setCreatedAt(new \DateTimeImmutable());


        $entityManager->persist($user);
        $entityManager->flush();

        // return $this->render('security/index.html.twig', []);
    }


    // Action pour connecter un utilisateur
    #[Route(
        path: '/login',
        name: 'login'
    )]
    public function connect(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($error) {
            $this->addFlash('error', 'Une erreur s\'est produite');
        }

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'username' => $username
        ]);
    }

    // Méthode pour la déconnexion
    #[Route('/logout', name: 'logout')]
    public function logout()
    {
    }
}

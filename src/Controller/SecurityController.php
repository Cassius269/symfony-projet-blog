<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    // Action pour enregistrer un utilisateur en base de données
    #[Route('/register', name: 'register')]
    public function register(): Response
    {

        return $this->render('security/index.html.twig', []);
    }


    // Action pour connecter un utilisateur
    public function connect(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $username = $authenticationUtils->getLastUsername();

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

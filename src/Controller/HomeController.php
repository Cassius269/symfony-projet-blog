<?php

namespace App\Controller;

use App\Services\Notificator; // Service personnalisé de création de notifiications instantannées grâce à Mercure
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    // Test du service personnalisé d'envoi de notifications instantannnées à l'aide de mercure
    #[Route('/publish', name: 'publish')]
    #[IsGranted("ROLE_ADMIN")] // Protégér la route de test contre toute tentative d'autres utilisateurs
    public function publish(Notificator $notif): Response
    {
        $notif->send('Un mail a été envoyé', 'demand', 20);

        return new Response('Notification envoyée!');
    }
}

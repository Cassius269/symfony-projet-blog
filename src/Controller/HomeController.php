<?php

namespace App\Controller;

use App\Services\Notificator;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', []);
    }

    // Test de mercure
    #[Route('/publish', name: 'publish')]
    public function publish(HubInterface $hub, Notificator $notif): Response
    {
        $user = $this->getUser();
        $notif->send('Un mail a été envoyé par John Dow', 'email', $user);

        return new Response('Notification envoyée!');
    }
}

<?php

namespace App\Controller;

use Symfony\Component\Mercure\Update;
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
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'notifications',
            json_encode(['status' => 'message envoyé'])
        );
        $hub->publish($update);
        return new Response('Notification envoyée!');
    }
}

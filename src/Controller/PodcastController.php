<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: "podcasts_")]
class PodcastController extends AbstractController
{
    #[Route('/podcasts', name: 'show_all')]
    public function index(): Response
    {
        return $this->render('podcasts/show_all_podcasts.html.twig', []);
    }
}

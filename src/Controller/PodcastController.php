<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Podcast;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: "podcasts_")]
class PodcastController extends AbstractController
{
    #[Route(path: '/podcasts', name: 'show_all')]
    public function index(): Response
    {
        return $this->render('podcasts/show_all_podcasts.html.twig', []);
    }

    #[Route(path: '/podcasts/{id}', name: 'show_detailled_podcast')]
    public function showDetailledPodcast(Podcast $podcast): Response
    {
        return $this->render('/podcasts/show_detailled_podcast.html.twig', [
            'podcast' => $podcast,
        ]);
    }

    #[Route(
        path: '/podcasts/{podcast}/{episode}',
        name: 'show_detailled_episode'
    )]
    public function showDetailledEpisode(Podcast $podcast, Episode $episode): Response
    {
        return $this->render('podcasts/show_detailled_episode.html.twig', []);
    }
}

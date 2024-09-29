<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Podcast;
use App\Repository\EpisodeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EpisodeController extends AbstractController
{
    #[Route(
        path: '/podcasts/{podcast}/{episode}',
        name: 'show_detailled_episode',
        methods: "GET"
    )]
    public function showDetailledEpisode(
        #[MapEntity(id: 'podcast')] Podcast $podcast,
        #[MapEntity(id: 'episode')] ?Episode $episode,
        EpisodeRepository $episodeRepository
    ): Response {
        // dd($podcast->getName());
        $randomEpisodes = $episodeRepository->getSimilarRandomEpisodes($podcast, 3);

        //dd($randomEpisodes);
        return $this->render('episodes/show_detailled_episode.html.twig', [
            'randomEpisodes' => $randomEpisodes,
        ]);
    }
}

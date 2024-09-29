<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Podcast;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(name: "podcasts_")]
class PodcastController extends AbstractController
{
    #[Route(path: '/podcasts', name: 'show_all', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('podcasts/show_all_podcasts.html.twig', []);
    }

    #[Route(
        path: '/podcasts/{id}',
        name: 'show_detailled_podcast',
        methods: 'GET'
    )]
    public function showDetailledPodcast(#[MapEntity(id: 'id')] ?Podcast $podcast): Response
    {
        // Si aucun podcast ayant l'id passé en paramètre, alors renvoyer une erreur 404
        if (!$podcast) {
            throw $this->createNotFoundException('Ce podcast n\'existe pas');
        }

        // Autrement pour tout podcast existant, afficher la vue correspondante
        return $this->render('/podcasts/show_detailled_podcast.html.twig', [
            'podcast' => $podcast,
        ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(
    path: '/rgpd',
    name: 'rgpd_'
)]
class RgpdController extends AbstractController
{
    #[Route(
        path: '/legal_notices',
        name: 'legal_notices'
    )]
    public function index(): Response
    {
        return $this->render('rgpd/legal_notices.html.twig', []);
    }

    #[Route(
        path: '/cookies_policy',
        name: 'cookies_policy'
    )]
    public function showCookiesPolicy(): Response
    {
        return $this->render('rgpd/cookies_policy.html.twig', []);
    }
}

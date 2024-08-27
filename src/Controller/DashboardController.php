<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    #[Route(path:'/dashboard', name: 'dashboard', methods: 'GET')]
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig', []);
    }
}

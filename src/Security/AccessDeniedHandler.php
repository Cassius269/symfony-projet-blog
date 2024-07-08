<?php

// src/Security/AccessDeniedHandler.php
namespace App\Security;

use Twig\Environment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        // $content = $this->twig->render("accessDenied.html.twig");

        // Retourne une instance de Response avec le contenu et le statut 403
        return new Response("Accès non autorisée", 403);
    }
}

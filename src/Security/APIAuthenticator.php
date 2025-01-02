<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;


class APIAuthenticator extends AbstractAuthenticator
{
    public function __construct(private UserRepository $userRepository) {}

    // Vérifie si l'authentificateur prend en charge la requête 
    public function supports(Request $request): ?bool
    {
        // Vérifie si l'en-tête 'Authorization' est présent et contient 'Bearer ' 
        return $request->headers->has('Authorization') && str_contains($request->headers->get('Authorization'), 'Bearer');
    }

    // // 1ère méthode : Authentifie la requête 
    // public function authenticate(Request $request): Passport
    // {
    //     // Extrait le jeton d'authentification de l'en-tête 
    //     $identifier = str_replace('Bearer ', '', $request->headers->get('Authorization'));

    //     // Crée un passeport auto-validant avec le jeton comme identifiant 
    //     return new SelfValidatingPassport(
    //         new UserBadge($identifier)
    //     );
    // }

    // 2ème méthode : Authentifie la requête avec avantage de traduction de personnalisation de l'erreur de l'authentification
    public function authenticate(Request $request): Passport
    {
        $authHeader = $request->headers->get('Authorization');
        $token = substr($authHeader, 7); // Retire "Bearer "

        return new Passport(
            new UserBadge($token, function ($userIdentifier) {
                // Load/verify the user identifier (i.e. the token)
                $user = $this->userRepository->findOneBy(['apiToken' => $userIdentifier]);

                if (!$user) {
                    throw new CustomUserMessageAuthenticationException('Token de l\'API invalide.');
                }

                return $user;
            }),
            new CustomCredentials(
                function ($credentials, $user) {
                    // No additional credential check needed as the token itself is the credential
                    return true;
                },
                $token
            )
        );
    }

    // Appelé en cas de succès de l'authentification 
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Aucune action spécifique nécessaire en cas de succès 
        return null;
    }

    // Appelé en cas d'échec de l'authentification 
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // Renvoie une réponse JSON avec un message d'erreur et un code 401 (Non autorisé) 
        return new JsonResponse(
            [
                'message' => $exception->getMessage()
            ],
            Response::HTTP_UNAUTHORIZED
        );
    }
}

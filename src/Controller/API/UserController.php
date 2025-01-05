<?php



namespace App\Controller\API;

use App\Repository\UserRepository;
use App\Security\APIAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route(name: "api_")] // Préfixation des noms de routes de ce controller
class UserController extends AbstractController

{

    #[Route(
        path: "/api/me", // route de l'action me 
        name: "me" // nom de la route portant l'action me 
    )]

    #[IsGranted("ROLE_AUTHOR")] // Seul un utilisateur ayant le rôle ROLE_AUTHOR peut accéder à cette route 
    public function me(): Response
    {
        // Renvoyer l'utilisateur autorisé depuis le token API entré dans les headers sur la clé Authorization 
        return $this->json($this->getUser(), 200, [], [
            'groups' => ['users.me'] // Exposition des champs appartenant au groupe "users.me" 
        ]);
    }

    #[Route(
        path: '/api/auth',
        name: "generating_token",
        methods: ['POST', 'GET'] // La méthode POST pour recevoir les identifiants et la méthode GET pour renvoyer le token
    )]
    public function generateToken(Request $request, Security $security, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $content = json_decode($request->getContent()); // transformer le contenu de la requête en objet classique

        if (property_exists($content, 'username') && property_exists($content, 'password')) {
            // Rechercher un auteur ayant le mail renseigné dans les identifiants
            $simillarUser = $userRepository->findOneByEmail($content->username);

            // Vérifier le mot de passe renseigné
            if ($simillarUser && $passwordHasher->isPasswordValid($simillarUser, $content->password)) {

                if ($simillarUser->isAccepted()) { // Si l'utilisateur est vérifié, générer un token sécurisé
                    $apiToken = bin2hex(random_bytes(32));
                    $simillarUser->setApiToken($apiToken);
                    $entityManager->flush();

                    $response = [
                        "token" => $apiToken
                    ];
                    // Renvoyer une réponse JSON contenant le token
                    return $this->json($response, 200);
                }
            }
        }

        throw new UnauthorizedHttpException("", "Authentification échouée"); // Si identifiants invalides, envoyer une exception de refus d'accès
    }
}

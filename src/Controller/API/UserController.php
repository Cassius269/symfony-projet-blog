<?php



namespace App\Controller\API;



use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Attribute\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Http\Attribute\IsGranted;



class UserController extends AbstractController

{

    #[Route(
        path: "/api/me", // route de l'action me 
        name: "api_me" // nom de la route portant l'action me 
    )]

    #[IsGranted("ROLE_AUTHOR")] // Seul un utilisateur ayant le rôle ROLE_AUTHOR peut accéder à cette route 
    public function me(): Response
    {
        // Renvoyer l'utilisateur autorisé depuis le token API entré dans les headers sur la clé Authorization 
        return $this->json($this->getUser(), 200, [], [
            'groups' => ['users.me'] // Exposition des champs appartenant au groupe "users.me" 
        ]);
    }
}

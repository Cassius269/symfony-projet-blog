<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

// Création d'un service personnalisé pour hasher les mots de passe des utilisateurs en phase de developpement pour éviter la répétion de hashage de mot de passe
class PasswordUtilityService
{
    // Injection de dépendances
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private ParameterBagInterface $parameterBag) {}

    // Action pour obtenir un mot de passe hashé depuis la variable d'environnement "DEFAULT_FIXTURE_PASSWORD"
    public function getAhashedPassword(User $user): string
    {
        // ATTENTION A UTILISER UNIQUEMENT POUR LES FIXTURES

        // Création du mot de passe
        $plaintextPassword = $this->parameterBag->get('default_password') ?? 'defaultPassword'; // Récupération de la variable d'environnement depuis les services, sinon le mot de passe sera par défaut "defaultPassword"

        // Hashage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );

        return $hashedPassword; // entrer le mot de passe hashé dans le nouvel objet utilisateur à créer
    }
}

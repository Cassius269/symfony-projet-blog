<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Author;
use App\Services\PasswordUtilityService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthorFixtures extends Fixture
{
    // Injection de dépendances
    public function __construct(private PasswordUtilityService $passwordUtilityService) {}

    public function load(ObjectManager $manager): void
    {
        // Créer une instance de Faker
        $faker = Factory::create('fr_FR'); // Régionalisation de Faker en français

        // Génération automatique de 20 auteurs ayant chacun surtout un email identique
        for ($i = 0; $i < 10; $i++) {
            $author = new Author;
            $author->setCreatedAt(new \DateTimeImmutable())
                ->setFirstname($faker->firstname()) // ajout de prénom aléatoire
                ->setLastname($faker->lastname()) // ajout de nom de famille aléatoire 
                ->setEmail($faker->unique()->freeEmail()) // ajout d'email aléatoire appartenant à un domaine de messagerie gratuite comme "hotmail", "gmail", etc
                ->setRoles(["ROLE_AUTHOR"])
                ->setAccepted($faker->boolean());

            // Génération de token pour chaque auteur accepté
            if ($author->isAccepted()) {
                $author->setApiToken($faker->SHA256()); // génération de token solide et sécurisé
            }

            $author->setPassword($this->passwordUtilityService->getAhashedPassword($author)); // entrer le mot de passe hashé à l'aide d'un service personnalisé dans le nouvel objet auteur

            // Ecrire la requête SQL de création de nouvel utilisateur
            $manager->persist($author);
        }

        // Envoyer les nouveaux utilisateurs fictifs en base de données 
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Admin;
use Doctrine\Persistence\ObjectManager;
use App\Services\PasswordUtilityService;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AdminFixtures extends Fixture
{
    // Injection de dépendances
    public function __construct(private PasswordUtilityService $passwordUtilityService) {}

    public function load(ObjectManager $manager): void
    {
        // Instanciation de faker
        $faker = Factory::create('fr_FR');

        // Création d'un nouvel objet Admin
        $admin = new Admin;
        $admin->setCreatedAt(new \DateTimeImmutable())
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setEmail($faker->unique()->freeEmail())
            ->setAccepted(true)
            ->setApiToken($faker->SHA256()) // génération de token solide et sécurisé
            ->setRoles(['ROLE_ADMIN']);

        // Création d'un mot de passe hashé
        $admin->setPassword($this->passwordUtilityService->getAhashedPassword($admin)); // entrer le mot de passe hashé à l'aide d'un service personnalisé dans le nouvel objet auteur

        // Persistence de la donnée et envoi en base de donnée du nouvel Admin
        $manager->persist($admin);
        $manager->flush();
    }
}

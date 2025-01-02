<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthorFixtures extends Fixture
{
    // Injection de dépendances
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        // Création d'un nouvel auteur fictif
        $author = new Author;
        $author->setCreatedAt(new \DateTimeImmutable())
            ->setFirstname('John')
            ->setLastname('Dow')
            ->setEmail('wddhv4kbw@mozmail.com')
            ->setRoles(["ROLE_AUTHOR"])
            ->setAccepted(true);

        // Création du mot de passe
        $plaintextPassword = "123456789"; // mot de passe en clair

        $hashedPassword = $this->passwordHasher->hashPassword(
            $author,
            $plaintextPassword
        );

        $author->setPassword($hashedPassword); // entrer le mot de passe hashé dans le nouvel objet auteur

        // Ecrire la requête SQL et envoyer en base de données le nouvel utilisateur
        $manager->persist($author);
        $manager->flush();
    }
}

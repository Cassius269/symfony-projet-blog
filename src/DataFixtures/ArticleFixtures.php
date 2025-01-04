<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\DataFixtures\CategoryFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Instancier faker
        $faker = Factory::create('fr_FR'); // Régionnalisation en français des données aléatoires générées 

        // Génération de 10 articles fictifs à la volée
        $numberElementsCategories = count(CategoryFixtures::CATEGORIES) - 1;

        for ($i = 0; $i < 10; $i++) {
            // $author = ; // obtenir l'objet de réference d'un auteur

            $article = new Article;
            $article->setCreatedAt(new \DateTimeImmutable())
                ->setTitle($faker->sentence(5))
                ->setContent($faker->paragraph(10, false)) // génération de contenu ayant EXACTEMENT 10 phrases indiqué avec le booléen "false"
                ->setAbstract($faker->paragraph(2))
                ->setCategory($this->getReference('category' . $faker->numberBetween(0, $numberElementsCategories))) // ajout de la réference unique de catégorie dans l'auteur d'un article
                ->setMainImageIllustration($this->getReference('mainImageIllustration'));

            // Seul un auteur accepté peut écrire un article
            if ($this->getReference('auteur' . $i)->isAccepted()) {
                $article->setAuthor($this->getReference('auteur' . $i)); // ajout de la réference unique d'auteur dans l'auteur d'un article
            } else {
                continue; // si un auteur n'est pas accepté, sauter directement à l'itération suivante
            }


            // Persister chaque article
            $manager->persist($article);
        }

        $manager->flush();
    }

    // Récupérer les données fictives des auteurs comme dépendance 
    public function getDependencies()
    {
        return [
            AuthorFixtures::class,
            CategoryFixtures::class,
            MainImageIllustrationFixtures::class
        ];
    }
}

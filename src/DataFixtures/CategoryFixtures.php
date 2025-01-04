<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    const CATEGORIES = ["mode", "tendances", "éthique"];

    public function load(ObjectManager $manager): void
    {
        // Récupération des noms de catégories de la constante de la classe
        $categories = CategoryFixtures::CATEGORIES;

        // Persister toutes les catégories
        for ($i = 0; $i < count($categories); $i++) {
            $category = new Category;
            $category->setName(mb_convert_case($categories[$i], MB_CASE_TITLE, 'UTF-8')); // La fonction mb_convert_case met en majuscule la première lettre d'un mot même si c'est une lettre avec accent

            // Création de réference unique pour chaque catégorie en vue de la relation entre entités Category et Article
            $this->addReference('category' . $i, $category);

            // Persister chaque catégorie
            $manager->persist($category);
        }

        // Envoyer en base de données toutes les catégories persistées
        $manager->flush();
    }
}

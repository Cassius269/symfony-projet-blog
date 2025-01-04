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
        foreach ($categories as $categoryName) {
            $category = new Category;
            $category->setName(mb_convert_case($categoryName, MB_CASE_TITLE, 'UTF-8')); // La fonction mb_convert_case met en majuscule la première lettre d'un mot même si c'est une lettre avec accent

            // Persister chaque catégorie
            $manager->persist($category);
        };

        // Envoyer en base de données toutes les catégories persistées
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\MainImageIllustration;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;

class MainImageIllustrationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Instanciation de faker
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            // Enregistrement d'une nouvelle image d'illustration
            $mainImageIllustration = new MainImageIllustration;
            $mainImageIllustration->setCreatedAt(new \DateTimeImmutable())
                ->setImageName($faker->word() . '.' . $faker->fileExtension())
                ->setTitle($faker->word(5, false));
            $this->setReference('mainImageIllustration', $mainImageIllustration);

            $manager->persist($mainImageIllustration);
        }

        $manager->flush();
    }
}

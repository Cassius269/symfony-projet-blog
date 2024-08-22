<?php

namespace App\Repository\Trait;

use App\Entity\Podcast;

trait Reposositorytrait
{
    public function getSimilarRandomContents(string $entity, string $podcastName, int $limit)
    {
        $entityManager = $this->getEntityManager(); // Obtenir l'Entity Manager
        $alias = strtolower($entity[0]); // Obtenir l'alias de l'entité

        // Construction de la requête
        $query = $entityManager->createQuery(
            "SELECT " . $alias . " FROM App\Entity\\" . $entity . " " . $alias . " WHERE " . $alias . ".podcast = :podcast ORDER BY RAND()"
        )
            ->setParameter('podcast', $podcastName)
            ->setMaxResults($limit);

        $episodes = $query->getResult(); // Lancer la requête

        return $episodes; // Retourner le résultat de la requête
    }
}

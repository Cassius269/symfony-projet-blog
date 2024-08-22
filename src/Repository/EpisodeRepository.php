<?php

namespace App\Repository;

use App\Entity\Episode;
use App\Entity\Podcast;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\Trait\Reposositorytrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Episode>
 */
class EpisodeRepository extends ServiceEntityRepository
{
    use Reposositorytrait; // Utilisation des méthodes se trouvant dans le trait Reposositorytrait

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Episode::class);
    }

    //    /**
    //     * @return Episode[] Returns an array of Episode objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('e.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Episode
    //    {
    //        return $this->createQueryBuilder('e')
    //            ->andWhere('e.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getSimilarRandomEpisodes(Podcast $podcast, int $limit)
    {
        $entityManager = $this->getEntityManager(); // Obtenir l'Entity Manager
        // $alias = strtolower($entity[0]); // Obtenir l'alias de l'entité

        // Construction de la requête
        $query = $entityManager->createQuery( "SELECT e FROM App\Entity\\Episode e WHERE e.podcast = :podcast ORDER BY RAND()")
        ->setParameter("podcast", $podcast)// Injection de la valeur réélle de l'objet Podcast
            ->setMaxResults($limit);

        $episodes = $query->getResult(); // Lancer la requête

        return $episodes; // Retourner le résultat de la requête
    }
}

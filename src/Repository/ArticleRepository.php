<?php

namespace App\Repository;

use App\Entity\Article;
use App\Repository\Trait\Reposositorytrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 */
class ArticleRepository extends ServiceEntityRepository
{
    use Reposositorytrait; // Utilisation des méthodes se trouvant dans le trait Reposositorytrait

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    //    /**
    //     * @return Article[] Returns an array of Article objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Article
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    // Requête personnalisé pour obtenir les articles les plus lues par ordre décroissant (du plus grand au plus petit)
    public function getTopArticles(int $limit)
    {
        return $this->createQueryBuilder('a')
            ->where('a.createdAt BETWEEN :start_date AND :end_date') // Filtrer les articles créés entre 2 dates : entre la date d'aujourd'hui et les 7 derniers jours
            ->setParameter('start_date', new \DateTime('-1 week'))
            ->setParameter('end_date', new \DateTime())
            ->setMaxResults($limit) // Pas d'utilisation de marqueur nommé car cette fonction attend un entier à coup sûr
            ->orderBy('a.nbreOfViews', 'DESC') // Ordonner les résultats par ordre décroissant
            ->getQuery()
            ->getResult();
    }

    // Requête personnalisée pour obtenir l'article le plus lu depuis les 7 derniers jours
    public function findTopArticle(): ?Article
    {
        $result = $this->createQueryBuilder('a')
            ->where('a.createdAt BETWEEN :start_date AND :end_date') // Filtrer les articles créés entre 2 dates : entre la date d'aujourd'hui et les 7 derniers jours
            ->setParameter('start_date', new \DateTime('-1 week'))
            ->setParameter('end_date', new \DateTime())
            ->orderBy('a.nbreOfViews', 'DESC') // Ordonner les résultats pour obtenir le plus lu
            ->setMaxResults(1) // Afficher uniquement le premier résultat (le plus lu)
            ->getQuery() // Lancer la requête
            ->getResult(); // Si il y a des résultats, renvoyer le premier, sinon rienvoyer null

        return $result ? $result[0] : null;
    }
}

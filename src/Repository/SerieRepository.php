<?php

namespace App\Repository;

use App\Entity\Season;
use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    //En DQL
    public function findBestSeriesDQL(){

        $dql = "SELECT s FROM App\Entity\Serie s
                WHERE s.popularity>100
                AND s.vote > 0
                ORDER BY s.popularity DESC";

        $em = $this->getEntityManager();
        $query = $em->createQuery($dql);
        $query->setMaxResults(50);
        return $query->getResult();
    }

    //Avec QueryBuilder
    public function findBestSeries(int $popularity){
        $qb = $this->createQueryBuilder('s');
        $qb
            ->andWhere("s.vote>8")
            ->addOrderBy("s.vote","DESC")
            ->addOrderBy("s.popularity","DESC")
        //  ->andWhere("s.popularity > 200"); --> fonctionne très bien.
        //  ->andWhere("s.popularity > $popularity") --> A ne pas faire pour éviter les injections SQL;
            ->andWhere("s.popularity > :popularity") // Utilisation d'un paramètre, pour pouvoir utiliser une variable
            ->setParameter("popularity", $popularity) ; // Il faut set la variable dans la requete.

        $query = $qb->getQuery();
        $query->setMaxResults(50);
        return $query->getResult();
    }

    public function findSeriesWithPaginator(int $page){
        $limit = 50;
        $offset = $limit*($page-1); // donnes les lignes de 0 à 49, puis de 50 à 99, etc...

        $qb = $this->createQueryBuilder('s');
        $qb->orderBy("s.popularity", "DESC");
        $qb->leftJoin("s.seasons", "seasons");
        $qb->addSelect("seasons"); // Pour que les élemnents de Saison soient aussi chargés
        $qb->setMaxResults($limit);
        $qb->setFirstResult($offset);
        $query = $qb->getQuery();
        // Le paginator sert a gérer le Limit 50. Sans ça, ca va nous retourner les 50 premiers résultats, saisons comprise. Mandolarien compte pour 2 résultats car 2 saisons, etc....
        return new Paginator($query);
    }

}

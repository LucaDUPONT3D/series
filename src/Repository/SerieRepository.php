<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function save(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBestSeries(){

        //En DQL
        //récupération des séries avec un vote supérieur à 8 et une popularité supérieur à 500 ordonné par popularité

//        $dql = "SELECT s FROM App\Entity\Serie s WHERE s.vote > 8 AND s.popularity > 100 ORDER BY s.popularity DESC ";
//
//        //transforme le string en objet de requète
//        $query = $this->getEntityManager()->createQuery($dql);
//
//        //ajoute une limite de résultat
//        $query->setMaxResults(50);

        //En queryBuilder
        return $this->createQueryBuilder('s')->andWhere('s.vote > 8')->andWhere('s.popularity > 100')
                ->addOrderBy('s.popularity', 'DESC')->setMaxResults(50)->getQuery()->getResult();

    }

}

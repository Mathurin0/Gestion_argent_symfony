<?php

namespace App\Repository;

use App\Entity\Soiree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Soiree|null find($id, $lockMode = null, $lockVersion = null)
 * @method Soiree|null findOneBy(array $criteria, array $orderBy = null)
 * @method Soiree[]    findAll()
 * @method Soiree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SoireeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Soiree::class);
    }

    // /**
    //  * @return Soiree[] Returns an array of Soiree objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Soiree
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

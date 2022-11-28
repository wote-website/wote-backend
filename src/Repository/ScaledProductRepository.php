<?php

namespace App\Repository;

use App\Entity\ScaledProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ScaledProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method ScaledProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method ScaledProduct[]    findAll()
 * @method ScaledProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScaledProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ScaledProduct::class);
    }

    // /**
    //  * @return ScaledProduct[] Returns an array of ScaledProduct objects
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
    public function findOneBySomeField($value): ?ScaledProduct
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

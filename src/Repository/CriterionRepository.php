<?php

namespace App\Repository;

use App\Entity\Criterion;
use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Criterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Criterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Criterion[]    findAll()
 * @method Criterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Criterion::class);
    }

    /**
     * @return Criterion[] Returns an array of Criterion objects
     */
    
    public function findAllWithTheme()
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.theme', 't')
            ->addSelect('t')             
      
            //->leftJoin('c.ratings', 'r')
            //->addSelect('r')   
            //->andWhere('c.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy('c.code', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllWithWeightings(Profile $profile)
    {
        return $this->createQueryBuilder('cri')
            ->leftJoin('cri.weightings', 'wei')
            ->addSelect('wei')
            ->andWhere('wei.profile = :profile')
            ->setParameter('profile', $profile)
            //->orWhere('cri.weightings = :empty')
            //->setParameter('empty', [])
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Criterion
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

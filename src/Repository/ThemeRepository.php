<?php

namespace App\Repository;

use App\Entity\Theme;
use App\Entity\Profile;
use App\Entity\Priority;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Theme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Theme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Theme[]    findAll()
 * @method Theme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Theme::class);
    }

    /**
     * @return Theme[] Returns an array of Theme objects
     */
    
    public function findAllWithCriteria()
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.criteria', 'c')
            ->addSelect('c')
            ->orderBy('t.code', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }    

    /**
     * @return Theme[] Returns an array of Theme objects
     */
    
    public function findAllWithPriorityInProfile(Profile $profile)
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('t.priorities', 'pri')
            ->addSelect('pri')
            ->andWhere('pri.profile = :profile')
            ->setParameter('profile', $profile)
            ->orderBy('t.code', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }    
    

    public function findOnebyPriorityWithCriteria(Priority $priority)
    {
        return $this->createQueryBuilder('the')
            ->join('the.priorities', 'pri')
            ->addSelect('pri')
            ->andWhere('pri.id = :priorityId')
            ->setParameter('priorityId', $priority->getId())
            ->join('the.criteria', 'cri')
            ->addSelect('cri')
            //->orderBy('t.code', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    /*
    public function findOneBySomeField($value): ?Theme
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

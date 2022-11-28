<?php

namespace App\Repository;

use App\Entity\Criterion;
use App\Entity\Priority;
use App\Entity\Profile;
use App\Entity\Weighting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Weighting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Weighting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Weighting[]    findAll()
 * @method Weighting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeightingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Weighting::class);
    }

    /**
     * This function is used to load and persist the weightings sum in the profile entity.
     * For the moment, it is done at profile UPDATE, waiting for rating update event creation.
     * @return [] containing the sum of the weightings for the given profile
     */
    
    public function findSumByOneProfile(Profile $profile)
    {
        return $this->createQueryBuilder('w')
            ->select('SUM(ABS(w.value)) AS sumValue')
            ->andWhere('w.profile = :pro')
            ->setParameter('pro', $profile)
            ->getQuery()
            ->getSingleResult()
        ;
    }
        
    /**
     * This function is used to load and persist the weightings sum in the priority entity.
     * For the moment, it is done at profile UPDATE, waiting for rating update event creation.
     * @return [] containing the sum of the weightings for the given priority
     */
    
    public function findSumByOnePriority(Priority $priority)
    {
        return $this->createQueryBuilder('w')
            ->select('SUM(ABS(w.value)) AS sumValue')
            ->andWhere('w.priority = :priority')
            ->setParameter('priority', $priority)
            ->getQuery()
            ->getSingleResult()
        ;
    }        

    /**
     * Used to check if one weighting already exists for this Criterion in this Profile
     */
    
    public function findOneFromCriterionAndProfile(Criterion $criterion,Profile $profile): ?Weighting
    {
        return $this->createQueryBuilder('wei')
            ->andWhere('wei.profile = :profile')
            ->setParameter('profile', $profile)
            ->andWhere('wei.criterion = :criterion')
            ->setParameter('criterion', $criterion)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    

    /**
     * Get all the weightings of the profile through the priorities.
     * @param  Profile $profile [description]
     * @return Rating[]           [description]
     */
    public function findAllForOneProfile(Profile $profile)
    {
        return $this->createQueryBuilder('wei')
            ->join('wei.priority', 'prio')
            ->andWhere('prio.profile = :profile')
            ->setParameter('profile', $profile)
            ->getQuery()
            ->getResult()
        ;
    }    

   
}

<?php

namespace App\Repository;

use App\Entity\Profile;
use App\Entity\Priority;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }

    
    public function findOneWithPriorities(Profile $profile, Priority $priority)
    {
        return $this->createQueryBuilder('pro')
            ->join('pro.priorities', 'pri')
            ->addSelect('pri')
            ->andWhere('pri.id = :priorityId')
            ->setParameter('priorityId', $priority->getId())
            //->orderBy('pro.id', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    
    public function findMyProfiles($user)
    {
        return $this->createQueryBuilder('pro')
            ->andWhere('pro.author = :val')
            ->setParameter('val', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPublicProfiles()
    {
        return $this->createQueryBuilder('pro')
            ->andWhere('pro.isPublic = :val')
            ->setParameter('val', true)
            ->getQuery()
            ->getResult()
        ;
    }
    
}

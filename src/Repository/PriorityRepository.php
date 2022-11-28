<?php

namespace App\Repository;

use App\Entity\Criterion;
use App\Entity\Priority;
use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method Priority|null find($id, $lockMode = null, $lockVersion = null)
 * @method Priority|null findOneBy(array $criteria, array $orderBy = null)
 * @method Priority[]    findAll()
 * @method Priority[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriorityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Priority::class);
    }

    
    public function findAllInProfile($profile)
    {
        return $this->createQueryBuilder('pri')
            ->innerJoin('pri.theme', 'the')
            ->addSelect('the')
            ->andWhere('pri.profile = :profile')
            ->setParameter('profile', $profile)
            ->orderBy('the.code', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }   

    /**
     * Used to get the fill list of criteria from a profile with the reference of each priority.
     * For detail of one country according to a profile.
     * @param  Profile $profile [description]
     * @return [type]          [description]
     */
    public function findAllInProfileWithAllCriterial(Profile $profile)
    {
        return $this->createQueryBuilder('pri')
            ->innerJoin('pri.theme', 'the')
            ->addSelect('the')
            ->innerJoin('the.criteria', 'cri')
            ->addSelect('cri')
            ->andWhere('pri.profile = :profile')
            ->setParameter('profile', $profile)
            ->orderBy('the.code', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    
    public function findOneWithCriteria($id): ?Priority
    {
        return $this->createQueryBuilder('pri')
            ->andWhere('pri.id = :id')
            ->setParameter('id', $id)
            ->join('pri.theme', 'the')
            ->addSelect('the')
            ->join('the.criteria', 'cri')
            ->addSelect('cri')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
    /**
     * used to load priority in the weighting based on the profile and the criterion
     * @param  [type] $criterion [description]
     * @param  [type] $profile   [description]
     * @return [type]            [description]
     */
    public function findOneFromCriterionAndProfile(Criterion $criterion, Profile $profile): ?Priority
    {
        return $this->createQueryBuilder('pri')
            ->join('pri.profile', 'profile')
            ->addSelect('profile')
            ->join('pri.theme', 'theme')
            ->addSelect('theme')
            ->join('theme.criteria', 'criterion')
            ->addSelect('criterion')
            ->andWhere('criterion.id = :criterion_id')
            ->setParameter('criterion_id', $criterion->getId())
            ->andWhere('profile.id = :profile_id')
            ->setParameter('profile_id', $profile->getId())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * For test of mapping from Native SQL request in Doctrine
     * @param  Profile $profile [description]
     * @return Priority[]           [description]
     */
    public function findAllInProfileBySQL(Profile $profile)
    {
        $entityManager = $this->getEntityManager();

        $sql = "SELECT id, profile_id, theme_id, value, weightings_sum ".
                "FROM priority prio WHERE prio.profile_id = :profile_param";

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Priority', 'prio');

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('profile_param', $profile->getId());

        return $query->getResult();
    }
    
}

<?php

namespace App\Repository;

use App\Entity\Rating;
use App\Entity\Criterion;
use App\Entity\Country;
use App\Entity\Weighting;
use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    /**
     * @return Rating[] Returns an array of Rating objects
     */
    
    public function findByCriterionWithCountry(Criterion $criterion)
    {
        return $this->createQueryBuilder('r')

            ->leftJoin('r.country', 'c')
            ->addSelect('c')    
                    
            ->andWhere('r.criterion = :cri')
            ->setParameter('cri', $criterion)
            ->orderBy('c.name', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return Rating[] Returns an array of Rating objects
     */
    
    public function findByCountryWithCriterion(Country $country)
    {
        return $this->createQueryBuilder('r')

            ->leftJoin('r.criterion', 'cri')
            ->addSelect('cri')    
            
            ->leftJoin('cri.weightings', 'weightings')
            ->addSelect('weightings')

            ->andWhere('r.country = :co')
            ->setParameter('co', $country)
            ->orderBy('cri.code', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }  

    /**
     * For the detail of one country
     * @param  Country $country 
     * @return Ratings           Filtered by $country
     */
    public function findAllByCountry(Country $country)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.country = :co')
            ->setParameter('co', $country)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Rating[] Returns an array of Rating objects
     */
    
    public function findByCountryForWeighting(Country $country, Profile $profile)
    {
        return $this->createQueryBuilder('r')

            ->leftJoin('r.criterion', 'cri')
            //->addSelect('cri')    
            
            ->join('cri.weightings', 'weightings')
            //->addSelect('weightings')
            ->join('weightings.priority', 'pri')
            ->andWhere('pri.profile = :profile')
            ->setParameter('profile', $profile)

            ->select('SUM(weightings.value) AS weightingValue')
            ->addSelect('SUM(r.ratingValue) AS ratingValue')
            ->addSelect('SUM(r.ratingValue*weightings.value) AS product')
            ->addSelect('SUM(r.ratingValue*weightings.value)/SUM(weightings.value) AS weightedAverage')

            ->andWhere('r.country = :co')
            ->setParameter('co', $country)
            ->orderBy('cri.code', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    public function getScoresForOneCountryByProfile(Country $country, Profile $profile)
    {
        return $this->createQueryBuilder('ra')
            ->andWhere('ra.country = :country')
            ->setParameter('country', $country)

            ->join('ra.criterion', 'cri')
            //->addSelect('cri')
            ->join('cri.weightings', 'wei')
            //->addSelect('wei')
            ->join('wei.priority', 'pri')
            //->addSelect('pri')
            ->andWhere('pri.profile = :profile')
            ->setParameter('profile', $profile)
            ->select('SUM(ra.ratingValue*wei.value*wei.positiveFlag - (100-ra.ratingValue)*wei.value*wei.negativeFlag) / SUM(ABS(wei.value)) AS score')
            ->addSelect('SUM(ABS(wei.value)) AS ratedWeightingsSum')
            ->addSelect('pri.id AS priorityId')
            ->groupBy('pri.id')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * This function is the copy of the calculation done in the CountryController in order to calculate score and transparency on priority level
     * It is not use for the moment. Called for dump check in profile show only.
     * @param  Profile $profile [description]
     * @return [type]           [description]
     */
    public function getScoredPrioritiesForOneCountryByProfile(Profile $profile)
    {
        $entityManager = $this->getEntityManager();

        $sqlFile = file(__DIR__.'/../NativeRequest/'.ucfirst(__FUNCTION__).'.sql');

        $sql = '';
        foreach ($sqlFile as $line) {
            $sql .= $line;
        }

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Priority', 'prio');

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('profile_param', $profile->getId());
        $query->setParameter('country_param', 598);

        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Rating
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

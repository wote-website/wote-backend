<?php

namespace App\Repository;

use App\Entity\Country;
use App\Entity\Profile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    /**
     * @return Country[] Returns an array of Country objects
     */
    
    public function findAllWithRating()
    {
        return $this->createQueryBuilder('co')
            ->leftJoin('co.ratings', 'ra')
            ->addSelect('ra')

            ->leftJoin('ra.criterion', 'cri')
            ->addSelect('cri')

            ->leftJoin('cri.weightings', 'wei')

            ->andWhere('co.name IN (:val)')
            ->setParameter('val', ['France', 'Belgium', 'Uruguay'])
            ->orderBy('co.name', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * Gives the ranking of all countries for one profile according to the weightings.
     * It presents also the transparency score that prensent how each country are registered in each criterion.
     * @param  Profile $profile
     * @return Array['countryName', 'transpScore', 'ratingScore']
     */
    public function findAllWithScore(Profile $profile)
    {
        return $this->createQueryBuilder('co')
            ->leftJoin('co.ratings', 'ra')
            ->leftJoin('ra.criterion', 'cri')
            ->leftJoin('cri.weightings', 'wei')
            ->leftJoin('wei.profile', 'pro')

            ->select('SUM(wei.value) AS countryWeightingsSum')

            ->groupBy('co.name')
            ->groupBy('co.id')
            ->addSelect('co.name AS countryName')
            ->addSelect('co.id AS countryId')

            ->addGroupBy('pro.weightingsSum')
            ->addSelect('pro.weightingsSum AS profileWeightingsSum')

            ->addSelect('SUM(wei.value)/pro.weightingsSum AS transpScore')


            ->addSelect('SUM(ra.ratingValue*wei.value)/SUM(wei.value) AS ratingScore')

            //->andWhere('co.name IN (:val)')
            //->setParameter('val', ['France', 'Belgium', 'Uruguay'])

            ->andWhere('wei.profile = :profile')
            ->setParameter('profile', $profile)

            //->orderBy('co.name', 'ASC')
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    public function getScoredCountriesForOneProfile(Profile $profile)
    {
        $entityManager = $this->getEntityManager();

        $sql = file_get_contents(__DIR__.'/../NativeRequest/'.ucfirst(__FUNCTION__).'.sql');

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Country', 'co');

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('profile_param', $profile->getId());

        return $query->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Country
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

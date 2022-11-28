<?php

namespace App\Repository;

use App\Entity\Country;
use App\Entity\Profile;
use App\Entity\Score;
use App\Repository\CountryRepository;
use App\Repository\CriterionRepository;
use App\Repository\ThemeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Score|null find($id, $lockMode = null, $lockVersion = null)
 * @method Score|null findOneBy(array $criteria, array $orderBy = null)
 * @method Score[]    findAll()
 * @method Score[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ScoreRepository extends ServiceEntityRepository
{
    
    private $countryRepository;
    private $themeRepository;
    private $criterionRepository;

    public function __construct(ManagerRegistry $registry, CountryRepository $countryRepository, ThemeRepository $themeRepository, CriterionRepository $criterionRepository)
    {
        parent::__construct($registry, Score::class);
        $this->countryRepository = $countryRepository;
        $this->themeRepository = $themeRepository;
        $this->criterionRepository = $criterionRepository;
    }


    /**
     * This working methods get scores separeted from countries.
     * The goal is to have only calculated fields but 2 points are blocking:
     * - doctrine mapping is not working without scores id and existing scores
     * - api_platform ressource generation is not working withou id
     * So I make here a manual mapping with the scalar result
     * And I persist even if there is no need
     * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
     * @param  Profile $profile From activeProfile in User
     * @return Scores[]           For all countries 
     */
    public function getScoresForAllCountriesForOneProfile(Profile $profile)
    {
        $entityManager = $this->getEntityManager();

        $sql = file_get_contents(__DIR__.'/../NativeRequest/'.ucfirst(__FUNCTION__).'.sql');

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Score', 'sco');

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('profile_param', $profile->getId());

        $scalarResults = $query->getScalarResult();
        $countries = $this->countryRepository->FindAll();
        $scores = [];

        foreach ($scalarResults as $scalarResult) {
            $score = new Score();
            foreach ($countries as $country) {
                if ($country->getId() == $scalarResult['sco_country_id']){
                    $score->setCountry($country);
                }
            }
            $score
                ->setValue($scalarResult['sco_value'])
                ->setCoverage($scalarResult['sco_coverage'])
                ->setProfile($profile)
                ->setCreationDate(new \DateTime);
            $scores []= $score;

            $entityManager->persist($score);
        }

        $entityManager->flush();

        return $scores;
    }


    public function getScoresForOneCountryForOneProfile(Country $country, Profile $profile)
    {
        $entityManager = $this->getEntityManager();

        $sql = file_get_contents(__DIR__.'/../NativeRequest/'.ucfirst(__FUNCTION__).'.sql');

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Score', 'sco');

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('profile_param', $profile->getId());
        $query->setParameter('country_param', $country->getId());

        $scalarResults = $query->getScalarResult();
        $scores = [];

        foreach ($scalarResults as $scalarResult) {
            $score = new Score();
            $score
                ->setCountry($country)
                ->setValue($scalarResult['sco_value'])
                ->setCoverage($scalarResult['sco_coverage'])
                ->setProfile($profile)
                ->setCreationDate(new \DateTime);
            $scores []= $score;

            $entityManager->persist($score);
        }

        $entityManager->flush();

        return $scores;
    }
    


    public function getScoresOfThemesForOneCountryForOneProfile(Country $country, Profile $profile)
    {
        $entityManager = $this->getEntityManager();

        $sql = file_get_contents(__DIR__.'/../NativeRequest/'.ucfirst(__FUNCTION__).'.sql');

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Score', 'sco');

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('profile_param', $profile->getId());
        $query->setParameter('country_param', $country->getId());

        $scalarResults = $query->getScalarResult();
        $themes = $this->themeRepository->FindAll();
        $scores = [];

        foreach ($scalarResults as $scalarResult) {
            $score = new Score();
            foreach ($themes as $theme) {
                if ($theme->getId() == $scalarResult['sco_theme_id']){
                    $score->setTheme($theme);
                }
            }
            $score
                ->setValue($scalarResult['sco_value'])
                ->setCoverage($scalarResult['sco_coverage'])
                ->setProfile($profile)
                ->setCountry($country)
                ->setCreationDate(new \DateTime);
            $scores []= $score;

            $entityManager->persist($score);
        }

        $entityManager->flush();

        return $scores;
    }


    public function getScoresOfCriteriaForOneCountryForOneProfile(Country $country, Profile $profile)
    {
        $entityManager = $this->getEntityManager();

        $sql = file_get_contents(__DIR__.'/../NativeRequest/'.ucfirst(__FUNCTION__).'.sql');

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('App\Entity\Score', 'sco');

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('profile_param', $profile->getId());
        $query->setParameter('country_param', $country->getId());

        $scalarResults = $query->getScalarResult();
        $criteria = $this->criterionRepository->FindAll();
        $scores = [];

        foreach ($criteria as $criterion) {
            $score = new Score();

            foreach ($scalarResults as $scalarResult) {
                if ($criterion->getId() == $scalarResult['sco_criterion_id']){
                    $score->setValue($scalarResult['sco_value']);
                    $score->setCoverage($scalarResult['sco_coverage']);
                }
            }
            $score
                ->setCriterion($criterion)
                ->setTheme($criterion->getTheme())
                ->setProfile($profile)
                ->setCountry($country)
                ->setCreationDate(new \DateTime);
            $scores []= $score;

            $entityManager->persist($score);
        }

        $entityManager->flush();

        return $scores;
    }



    // /**
    //  * @return Score[] Returns an array of Score objects
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
    public function findOneBySomeField($value): ?Score
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

<?php

namespace App\Repository;

use App\Entity\ProductScale;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMappingBuilder;

/**
 * @method ProductScale|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductScale|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductScale[]    findAll()
 * @method ProductScale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductScaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductScale::class);
    }



    public function getActiveScaleForOneUser(User $user)
    {
        $entityManager = $this->getEntityManager();

        $sql = file_get_contents(__DIR__.'/../NativeRequest/'.ucfirst(__FUNCTION__).'.sql');

        $rsm = new ResultSetMappingBuilder($entityManager);
        $rsm->addRootEntityFromClassMetadata('App\Entity\ProductScale', 'sca');

        $query = $entityManager->createNativeQuery($sql, $rsm);
        $query->setParameter('user_param', $user->getId());

        return $query->getResult();
    }

    // /**
    //  * @return ProductScale[] Returns an array of ProductScale objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductScale
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

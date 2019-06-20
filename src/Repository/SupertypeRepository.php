<?php

namespace App\Repository;

use App\Entity\Supertype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Supertype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supertype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supertype[]    findAll()
 * @method Supertype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupertypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Supertype::class);
    }

    // /**
    //  * @return Supertype[] Returns an array of Supertype objects
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
    public function findOneBySomeField($value): ?Supertype
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

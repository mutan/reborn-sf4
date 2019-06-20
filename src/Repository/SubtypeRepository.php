<?php

namespace App\Repository;

use App\Entity\Subtype;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Subtype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subtype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subtype[]    findAll()
 * @method Subtype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubtypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subtype::class);
    }

    // /**
    //  * @return Subtype[] Returns an array of Subtype objects
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
    public function findOneBySomeField($value): ?Subtype
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

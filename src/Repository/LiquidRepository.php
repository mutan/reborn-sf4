<?php

namespace App\Repository;

use App\Entity\Liquid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Liquid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liquid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liquid[]    findAll()
 * @method Liquid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Liquid::class);
    }

    // /**
    //  * @return Liquid[] Returns an array of Liquid objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Liquid
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

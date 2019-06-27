<?php

namespace App\Repository;

use App\Entity\Liquid;
use App\Repository\Interfaces\CountableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Liquid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Liquid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Liquid[]    findAll()
 * @method Liquid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LiquidRepository extends ServiceEntityRepository implements CountableRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Liquid::class);
    }

    public function getCount(): int
    {
        return $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->getQuery()->getSingleScalarResult();
    }
}

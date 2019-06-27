<?php

namespace App\Repository;

use App\Entity\Supertype;
use App\Repository\Interfaces\CountableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Supertype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Supertype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Supertype[]    findAll()
 * @method Supertype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupertypeRepository extends ServiceEntityRepository implements CountableRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Supertype::class);
    }

    public function getCount(): int
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()->getSingleScalarResult();
    }
}

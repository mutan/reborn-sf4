<?php

namespace App\Repository;

use App\Entity\Subtype;
use App\Repository\Interfaces\CountableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Subtype|null find($id, $lockMode = null, $lockVersion = null)
 * @method Subtype|null findOneBy(array $criteria, array $orderBy = null)
 * @method Subtype[]    findAll()
 * @method Subtype[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SubtypeRepository extends ServiceEntityRepository implements CountableRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Subtype::class);
    }

    public function getCount(): int
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()->getSingleScalarResult();
    }
}

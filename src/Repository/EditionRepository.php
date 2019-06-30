<?php

namespace App\Repository;

use App\Entity\Edition;
use App\Repository\Interfaces\CountableRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Edition|null find($id, $lockMode = null, $lockVersion = null)
 * @method Edition|null findOneBy(array $criteria, array $orderBy = null)
 * @method Edition[]    findAll()
 * @method Edition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EditionRepository extends ServiceEntityRepository implements CountableRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Edition::class);
    }

    public function findAllOrderedByNumber()
    {
        return $this->findBy([], ['number' => 'ASC']);
    }

    public function getCount(): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->getQuery()->getSingleScalarResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\Packaging;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Packaging>
 */
final class PackagingRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry)
    {
        parent::__construct($registry, Packaging::class);
    }

    public function findPackagingByDimensions(float $x, float $y, float $z, float $weight) : ?Packaging
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.width >= :x')
            ->setParameter('x', $x)
            ->andWhere('p.height >= :y')
            ->setParameter('y', $y)
            ->andWhere('p.length >= :z')
            ->setParameter('z', $z)
            ->andWhere('p.maxWeight >= :weight')
            ->setParameter('weight', $weight)
            ->addOrderBy('p.volume', 'ASC')
            ->addOrderBy('p.maxWeight', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

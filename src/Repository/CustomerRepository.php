<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findAllWithOrdersCount(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.name', 'c.email', 'COUNT(o.id) AS ordersCount')
            ->leftJoin('c.orders', 'o')
            ->groupBy('c.id')
            ->orderBy('ordersCount', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function findTopBySpent(int $limit): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.name', 'SUM(b.price) AS spent')
            ->join('c.orders', 'o')
            ->join('o.books', 'b')
            ->groupBy('c.id')
            ->orderBy('spent', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }
}
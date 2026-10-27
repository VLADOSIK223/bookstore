<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function findAllWithTotals(): array
    {
        return $this->createQueryBuilder('o')
            ->select('o.id AS orderId', 'c.name', 'SUM(b.price) AS total')
            ->join('o.customer', 'c')
            ->join('o.books', 'b')
            ->groupBy('o.id')
            ->orderBy('o.id')
            ->getQuery()
            ->getArrayResult();
    }

    public function getAverageTotal(): float
    {
        $row = $this->createQueryBuilder('o')
            ->select('SUM(b.price) AS totalSum', 'COUNT(DISTINCT o.id) AS ordersCount')
            ->join('o.books', 'b')
            ->getQuery()
            ->getSingleResult();

        if ($row['ordersCount'] == 0) {
            return 0;
        }

        return $row['totalSum'] / $row['ordersCount'];
    }
}
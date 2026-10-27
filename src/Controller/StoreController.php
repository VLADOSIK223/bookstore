<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StoreController extends AbstractController
{
    #[Route('/customers', name: 'customers')]
    public function customers(CustomerRepository $customerRepository): Response
    {
        return $this->render('store/customers.html.twig', [
            'customers' => $customerRepository->findAllWithOrdersCount(),
        ]);
    }

    #[Route('/orders', name: 'orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        return $this->render('store/orders.html.twig', [
            'orders' => $orderRepository->findAllWithTotals(),
        ]);
    }

    #[Route('/customers/top', name: 'customers_top')]
    public function top(CustomerRepository $customerRepository): Response
    {
        return $this->render('store/top.html.twig', [
            'customers' => $customerRepository->findTopBySpent(3),
        ]);
    }

    #[Route('/orders/average', name: 'orders_average')]
    public function average(OrderRepository $orderRepository): Response
    {
        return $this->render('store/average.html.twig', [
            'average' => $orderRepository->getAverageTotal(),
        ]);
    }

    #[Route('/books/expensive', name: 'books_expensive')]
    public function expensive(BookRepository $bookRepository): Response
    {
        return $this->render('store/expensive.html.twig', [
            'book' => $bookRepository->findMostExpensive(),
        ]);
    }
}
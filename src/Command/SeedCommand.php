<?php

namespace App\Command;

use App\Entity\Book;
use App\Entity\Customer;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:seed', description: 'Заполнить базу тестовыми данными')]
class SeedCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $anna = new Customer('Анна Смирнова', 'anna@mail.ru');
        $ivan = new Customer('Иван Петров', 'ivan@mail.ru');
        $oleg = new Customer('Олег Иванов', 'oleg@mail.ru');
        $maria = new Customer('Мария Кузнецова', 'maria@mail.ru');
        $dmitry = new Customer('Дмитрий Соколов', 'dmitry@mail.ru');

        $war = new Book('Война и мир', 1200);
        $master = new Book('Мастер и Маргарита', 650);
        $crime = new Book('Преступление и наказание', 500);
        $clean = new Book('Чистый код', 2800);
        $prince = new Book('Маленький принц', 300);

        foreach ([$anna, $ivan, $oleg, $maria, $dmitry, $war, $master, $crime, $clean, $prince] as $item) {
            $this->em->persist($item);
        }

        $orders = [
            [$anna, [$war, $master]],
            [$anna, [$clean]],
            [$anna, [$prince, $crime]],
            [$ivan, [$master, $crime]],
            [$ivan, [$war]],
            [$oleg, [$prince]],
            [$maria, [$clean, $war, $master]],
        ];

        foreach ($orders as [$customer, $books]) {
            $order = new Order($customer);
            foreach ($books as $book) {
                $order->addBook($book);
            }
            $this->em->persist($order);
        }

        $this->em->flush();

        $output->writeln('Готово: тестовые данные добавлены');

        return Command::SUCCESS;
    }
}
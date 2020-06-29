<?php


namespace App\Service;


use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class BookManager implements BookManagerInterface
{
    private EntityManagerInterface $em;
    private BookRepository $repository;

    public function __construct(EntityManagerInterface $em, BookRepository $repository)
    {
        $this->em = $em;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getRepository(): ObjectRepository
    {
        return  $this->repository;
    }

    /**
     * @inheritDoc
     */
    public function store(Book $book): Book
    {

    }

    /**
     * @inheritDoc
     */
    public function get($id): ?Book
    {
        return $this->repository->find($id);
    }

    /**
     * @inheritDoc
     */
    public function update(Book $book): Book
    {
        // TODO: Implement update() method.
    }

    /**
     * @inheritDoc
     */
    public function delete(Book $book): Book
    {
        // TODO: Implement delete() method.
    }
}

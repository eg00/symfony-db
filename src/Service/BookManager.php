<?php


namespace App\Service;


use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\Exception\StorageException;
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
        return $this->repository;
    }

    /**
     * @inheritDoc
     */
    public function store(Book $book): Book
    {
        if ($book->getId() !== null) {
            throw new StorageException("Book {$book->getId()} exists");
        }
        $this->persistAndflush($book);
        $this->em->refresh($book);

        return $book;
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
        if ($book->getId() === null) {
            throw new StorageException("Book {$book->getId()} does not exists");
        }
        $this->persistAndflush($book);
        $this->em->refresh($book);

        return $book;
    }

    /**
     * @inheritDoc
     */
    public function delete(Book $book): Book
    {
        if ($book->getId() === null) {
            throw new StorageException("Book {$book->getId()} does not exists");
        }

        try {
            $this->em->remove($book);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage(), (int) $e->getCode(), $e);
        }

        return $book;
    }

    private function persistAndflush(Book $book): void
    {
        try {
            $this->em->persist($book);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}

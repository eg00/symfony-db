<?php


namespace App\Service;


use App\Entity\Book;
use Doctrine\Persistence\ObjectRepository;

interface BookManagerInterface
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository;

    /**
     * @param Book $book
     * @return Book
     */
    public function store(Book $book): Book;

    /**
     * @param $id
     * @return Book|null
     */
    public function get($id): ?Book;

    /**
     * @param Book $book
     * @return Book
     */
    public function update(Book $book): Book;

    /**
     * @param Book $book
     * @return Book
     */
    public function delete(Book $book): Book;
}

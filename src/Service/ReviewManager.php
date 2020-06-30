<?php

namespace App\Service;

use App\Entity\Reviews;
use App\Repository\ReviewsRepository;
use App\Service\Exception\StorageException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

class ReviewManager implements ReviewManagerInterface
{
    private EntityManagerInterface $em;
    private ReviewsRepository $repository;

    public function __construct(EntityManagerInterface $em, ReviewsRepository $repository)
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
    public function store(Reviews $review): Reviews
    {
        if ($review->getId() !== null) {
            throw new StorageException("Review {$review->getId()} exists");
        }
        $this->persistAndflush($review);
        $this->em->refresh($review);

        return $review;
    }

    /**
     * @inheritDoc
     */
    public function get($id): ?Reviews
    {
        return $this->repository->find($id);
    }

    /**
     * @inheritDoc
     */
    public function update(Reviews $review): Reviews
    {
        if ($review->getId() === null) {
            throw new StorageException("Review {$review->getId()} does not exists");
        }
        $this->persistAndflush($review);
        $this->em->refresh($review);

        return $review;
    }

    /**
     * @inheritDoc
     */
    public function delete(Reviews $review): Reviews
    {
        if ($review->getId() === null) {
            throw new StorageException("Review {$review->getId()} does not exists");
        }

        try {
            $this->em->remove($review);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage(), (int) $e->getCode(), $e);
        }

        return $review;
    }

    private function persistAndflush(Reviews $review): void
    {
        try {
            $this->em->persist($review);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new StorageException($e->getMessage(), (int) $e->getCode(), $e);
        }
    }
}

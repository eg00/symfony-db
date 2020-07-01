<?php

namespace App\Service;

use App\Entity\Reviews;
use Doctrine\Persistence\ObjectRepository;

interface ReviewManagerInterface
{
    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository;

    /**
     * @param Reviews $review
     *
     * @return Reviews
     */
    public function store(Reviews $review): Reviews;

    /**
     * @param $id
     *
     * @return Reviews|null
     */
    public function get($id): ?Reviews;

    /**
     * @param Reviews $review
     *
     * @return Reviews
     */
    public function update(Reviews $review): Reviews;

    /**
     * @param Reviews $review
     *
     * @return Reviews
     */
    public function delete(Reviews $review): Reviews;
}

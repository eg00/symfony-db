<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\Reviews;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\UniqueGenerator;

class BookFixtures extends Fixture
{
    protected const FINAL_COUNT = 10;

    /**
     * @var Generator|UniqueGenerator
     */
    private $faker;

    protected array $books = [];

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager): void
    {
        $this->createBooks($manager);

        foreach ($this->books as $book) {
            $this->createReviews($book, $manager);
        }


        $manager->flush();
    }

    protected function createBooks(ObjectManager $manager): void
    {
        for ($i = self::FINAL_COUNT; $i >= 0; $i--)  {
            $book = (new Book())
                ->setTitle($this->faker->sentence(\random_int(2, 6)))
                ->setAuthor($this->faker->name)
                ->setPublishedDate($this->faker->dateTimeBetween('-5 years', 'now'))
                ->setIsbn((int) $this->faker->isbn10);

            $manager->persist($book);
            $this->books[] = $book;
        }
        $manager->flush();
    }

    protected function createReviews(Book $book, ObjectManager $manager): void
    {
        $count = \random_int(0, 4);
        for ($i = $count; $i >= 0; $i--) {
            $review = (new Reviews())
                ->setRevieverName($this->faker->name)
                ->setContent($this->faker->realText)
                ->setRating(\random_int(1, 10))
                ->setPublishedDate($this->faker->dateTimeBetween($book->getPublishedDate(), 'now'));
            $book->addReview($review);
            $manager->persist($review);
        }
        $manager->flush();
    }
}

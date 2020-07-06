<?php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReviewsRepository::class)
 */
class Reviews
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $book_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"show"})
     */
    private $reviever_name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"show"})
     */
    private $content;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"show"})
     */
    private $rating;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"show"})
     */
    private $published_date;

    /**
     * @ORM\ManyToOne(targetEntity=Book::class, inversedBy="reviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $book;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?int
    {
        return $this->book_id;
    }

    public function setBookId(int $book_id): self
    {
        $this->book_id = $book_id;

        return $this;
    }

    public function getRevieverName(): ?string
    {
        return $this->reviever_name;
    }

    public function setRevieverName(string $reviever_name): self
    {
        $this->reviever_name = $reviever_name;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getPublishedDate(): ?\DateTimeInterface
    {
        return $this->published_date;
    }

    public function setPublishedDate(\DateTimeInterface $published_date): self
    {
        $this->published_date = $published_date;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): self
    {
        $this->book = $book;

        return $this;
    }
}

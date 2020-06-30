<?php

namespace App\Controller;

use App\Service\BookManagerInterface;
use App\Service\ReviewManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReviewController extends AbstractController
{
    private ReviewManagerInterface $reviewManager;
    private BookManagerInterface $bookManager;

    public function __construct(ReviewManagerInterface $reviewManager, BookManagerInterface $bookManager)
    {
        $this->reviewManager = $reviewManager;
        $this->bookManager = $bookManager;
    }

    /**
     * @Route("/book/show/{bookId<\d+>}/reviews", name="reviews")
     *
     * @param int $page
     *
     * @return Response
     */
    public function index(int $bookId): Response
    {
        $this->checkIfBookExists($bookId);

        return $this->render('review/index.html.twig', [
            'reviews' => $this->reviewManager->getRepository()->findByBookId($bookId),
            'book' => $this->bookManager->get($bookId),
        ]);
    }

    /**
     * @Route("/book/show/{bookId<\d+>}/review/{id<\d+>}", name="review_show")
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(int $bookId, int $id): Response
    {
        $this->checkIfBookExists($bookId);

        return $this->render('review/show.html.twig', [
            'review' => $this->reviewManager->get($id),
            'bookId' => $bookId
        ]);
    }

    private function checkIfBookExists($bookId)
    {
        if (!$this->bookManager->get($bookId)) {
            throw $this->createNotFoundException('The book does not exist');
        }
    }
}

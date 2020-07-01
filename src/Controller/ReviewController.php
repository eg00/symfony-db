<?php

namespace App\Controller;

use App\Entity\Reviews;
use App\Form\ReviewType;
use App\Service\BookManagerInterface;
use App\Service\ReviewManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     * @param int $bookId
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
     * @param int $bookId
     * @param int $id
     *
     * @return Response
     */
    public function show(int $bookId, int $id): Response
    {
        $this->checkIfBookExists($bookId);

        return $this->render('review/show.html.twig', [
            'review' => $this->reviewManager->get($id),
            'bookId' => $bookId,
        ]);
    }

    /**
     * @Route("/book/show/{bookId<\d+>}/review/create", name="review_create")
     *
     * @param int     $bookId
     * @param Request $request
     *
     * @return Response
     */
    public function create(int $bookId, Request $request): Response
    {
        $this->checkIfBookExists($bookId);

        $review = new Reviews();
        $form = $this->createForm(ReviewType::class, $review,
            ['attr' => ['bookId' => $bookId],
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();

            $this->reviewManager->store($review);

            return $this->redirectToRoute('book_show', ['id' => $bookId]);
        }

        return $this->render('review/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/book/show/{bookId<\d+>}/review/update/{id<\d+>}", name="review_update")
     *
     * @param int     $bookId
     * @param int     $id
     * @param Request $request
     *
     * @return Response
     */
    public function update(int $bookId, int $id, Request $request): Response
    {
        $this->checkIfBookExists($bookId);
        $review = $this->reviewManager->get($id);
        $form = $this->createForm(ReviewType::class, $review,
            ['attr' => ['bookId' => $bookId],
            ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $review = $form->getData();
            $this->reviewManager->update($review);

            return $this->redirectToRoute('book_show', ['id' => $bookId]);
        }

        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function checkIfBookExists($bookId): void
    {
        if (!$this->bookManager->get($bookId)) {
            throw $this->createNotFoundException('The book does not exist');
        }
    }
}

<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Service\BookManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private BookManagerInterface $bookManager;
    private LoggerInterface $logger;

    public function __construct(BookManagerInterface $bookManager, LoggerInterface $logger)
    {
        $this->bookManager = $bookManager;
        $this->logger = $logger;
    }

    /**
     * @Route("/book/{page<\d+>?1}", name="book")
     *
     * @param int $page
     *
     * @return Response
     */
    public function index(int $page = 1): Response
    {
        $limit = 10;
        $data = $this->bookManager->getRepository()->paginate($page, $limit);
        $pages = ceil($data->count() / $limit);

        return $this->render('book/index.html.twig', [
            'books' => $data,
            'page' => $page,
            'pages' => $pages,
        ]);
    }

    /**
     * @Route("/book/create", name="book_create")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $this->bookManager->store($book);

            return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/book/show/{id<\d+>}", name="book_show")
     *
     * @param int $id
     *
     * @return Response
     */
    public function show(int $id): Response
    {
        $this->logger->info("book ${id} requested");
        return $this->render('book/show.html.twig', ['book' => $this->bookManager->get($id)]);
    }

    /**
     * @Route("/book/update/{id<\d+>}", name="book_update")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function update(int $id, Request $request): Response
    {
        $book = $this->bookManager->get($id);
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $book = $form->getData();
            $this->bookManager->update($book);
            $this->logger->info("book ${id} updated");

            return $this->redirectToRoute('book_show', ['id' => $book->getId()]);
        }

        return $this->render('book/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

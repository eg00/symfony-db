<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private BookRepository $bookPrepository;

    public function __construct(BookRepository $bookPrepository)
    {
        $this->bookPrepository = $bookPrepository;
    }

    /**
     * @Route("/book", name="book")
     */
    public function index()
    {

        return $this->render('book/index.html.twig', [
            'books' => $this->bookPrepository->findAll(),
        ]);
    }
}

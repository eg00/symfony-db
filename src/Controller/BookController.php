<?php

namespace App\Controller;

use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private BookRepository $bookPrepository;

    public function __construct(BookRepository $bookPrepository)
    {
        $this->bookPrepository = $bookPrepository;
    }

    /**
     * @Route("/book/{page<\d+>?1}", name="book")
     * @param int $page
     * @return Response
     */
    public function index(int $page = 1): Response
    {
        $limit = 10;
        $data = $this->bookPrepository->paginate($page, $limit);
        $pages = ceil($data->count() / $limit);

        return $this->render('book/index.html.twig', [
            'books' => $data,
            'page' => $page,
            'pages' => $pages,
        ]);
    }
}

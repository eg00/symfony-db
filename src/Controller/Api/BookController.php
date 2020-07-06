<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Service\BookManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class BookController extends AbstractController
{
    private static $headers = [
        'Content-Type' => 'application/json; charset=utf-8',
    ];
    private BookManagerInterface $bookManager;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(BookManagerInterface $bookManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->bookManager = $bookManager;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Route("/books/{page<\d+>}", name="api_book_index")
     *
     * @param int $page
     *
     * @return Response
     */
    public function index(int $page = 1): Response
    {
        $limit = 10;
        $data = $this->bookManager->getRepository()->paginate($page, $limit);

        $serializedData = $this->serializer->serialize($data, 'json', [
            'groups' => 'index',
        ]);

        return new Response($serializedData, Response::HTTP_OK, self::$headers);
    }

    /**
     * @Route("/book/{id<\d+>}", name="api_book_show")
     *
     * @param $id
     *
     * @return Response
     */
    public function show($id): Response
    {
        $book = $this->bookManager->get($id);
        if ($book === null) {
            return new Response($book, Response::HTTP_NOT_FOUND, self::$headers);
        }
        $serializedData = $this->serializer->serialize($book, 'json', ['groups' => 'show']);

        return new Response($serializedData, Response::HTTP_OK, self::$headers);
    }

    /**
     * @Route("/book/", name="api_book_validate", methods={"POST"})
     *
     * @param $id
     *
     * @return Response
     */
    public function validate(Request $request)
    {
        if ($request->headers->get('Content-Type') !== 'application/json') {
            return new Response(null, Response::HTTP_BAD_REQUEST, self::$headers);
        }
        $book = $this->serializer->deserialize($request->getContent(), Book::class, 'json');
        $errors = $this->validator->validate($book);
        if ($errors->count()) {
            return new Response((string) $errors, Response::HTTP_BAD_REQUEST, self::$headers);
        }
        $serializedData = $this->serializer->serialize($book, 'json', ['groups' => 'show']);

        return new Response($serializedData, Response::HTTP_OK, self::$headers);
    }
}

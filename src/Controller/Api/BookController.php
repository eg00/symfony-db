<?php

namespace App\Controller\Api;

use App\Entity\Book;
use App\Service\BookManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
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
     * @Route("/books/{page<\d+>}", name="api_book_index", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     description="Returns list of books",
     * )
     * @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     type="integer",
     *     description="The field used to show page"
     * )
     * @SWG\Tag(name="books")
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
     * @Route("/book/{id<\d+>}", name="api_book_show", methods={"GET"})
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Book::class),
     *     description="Returns the book by id",
     * )
     * @SWG\Response(
     *         response="400",
     *         description="Returned on a missing request parameter"
     *     ),
     * @SWG\Response(
     *         response="404",
     *         description="Returned if model not found"
     *     ),
     * @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     type="integer",
     *     description="The id of book"
     * )
     * @SWG\Tag(name="book")
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
     * @SWG\Response(
     *     response=200,
     *     @Model(type=Book::class, groups={"index"}),
     *     description="Validate json",
     * )
     * @SWG\Response(
     *         response="400",
     *         description="Returned on a missing request parameter"
     *     ),
     * @SWG\Parameter(
     *       name="body",
     *       in="body",
     *       description="json book object",
     *       parameter="body",
     *      @SWG\Schema(
     *        type="object",
     *        @SWG\Property(
     *             type="string",
     *             property="title",
     *             type="string",
     *             example="Book title",
     *           ),
     *            @SWG\Property(
     *             type="string",
     *             property="author",
     *             type="string",
     *             example="John Doe",
     *           ),
     *            @SWG\Property(
     *             type="string",
     *             property="published_date",
     *             type="datetime",
     *             example="2019-01-04T00:00:00+00:00",
     *           ),
     *      )
     * ),
     * @SWG\Tag(name="book")
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

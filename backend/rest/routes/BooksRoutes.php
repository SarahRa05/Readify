<?php

use OpenApi\Annotations as OA;

Flight::group('/books', function () {

    /**
     * @OA\Get(
     *     path="/books",
     *     tags={"books"},
     *     summary="Get all books",
     *     description="Returns a list of all books in the library.",
     *     @OA\Response(
     *         response=200,
     *         description="Array of books"
     *     )
     * )
     */
    Flight::route('GET /', function () {
        // returns: ['success' => true, 'data' => [...]]
        Flight::json(Flight::books_service()->getBooks());
    });

    /**
     * @OA\Get(
     *     path="/books/{id}",
     *     tags={"books"},
     *     summary="Get a single book by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Book object"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Book not found"
     *     )
     * )
     */
    Flight::route('GET /@id', function ($id) {
        Flight::json(Flight::books_service()->getBook($id));
    });

    /**
     * @OA\Post(
     *     path="/books",
     *     tags={"books"},
     *     summary="Create a new book",
     *     description="Adds a new book to the database.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title","author","publication_year","available_copies"},
     *             @OA\Property(property="isbn", type="string", example="9780131103627"),
     *             @OA\Property(property="title", type="string", example="The C Programming Language"),
     *             @OA\Property(property="author", type="string", example="Kernighan & Ritchie"),
     *             @OA\Property(property="publication_year", type="integer", example=1988),
     *             @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="available_copies", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Created book (with generated id)"
     *     )
     * )
     */
    Flight::route('POST /', function () {
        $data = Flight::request()->data->getData();
        Flight::json(Flight::books_service()->addBook($data));
    });

    /**
     * @OA\Put(
     *     path="/books/{id}",
     *     tags={"books"},
     *     summary="Update an existing book",
     *     description="Replaces all editable fields of a book.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="isbn", type="string", example="9780131103627"),
     *             @OA\Property(property="title", type="string", example="The C Programming Language - Updated"),
     *             @OA\Property(property="author", type="string", example="Kernighan & Ritchie"),
     *             @OA\Property(property="publication_year", type="integer", example=1988),
     *             @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="available_copies", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated book"
     *     )
     * )
     */
    Flight::route('PUT /@id', function ($id) {
        $data = Flight::request()->data->getData();
        // id first, data second – your service handles it
        Flight::json(Flight::books_service()->updateBook($id, $data));
    });

    /**
     * @OA\Delete(
     *     path="/books/{id}",
     *     tags={"books"},
     *     summary="Delete a book",
     *     description="Deletes a book by ID.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Delete result"
     *     )
     * )
     */
    Flight::route('DELETE /@id', function ($id) {
        Flight::json(Flight::books_service()->deleteBook($id));
    });
});




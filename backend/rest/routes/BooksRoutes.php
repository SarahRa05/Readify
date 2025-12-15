<?php

use OpenApi\Annotations as OA;

Flight::group('/books', function () {

    /**
     * @OA\Get(
     *     path="/books",
     *     tags={"books"},
     *     summary="Get all books",
     *     description="Accessible to any authenticated user (member/admin).",
     *     @OA\Response(
     *         response=200,
     *         description="Array of books"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    Flight::route('GET /', function () {
        // ✅ No authorization check here.
        // Global middleware already enforces authentication.
        $res = Flight::books_service()->getBooks();
        if (isset($res['success']) && $res['success']) {
            Flight::json($res['data']);
        } else {
            Flight::halt(500, isset($res['error']) ? $res['error'] : 'Server error');
        }
    });

    /**
     * @OA\Get(
     *     path="/books/{id}",
     *     tags={"books"},
     *     summary="Get a single book by ID",
     *     description="Accessible to any authenticated user (member/admin).",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Book object"),
     *     @OA\Response(response=404, description="Book not found"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('GET /@id', function ($id) {
        // ✅ No authorization check here.
        $res = Flight::books_service()->getBook($id);
        if ($res['success']) {
            Flight::json($res['data']);
        } else {
            Flight::halt(404, $res['error']);
        }
    });

    /**
     * @OA\Post(
     *     path="/books",
     *     tags={"books"},
     *     summary="Create a new book (admin only)",
     *     description="Admins can add new books.",
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
     *     @OA\Response(response=200, description="Created book"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('POST /', function () {
        // ✅ AUTHORIZATION (admin only)
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $data = Flight::request()->data->getData();
        $res  = Flight::books_service()->addBook($data);

        if ($res['success']) {
            Flight::json($res['data']);
        } else {
            Flight::halt(500, $res['error']);
        }
    });

    /**
     * @OA\Put(
     *     path="/books/{id}",
     *     tags={"books"},
     *     summary="Update an existing book (admin only)",
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
     *             @OA\Property(property="title", type="string", example="Updated title"),
     *             @OA\Property(property="author", type="string", example="Updated author"),
     *             @OA\Property(property="publication_year", type="integer", example=1988),
     *             @OA\Property(property="category_id", type="integer", nullable=true, example=1),
     *             @OA\Property(property="available_copies", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated book"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('PUT /@id', function ($id) {
        // ✅ AUTHORIZATION (admin only)
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $data = Flight::request()->data->getData();
        $res  = Flight::books_service()->updateBook($id, $data);

        if ($res['success']) {
            Flight::json($res['data']);
        } else {
            Flight::halt(500, $res['error']);
        }
    });

    /**
     * @OA\Delete(
     *     path="/books/{id}",
     *     tags={"books"},
     *     summary="Delete a book (admin only)",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Book ID",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(response=200, description="Delete result"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    Flight::route('DELETE /@id', function ($id) {
        // ✅ AUTHORIZATION (admin only)
        Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

        $res = Flight::books_service()->deleteBook($id);

        if ($res['success']) {
            Flight::json($res['data']);
        } else {
            Flight::halt(500, 'Delete failed.');
        }
    });

});

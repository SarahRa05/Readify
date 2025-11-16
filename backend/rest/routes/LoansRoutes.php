<?php

use OpenApi\Annotations as OA;


/**
 * @OA\Tag(
 *   name="loans",
 *   description="Loan management"
 * )
 */

/**
 * @OA\Get(
 *     path="/loans",
 *     tags={"loans"},
 *     summary="Get all loans",
 *     @OA\Response(
 *         response=200,
 *         description="Array of loans"
 *     )
 * )
 */
Flight::route('GET /loans', function () {
    $res = Flight::loans_service()->getLoans();
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Get(
 *     path="/loans/{id}",
 *     tags={"loans"},
 *     summary="Get loan by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Loan object"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Loan not found"
 *     )
 * )
 */
Flight::route('GET /loans/@id', function ($id) {
    $res = Flight::loans_service()->getLoan($id);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(404, $res['error']);
    }
});

/**
 * @OA\Post(
 *     path="/loans",
 *     tags={"loans"},
 *     summary="Create loan",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","book_id","borrow_date","due_date"},
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="book_id", type="integer", example=1),
 *             @OA\Property(property="borrow_date", type="string", format="date", example="2025-11-15"),
 *             @OA\Property(property="due_date", type="string", format="date", example="2025-11-30"),
 *             @OA\Property(property="return_date", type="string", format="date", nullable=true),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Created loan"
 *     )
 * )
 */
Flight::route('POST /loans', function () {
    $data = Flight::request()->data->getData();
    $res = Flight::loans_service()->addLoan($data);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Put(
 *     path="/loans/{id}",
 *     tags={"loans"},
 *     summary="Update loan",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="borrow_date", type="string", format="date"),
 *             @OA\Property(property="due_date", type="string", format="date"),
 *             @OA\Property(property="return_date", type="string", format="date", nullable=true),
 *             @OA\Property(property="status", type="string", example="returned")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Updated loan"
 *     )
 * )
 */
Flight::route('PUT /loans/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $res = Flight::loans_service()->updateLoan($id, $data);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Delete(
 *     path="/loans/{id}",
 *     tags={"loans"},
 *     summary="Delete loan",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Delete result"
 *     )
 * )
 */
Flight::route('DELETE /loans/@id', function ($id) {
    $res = Flight::loans_service()->deleteLoan($id);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, 'Delete failed.');
    }
});

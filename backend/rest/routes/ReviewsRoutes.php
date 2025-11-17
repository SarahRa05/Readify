<?php

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *   name="reviews",
 *   description="Book reviews"
 * )
 */

/**
 * @OA\Get(
 *     path="/reviews",
 *     tags={"reviews"},
 *     summary="Get all reviews",
 *     @OA\Response(
 *         response=200,
 *         description="Array of reviews"
 *     )
 * )
 */
Flight::route('GET /reviews', function () {
    $res = Flight::reviews_service()->getReviews();
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Get(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Get review by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Review object"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Review not found"
 *     )
 * )
 */
Flight::route('GET /reviews/@id', function ($id) {
    $res = Flight::reviews_service()->getReview($id);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(404, $res['error']);
    }
});

/**
 * @OA\Post(
 *     path="/reviews",
 *     tags={"reviews"},
 *     summary="Create review",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"user_id","book_id","rating"},
 *             @OA\Property(property="user_id", type="integer", example=6),
 *             @OA\Property(property="book_id", type="integer", example=1),
 *             @OA\Property(property="rating", type="integer", example=5),
 *             @OA\Property(property="review_text", type="string", example="Amazing book!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Created review"
 *     )
 * )
 */
Flight::route('POST /reviews', function () {
    $data = Flight::request()->data->getData();
    $res = Flight::reviews_service()->addReview($data);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Put(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Update review",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="rating", type="integer", example=4),
 *             @OA\Property(property="review_text", type="string", example="Updated review text")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Updated review"
 *     )
 * )
 */
Flight::route('PUT /reviews/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $res = Flight::reviews_service()->updateReview($id, $data);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Delete(
 *     path="/reviews/{id}",
 *     tags={"reviews"},
 *     summary="Delete review",
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
Flight::route('DELETE /reviews/@id', function ($id) {
    $res = Flight::reviews_service()->deleteReview($id);
    if ($res['success']) {
        Flight::json($res);
    } else {
        Flight::halt(500, 'Delete failed.');
    }
});

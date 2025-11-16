<?php

use OpenApi\Annotations as OA;


/**
 * @OA\Get(
 *      path="/categories",
 *      tags={"categories"},
 *      summary="Get all categories",
 *      @OA\Response(
 *           response=200,
 *           description="Array of all categories"
 *      )
 * )
 */
Flight::route('GET /categories', function(){
    $res = Flight::categories_service()->getCategories();
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Get category by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Category ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category object"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found"
 *     )
 * )
 */
Flight::route('GET /categories/@id', function($id){
    $res = Flight::categories_service()->getCategory($id);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(404, $res['error']);
    }
});

/**
 * @OA\Post(
 *     path="/categories",
 *     tags={"categories"},
 *     summary="Create a new category",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"category_name"},
 *             @OA\Property(property="category_name", type="string", example="Fiction"),
 *             @OA\Property(property="description", type="string", example="Fictional books")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New category created"
 *     )
 * )
 */
Flight::route('POST /categories', function(){
    $data = Flight::request()->data->getData();
    $res = Flight::categories_service()->createCategory($data);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Put(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Update category by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Category ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="category_name", type="string", example="Updated Fiction"),
 *             @OA\Property(property="description", type="string", example="Updated description")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Category updated"
 *     )
 * )
 */
Flight::route('PUT /categories/@id', function($id){
    $data = Flight::request()->data->getData();
    $res = Flight::categories_service()->updateCategory($id, $data);
    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Delete(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Delete category by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="Category ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Delete result"
 *     )
 * )
 */
Flight::route('DELETE /categories/@id', function($id){
    $res = Flight::categories_service()->deleteCategory($id);
    if ($res['success']) {
        Flight::json($res);
    } else {
        Flight::halt(500, 'Delete failed.');
    }
});

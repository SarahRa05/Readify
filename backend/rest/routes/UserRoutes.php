<?php

use OpenApi\Annotations as OA;


/**
 * @OA\Get(
 *      path="/users",
 *      tags={"users"},
 *      summary="Get all users",
 *      @OA\Response(
 *           response=200,
 *           description="Array of all users (password_hash omitted)"
 *      )
 * )
 */
Flight::route('GET /users', function () {
    $res = Flight::users_service()->getUsers();

    if ($res['success']) {
        // Return just the data, like in RestaurantRoutes
        Flight::json($res['data']);
    } else {
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Get(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Get user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User object without password_hash"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('GET /users/@id', function ($id) {
    $res = Flight::users_service()->getUser($id);

    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        Flight::halt(404, $res['error']);
    }
});

/**
 * @OA\Post(
 *     path="/users",
 *     tags={"users"},
 *     summary="Create (register) a user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"full_name","email","password"},
 *             @OA\Property(property="full_name", type="string", example="Ana Example"),
 *             @OA\Property(property="email", type="string", example="ana@example.com"),
 *             @OA\Property(property="password", type="string", example="secret123"),
 *             @OA\Property(property="role", type="string", example="member")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New user created (password_hash omitted)"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Validation or duplicate email error"
 *     )
 * )
 */
Flight::route('POST /users', function () {
    $data = Flight::request()->data->getData();
    $res  = Flight::users_service()->createUser($data);

    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        // Same style as professor's auth routes (halt with 500 on error)
        Flight::halt(500, $res['error']);
    }
});

/**
 * @OA\Put(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Update user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="full_name", type="string", example="Ana Updated"),
 *             @OA\Property(property="email", type="string", example="ana2@example.com"),
 *             @OA\Property(property="password", type="string", example="newpass"),
 *             @OA\Property(property="role", type="string", example="admin")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated (password_hash omitted)"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Validation or duplicate email error"
 *     )
 * )
 */
Flight::route('PUT /users/@id', function ($id) {
    $data = Flight::request()->data->getData();
    $res  = Flight::users_service()->updateUser($id, $data);

    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        // If service says "User not found", we return 404, otherwise 500
        if ($res['error'] === 'User not found.') {
            Flight::halt(404, $res['error']);
        } else {
            Flight::halt(500, $res['error']);
        }
    }
});

/**
 * @OA\Delete(
 *     path="/users/{id}",
 *     tags={"users"},
 *     summary="Delete user by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="User ID",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="User not found"
 *     )
 * )
 */
Flight::route('DELETE /users/@id', function ($id) {
    $res = Flight::users_service()->deleteUser($id);

    if ($res['success']) {
        Flight::json($res['data']);
    } else {
        if ($res['error'] === 'User not found.') {
            Flight::halt(404, $res['error']);
        } else {
            Flight::halt(500, 'Delete failed.');
        }
    }
});

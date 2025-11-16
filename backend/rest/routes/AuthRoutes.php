<?php

use OpenApi\Annotations as OA;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * Authentication routes: register and login
 * Professor-style: Flight::group('/auth', function() { ... });
 */

Flight::group('/auth', function() {

    /**
     * @OA\Post(
     *     path="/auth/register",
     *     summary="Register new user.",
     *     description="Add a new user to the database.",
     *     tags={"auth"},
     *     @OA\RequestBody(
     *         description="User registration data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"full_name", "email", "password"},
     *                 @OA\Property(
     *                     property="full_name",
     *                     type="string",
     *                     example="Sarah Radonja",
     *                     description="User full name"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="sarah@example.com",
     *                     description="User email"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     example="myStrongPassword123",
     *                     description="Plain text password"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     example="member",
     *                     description="User role (default member)"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User has been added."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Validation or server error."
     *     )
     * )
     */
    Flight::route('POST /register', function () {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->register($data);
    
        if ($response['success']) {
            Flight::json([
                'message' => 'User registered successfully',
                'data'    => $response['data']
            ]);
        } else {
            Flight::halt(500, $response['error']);
        }
    });

    /**
     * @OA\Post(
     *      path="/auth/login",
     *      tags={"auth"},
     *      summary="Login to system using email and password",
     *      @OA\Response(
     *           response=200,
     *           description="User data and JWT token"
     *      ),
     *      @OA\RequestBody(
     *          description="Credentials",
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", example="sarah@example.com", description="User email address"),
     *              @OA\Property(property="password", type="string", example="myStrongPassword123", description="User password")
     *          )
     *      )
     * )
     */
    Flight::route('POST /login', function() {
        $data = Flight::request()->data->getData();

        $response = Flight::auth_service()->login($data);
    
        if ($response['success']) {
            Flight::json([
                'message' => 'User logged in successfully',
                'data'    => $response['data']
            ]);
        } else {
            // professor-style: 500 on error
            Flight::halt(500, $response['error']);
        }
    });
});

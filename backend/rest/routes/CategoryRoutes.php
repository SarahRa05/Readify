<?php
require_once __DIR__ . '/../data/Roles.php';

/*** 
 * @OA\Get(
 *   path="/categories",
 *   tags={"categories"},
 *   summary="Get all categories",
 *   security={{"bearerAuth":{}}},
 *   @OA\Response(response=200, description="List of categories")
 * )
 */
Flight::route('GET /categories', function () {
  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::MEMBER]);
  Flight::json(Flight::categories_service()->get_all());
});

/*** 
 * @OA\Get(
 *   path="/categories/{id}",
 *   tags={"categories"},
 *   summary="Get category by ID",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Single category")
 * )
 */
Flight::route('GET /categories/@id', function ($id) {
  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::MEMBER]);
  Flight::json(Flight::categories_service()->get_by_id($id));
});

/*** 
 * @OA\Post(
 *   path="/categories",
 *   tags={"categories"},
 *   summary="Create new category (ADMIN only)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Response(response=200, description="Category created")
 * )
 */
Flight::route('POST /categories', function () {
  Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
  $data = Flight::request()->data->getData();
  Flight::json(Flight::categories_service()->add($data));
});

/*** 
 * @OA\Put(
 *   path="/categories/{id}",
 *   tags={"categories"},
 *   summary="Update category (ADMIN only)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Category updated")
 * )
 */
Flight::route('PUT /categories/@id', function ($id) {
  Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
  $data = Flight::request()->data->getData();
  Flight::json(Flight::categories_service()->update($id, $data));
});

/*** 
 * @OA\Delete(
 *   path="/categories/{id}",
 *   tags={"categories"},
 *   summary="Delete category (ADMIN only)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Category deleted")
 * )
 */
Flight::route('DELETE /categories/@id', function ($id) {
  Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
  Flight::json(Flight::categories_service()->delete($id));
});

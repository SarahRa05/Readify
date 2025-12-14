<?php
require_once __DIR__ . '/../data/Roles.php';

/*** 
 * @OA\Get(
 *   path="/reviews",
 *   tags={"reviews"},
 *   summary="Get all reviews",
 *   security={{"bearerAuth":{}}},
 *   @OA\Response(response=200, description="List of reviews")
 * )
 */
Flight::route('GET /reviews', function () {
  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::MEMBER]);
  Flight::json(Flight::reviews_service()->get_all());
});

/*** 
 * @OA\Get(
 *   path="/reviews/{id}",
 *   tags={"reviews"},
 *   summary="Get review by ID",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Single review")
 * )
 */
Flight::route('GET /reviews/@id', function ($id) {
  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::MEMBER]);
  Flight::json(Flight::reviews_service()->get_by_id($id));
});

/*** 
 * @OA\Post(
 *   path="/reviews",
 *   tags={"reviews"},
 *   summary="Create review (MEMBER or ADMIN)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Response(response=200, description="Review created")
 * )
 */
Flight::route('POST /reviews', function () {
  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::MEMBER]);

  $data = Flight::request()->data->getData();
  $user = Flight::get('user');

  // force logged-in user
  $data['user_id'] = $user->user_id;

  Flight::json(Flight::reviews_service()->add($data));
});

/*** 
 * @OA\Put(
 *   path="/reviews/{id}",
 *   tags={"reviews"},
 *   summary="Update review (ADMIN only)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Review updated")
 * )
 */
Flight::route('PUT /reviews/@id', function ($id) {
  Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
  $data = Flight::request()->data->getData();
  Flight::json(Flight::reviews_service()->update($id, $data));
});

/*** 
 * @OA\Delete(
 *   path="/reviews/{id}",
 *   tags={"reviews"},
 *   summary="Delete review (ADMIN only)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Review deleted")
 * )
 */
Flight::route('DELETE /reviews/@id', function ($id) {
  Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
  Flight::json(Flight::reviews_service()->delete($id));
});

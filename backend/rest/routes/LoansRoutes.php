<?php
require_once __DIR__ . '/../data/Roles.php';

/*** 
 * @OA\Get(
 *   path="/loans",
 *   tags={"loans"},
 *   summary="Get loans (ADMIN = all, MEMBER = own)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Response(response=200, description="List of loans")
 * )
 */
Flight::route('GET /loans', function () {
  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::MEMBER]);

  $user = Flight::get('user');

  if ($user->role === Roles::ADMIN) {
    Flight::json(Flight::loans_service()->getLoans());
  } else {
    Flight::json(Flight::loans_service()->get_by_user_id($user->user_id));
  }
  
});

/*** 
 * @OA\Get(
 *   path="/loans/{id}",
 *   tags={"loans"},
 *   summary="Get loan by ID",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Single loan")
 * )
 */
Flight::route('GET /loans/@id', function ($id) {
  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::MEMBER]);
  Flight::json(Flight::loans_service()->get_by_id($id));
});

/*** 
 * @OA\Post(
 *   path="/loans",
 *   tags={"loans"},
 *   summary="Create loan (MEMBER or ADMIN)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Response(response=200, description="Loan created")
 * )
 */
Flight::route('POST /loans', function () {
  Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::MEMBER]);

  $data = Flight::request()->data->getData();
  $user = Flight::get('user');

  $data['user_id'] = $user->user_id;

  Flight::json(Flight::loans_service()->add($data));
});

/*** 
 * @OA\Put(
 *   path="/loans/{id}",
 *   tags={"loans"},
 *   summary="Update loan (ADMIN only)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Loan updated")
 * )
 */
Flight::route('PUT /loans/@id', function ($id) {
  Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
  $data = Flight::request()->data->getData();
  Flight::json(Flight::loans_service()->update($id, $data));
});

/*** 
 * @OA\Delete(
 *   path="/loans/{id}",
 *   tags={"loans"},
 *   summary="Delete loan (ADMIN only)",
 *   security={{"bearerAuth":{}}},
 *   @OA\Parameter(name="id", in="path", required=true),
 *   @OA\Response(response=200, description="Loan deleted")
 * )
 */
Flight::route('DELETE /loans/@id', function ($id) {
  Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
  Flight::json(Flight::loans_service()->delete($id));
});

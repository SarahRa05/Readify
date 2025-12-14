<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {

  public function verifyToken($token) {

    if (!$token) {
      Flight::halt(401, "Missing authentication header");
    }

    // Accept BOTH formats:
    // 1) "Bearer <token>"
    // 2) "<token>"
    if (stripos($token, "Bearer ") === 0) {
      $token = trim(substr($token, 7));
    }

    $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

    // IMPORTANT: your token payload must have ->user
    if (!isset($decoded->user)) {
      Flight::halt(401, "Invalid token payload: user missing");
    }

    Flight::set('user', $decoded->user);
    Flight::set('jwt_token', $token);

    return true;
  }

  public function authorizeRole($requiredRole) {
    $user = Flight::get('user');
    if (!$user || !isset($user->role)) {
      Flight::halt(401, "Forbidden: role missing in token (NULL)");
    }
    if ($user->role !== $requiredRole) {
      Flight::halt(403, "Access denied: insufficient privileges");
    }
  }

  public function authorizeRoles($roles) {
    $user = Flight::get('user');
    if (!$user || !isset($user->role)) {
      Flight::halt(401, "Forbidden: role missing in token (NULL)");
    }
    if (!in_array($user->role, $roles)) {
      Flight::halt(403, "Forbidden: role not allowed");
    }
  }
}

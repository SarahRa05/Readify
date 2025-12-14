<?php

require_once __DIR__ . '/../dao/UsersDao.php';
require_once __DIR__ . '/../data/Roles.php';

use Firebase\JWT\JWT;

class AuthService {

  private $users_dao;

  public function __construct() {
    $this->users_dao = new UsersDao();
  }

  public function register($entity) {

    if (empty($entity['full_name']) || empty($entity['email']) || empty($entity['password'])) {
      return ['success' => false, 'error' => 'full_name, email and password are required.'];
    }

    $existing = $this->users_dao->getByEmail($entity['email']);
    if ($existing) {
      return ['success' => false, 'error' => 'Email already registered.'];
    }

    $new_user = [
      'full_name'     => $entity['full_name'],
      'email'         => $entity['email'],
      'password_hash' => password_hash($entity['password'], PASSWORD_BCRYPT),
      'role'          => $entity['role'] ?? Roles::MEMBER
    ];

    $inserted = $this->users_dao->add($new_user);
    unset($inserted['password_hash']);

    return ['success' => true, 'data' => $inserted];
  }

  public function login($entity) {

    if (empty($entity['email']) || empty($entity['password'])) {
      return ['success' => false, 'error' => 'Email and password are required.'];
    }

    $user = $this->users_dao->getByEmail($entity['email']);
    if (!$user || !password_verify($entity['password'], $user['password_hash'])) {
      return ['success' => false, 'error' => 'Invalid email or password.'];
    }

    unset($user['password_hash']);

    $jwt_payload = [
      'user' => $user,
      'iat'  => time(),
      'exp'  => time() + (60 * 60 * 24)
    ];

    $token = JWT::encode($jwt_payload, Config::JWT_SECRET(), 'HS256');

    return ['success' => true, 'data' => [
      'token' => $token,
      'user'  => $user
    ]];
  }
}

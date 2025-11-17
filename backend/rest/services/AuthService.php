<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

/**
 * AuthService
 * Handles registration and login + JWT (professor style)
 */

// services & DAO
require_once __DIR__ . '/UsersService.php';
require_once __DIR__ . '/../dao/UsersDao.php';
// ⬆️ NOTICE: no config.php here – it will be loaded by BaseDao via UsersDAO

class AuthService {

    private $users_service;
    private $users_dao;

    public function __construct() {
        $this->users_service = new UsersService();
        $this->users_dao     = new UsersDao();   // same as in professor's code
    }

    // ----------------- REGISTER -----------------
    /**
     * Register new user (used by POST /auth/register)
     */
    public function register($data) {
        // reuse UsersService so validation & hashing stay in one place
        $res = $this->users_service->createUser($data);

        if (!$res['success']) {
            return $res;
        }

        // professor usually just returns created user
        return $res;
    }

    // ----------------- LOGIN -----------------
    /**
     * Login existing user (used by POST /auth/login)
     */
    public function login($data) {
        if (empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'error' => 'Email and password are required.'];
        }

        // find user by email
        $user = $this->users_dao->getByEmail($data['email']);
        if (!$user) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // verify password
        if (!password_verify($data['password'], $user['password_hash'])) {
            return ['success' => false, 'error' => 'Invalid email or password.'];
        }

        // JWT payload (professor-style)
        $payload = [
            'user_id' => $user['user_id'],
            'email'   => $user['email'],
            'role'    => $user['role'],
            'iat'     => time(),
            'exp'     => time() + 3600 * 24 // 24h
        ];

        $token = JWT::encode($payload, Config::JWT_SECRET(), 'HS256');

        // never send password hash
        unset($user['password_hash']);

        return [
            'success' => true,
            'data'    => [
                'token' => $token,
                'user'  => $user
            ]
        ];
    }
}


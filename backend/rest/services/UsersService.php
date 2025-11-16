<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/UsersDao.php';

class UsersService extends BaseService {

    public function __construct() {
        parent::__construct(new UsersDao());
    }

    // Get all users (without password_hash)
    public function getUsers() {
        $users = parent::get_all();
        foreach ($users as &$u) {
            unset($u['password_hash']);
        }
        return ['success' => true, 'data' => $users];
    }

    // Get single user by ID (without password_hash)
    public function getUser($id) {
        $u = parent::get_by_id($id);
        if (!$u) {
            return ['success' => false, 'error' => 'User not found.'];
        }
        unset($u['password_hash']);
        return ['success' => true, 'data' => $u];
    }

    // Create a new user (used by /users and by auth register)
    public function createUser($data) {
        if (empty($data['full_name']) || empty($data['email']) || empty($data['password'])) {
            return ['success' => false, 'error' => 'full_name, email and password are required.'];
        }

        $entity = [
            'full_name'      => $data['full_name'],
            'email'          => $data['email'],
            'password_hash'  => password_hash($data['password'], PASSWORD_BCRYPT),
            'role'           => $data['role'] ?? 'member'
        ];

        $inserted = $this->dao->add($entity);   // BaseDao::add sets $inserted['id']

        unset($inserted['password_hash']);
        return ['success' => true, 'data' => $inserted];
    }

    // Update user by ID
    public function updateUser($id, $data) {
        $entity = [];

        if (isset($data['full_name'])) {
            $entity['full_name'] = $data['full_name'];
        }
        if (isset($data['email'])) {
            $entity['email'] = $data['email'];
        }
        if (isset($data['role'])) {
            $entity['role'] = $data['role'];
        }
        if (isset($data['password']) && $data['password'] !== '') {
            $entity['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if (empty($entity)) {
            return ['success' => false, 'error' => 'Nothing to update.'];
        }

        // Use UsersDao::updateById so we update by user_id PK
        $updated = $this->dao->updateById($id, $entity);
        unset($updated['password_hash']);

        return ['success' => true, 'data' => $updated];
    }

    // Delete user by ID
    public function deleteUser($id) {
        $ok = $this->dao->delete($id);
        return ['success' => true, 'data' => ['deleted' => $ok]];
    }
}




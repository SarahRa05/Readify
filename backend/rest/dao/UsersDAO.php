<?php
require_once __DIR__ . '/BaseDao.php';

class UsersDao extends BaseDao
{
    // primary key column name in DB
    private $pk = 'user_id';

    public function __construct()
    {
        parent::__construct('users');
    }

    // Get single user by ID (uses user_id column, not generic id)
    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE {$this->pk} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Delete user by ID
    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE {$this->pk} = :id");
        $stmt->bindValue(':id', $id); 
        return $stmt->execute();
    }

    // Update user by ID (wrapper around BaseDao::update)
    public function updateById($id, $entity) {
        return parent::update($entity, $id, $this->pk);
    }

    // 🔹 NEW: find user by email (used for login)
    public function getByEmail($email) {
        $query = "SELECT * FROM users WHERE email = :email";
        return $this->query_unique($query, ['email' => $email]);
    }
}



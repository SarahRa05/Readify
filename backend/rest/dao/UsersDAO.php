<?php
require_once __DIR__ . '/BaseDao.php';

class UsersDao extends BaseDao
{
    private $pk = 'user_id';

    public function __construct()
    {
        parent::__construct('users');
    }

    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE {$this->pk} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM users WHERE {$this->pk} = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function updateById($id, $entity) {
        return parent::update($entity, $id, $this->pk);
    }

    // ✅ used for login
    public function getByEmail($email) {
        // IMPORTANT: include role explicitly
        $query = "SELECT user_id, full_name, email, password_hash, role
                  FROM users
                  WHERE email = :email";
        return $this->query_unique($query, ['email' => $email]);
    }
}

<?php
require_once __DIR__ . '/BaseDao.php';

class LoansDao extends BaseDao
{
    private $pk = 'loan_id';

    public function __construct()
    {
        parent::__construct('loans');
    }

    // ✅ IMPORTANT: match BaseService method names (snake_case)
    public function get_all() {
        return parent::getAll();
    }

    public function get_by_id($id) {
        $stmt = $this->connection->prepare("SELECT * FROM loans WHERE {$this->pk} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // ✅ NEW: used by LoansService::get_by_user_id()
    public function get_by_user_id($user_id) {
        $stmt = $this->connection->prepare("SELECT * FROM loans WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function add($entity) {
        return parent::add($entity);
    }

    public function update($entity, $id, $id_column = "id")
    {
        // default PK should be loan_id in your case
        if ($id_column === "id") $id_column = $this->pk;
        return parent::update($entity, $id, $id_column);
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM loans WHERE {$this->pk} = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
}

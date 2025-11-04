<?php
require_once __DIR__ . '/BaseDao.php';

class LoansDao extends BaseDao
{
    private $pk = 'loan_id';

    public function __construct()
    {
        parent::__construct('loans');
    }

    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM loans WHERE {$this->pk} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM loans WHERE {$this->pk} = :id");
        $stmt->bindValue(':id', $id); 
        return $stmt->execute();
    }

    public function updateById($id, $entity) {
        return parent::update($entity, $id, $this->pk);
    }
}

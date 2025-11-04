<?php
require_once __DIR__ . '/BaseDao.php';

class CategoriesDao extends BaseDao
{
    private $pk = 'category_id';

    public function __construct()
    {
        parent::__construct('categories');
    }

    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM categories WHERE {$this->pk} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM categories WHERE {$this->pk} = :id");
        $stmt->bindValue(':id', $id); 
        return $stmt->execute();
    }

    public function updateById($id, $entity) {
        return parent::update($entity, $id, $this->pk);
    }
}

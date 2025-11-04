<?php
require_once __DIR__ . '/BaseDao.php';

class BooksDao extends BaseDao
{
    private $pk = 'book_id';

    public function __construct()
    {
        parent::__construct('books');
    }

    public function getById($id) {
        $stmt = $this->connection->prepare("SELECT * FROM books WHERE {$this->pk} = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function delete($id)
    {
        $stmt = $this->connection->prepare("DELETE FROM books WHERE {$this->pk} = :id");
        $stmt->bindValue(':id', $id); 
        return $stmt->execute();
    }

    public function updateById($id, $entity) {
        return parent::update($entity, $id, $this->pk);
    }
}

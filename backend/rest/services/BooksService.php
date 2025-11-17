<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BooksDao.php';

class BooksService extends BaseService {

    public function __construct() {
        parent::__construct(new BooksDao());
    }

    public function getBooks() {
        return ['success' => true, 'data' => parent::get_all()];
    }

    public function getBook($id) {
        $b = parent::get_by_id($id);
        if (!$b) {
            return ['success' => false, 'error' => 'Book not found'];
        }
        return ['success' => true, 'data' => $b];
    }

    public function addBook($data) {
        // BaseDao::add returns the whole inserted entity with id
        $inserted = parent::add($data);
        return ['success' => true, 'data' => $inserted];
    }

    public function updateBook($id, $data) {
        // IMPORTANT: entity first, id second, PK column name
        $updated = parent::update($data, $id, 'book_id');
        return ['success' => true, 'data' => $updated];
    }

    public function deleteBook($id) {
        $ok = parent::delete($id);
        return ['success' => true, 'data' => ['deleted' => $ok]];
    }
}


<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/BooksDao.php';

class BooksService extends BaseService {

    public function __construct() {
        parent::__construct(new BooksDao());
    }

    public function getBooks() {
        try {
            return ['success' => true, 'data' => parent::get_all()];
        } catch (Exception $e) {
            return ['success' => false, 'status' => 500, 'error' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function getBook($id) {
        if (!is_numeric($id)) {
            return ['success' => false, 'status' => 400, 'error' => 'Invalid book id'];
        }

        try {
            $b = parent::get_by_id((int)$id);
            if (!$b) {
                return ['success' => false, 'status' => 404, 'error' => 'Book not found'];
            }
            return ['success' => true, 'data' => $b];
        } catch (Exception $e) {
            return ['success' => false, 'status' => 500, 'error' => 'Database error: ' . $e->getMessage()];
        }
    }

    // ------------------ HELPERS ------------------
    private function clean_string($v) {
        if ($v === null) return null;
        return trim((string)$v);
    }

    private function parse_int_or_null($v) {
        if ($v === null || $v === '') return null;
        if (!is_numeric($v)) return null;
        return (int)$v;
    }

    private function validate_book_payload($data, $is_update = false) {
        // Required on create; on update we allow partial updates (but if provided, must be valid)
        $title  = isset($data['title'])  ? $this->clean_string($data['title'])  : null;
        $author = isset($data['author']) ? $this->clean_string($data['author']) : null;

        $publication_year = array_key_exists('publication_year', $data)
            ? $this->parse_int_or_null($data['publication_year'])
            : null;

        $available_copies = array_key_exists('available_copies', $data)
            ? $this->parse_int_or_null($data['available_copies'])
            : null;

        $category_id = array_key_exists('category_id', $data)
            ? $this->parse_int_or_null($data['category_id'])
            : null;

        // CREATE: required fields must exist and be valid
        if (!$is_update) {
            if (!$title || strlen($title) < 2) {
                return ['ok' => false, 'error' => 'Title is required (min 2 characters).'];
            }
            if (!$author || strlen($author) < 2) {
                return ['ok' => false, 'error' => 'Author is required (min 2 characters).'];
            }
            if ($publication_year === null) {
                return ['ok' => false, 'error' => 'Publication year is required and must be a number.'];
            }
            if ($available_copies === null) {
                return ['ok' => false, 'error' => 'Available copies is required and must be a number.'];
            }
        }

        // UPDATE: if fields are present, validate them
        if ($title !== null && strlen($title) < 2) {
            return ['ok' => false, 'error' => 'Title must be at least 2 characters.'];
        }
        if ($author !== null && strlen($author) < 2) {
            return ['ok' => false, 'error' => 'Author must be at least 2 characters.'];
        }

        if ($publication_year !== null && ($publication_year < 0 || $publication_year > 2100)) {
            return ['ok' => false, 'error' => 'Publication year must be between 0 and 2100.'];
        }

        if ($available_copies !== null && $available_copies < 1) {
            return ['ok' => false, 'error' => 'Available copies must be at least 1.'];
        }

        // category_id is nullable, but if provided must be >= 1
        if ($category_id !== null && $category_id < 1) {
            return ['ok' => false, 'error' => 'Invalid category id.'];
        }

        // Build sanitized payload (only allowed keys)
        $clean = [];

        if (isset($data['isbn'])) $clean['isbn'] = $this->clean_string($data['isbn']);
        if ($title !== null) $clean['title'] = $title;
        if ($author !== null) $clean['author'] = $author;
        if ($publication_year !== null) $clean['publication_year'] = $publication_year;

        // allow null explicitly if category_id is sent
        if (array_key_exists('category_id', $data)) $clean['category_id'] = $category_id;

        if ($available_copies !== null) $clean['available_copies'] = $available_copies;

        // If your books table has these columns and you want to accept them:
        // if (isset($data['description'])) $clean['description'] = $this->clean_string($data['description']);
        // if (isset($data['publisher'])) $clean['publisher'] = $this->clean_string($data['publisher']);

        return ['ok' => true, 'clean' => $clean];
    }

    // ------------------ CRUD ------------------
    public function addBook($data) {
        if (!is_array($data)) {
            return ['success' => false, 'status' => 400, 'error' => 'Invalid request body.'];
        }

        $v = $this->validate_book_payload($data, false);
        if (!$v['ok']) {
            return ['success' => false, 'status' => 400, 'error' => $v['error']];
        }

        try {
            $inserted = parent::add($v['clean']);
            return ['success' => true, 'data' => $inserted];
        } catch (Exception $e) {
            return ['success' => false, 'status' => 500, 'error' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function updateBook($id, $data) {
        if (!is_numeric($id)) {
            return ['success' => false, 'status' => 400, 'error' => 'Invalid book id'];
        }
        if (!is_array($data)) {
            return ['success' => false, 'status' => 400, 'error' => 'Invalid request body.'];
        }

        try {
            // ensure book exists (nice for defense)
            $existing = parent::get_by_id((int)$id);
            if (!$existing) {
                return ['success' => false, 'status' => 404, 'error' => 'Book not found'];
            }

            $v = $this->validate_book_payload($data, true);
            if (!$v['ok']) {
                return ['success' => false, 'status' => 400, 'error' => $v['error']];
            }

            if (count($v['clean']) === 0) {
                return ['success' => false, 'status' => 400, 'error' => 'No valid fields provided for update.'];
            }

            $updated = parent::update($v['clean'], (int)$id, 'book_id');
            return ['success' => true, 'data' => $updated];
        } catch (Exception $e) {
            return ['success' => false, 'status' => 500, 'error' => 'Database error: ' . $e->getMessage()];
        }
    }

    public function deleteBook($id) {
        if (!is_numeric($id)) {
            return ['success' => false, 'status' => 400, 'error' => 'Invalid book id'];
        }

        try {
            $ok = parent::delete((int)$id);
            return ['success' => true, 'data' => ['deleted' => $ok]];
        } catch (Exception $e) {
            return ['success' => false, 'status' => 500, 'error' => 'Database error: ' . $e->getMessage()];
        }
    }
}

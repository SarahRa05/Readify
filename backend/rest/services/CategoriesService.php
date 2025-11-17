<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CategoriesDao.php';

class CategoriesService extends BaseService {
    private $categories_dao;

    public function __construct() {
        $this->categories_dao = new CategoriesDao();
        parent::__construct($this->categories_dao);
    }

    /* ========== READ ========== */

    public function getCategories() {
        $categories = parent::get_all();
        return ['success' => true, 'data' => $categories];
    }

    public function getCategory($id) {
        $c = parent::get_by_id($id);
        if (!$c) return ['success' => false, 'error' => 'Category not found.'];
        return ['success' => true, 'data' => $c];
    }

    /* ========== CREATE ========== */

    public function createCategory($data) {
        // required field
        if (empty($data['category_name'])) {
            return ['success' => false, 'error' => 'category_name is required.'];
        }

        // unique name
        $existing = $this->categories_dao->getByName($data['category_name']);
        if ($existing) {
            return ['success' => false, 'error' => 'Category name already exists.'];
        }

        // description is optional
        $entity = parent::add($data);
        return ['success' => true, 'data' => $entity];
    }

    /* ========== UPDATE ========== */

    public function updateCategory($id, $data) {
        // if name changes, check uniqueness
        if (!empty($data['category_name'])) {
            $existing = $this->categories_dao->getByName($data['category_name']);
            if ($existing && (int)$existing['category_id'] !== (int)$id) {
                return ['success' => false, 'error' => 'Category name already exists.'];
            }
        }

        $updated = parent::update($data, $id, 'category_id');
        return ['success' => true, 'data' => $updated];
    }

    /* ========== DELETE ========== */

    public function deleteCategory($id) {
        $ok = parent::delete($id);
        return ['success' => (bool)$ok];
    }
}

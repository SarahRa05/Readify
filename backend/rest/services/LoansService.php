<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/LoansDao.php';

class LoansService extends BaseService {

    public function __construct() {
        parent::__construct(new LoansDao());
    }

    // Return all loans
    public function getLoans() {
        return ['success' => true, 'data' => parent::get_all()];
    }

    // Get single loan
    public function getLoan($id) {
        $l = parent::get_by_id($id);
        if (!$l) return ['success' => false, 'error' => 'Loan not found'];
        return ['success' => true, 'data' => $l];
    }

    // Create new loan (basic validation)
    public function addLoan($data) {
        if (empty($data['user_id']) || empty($data['book_id']) ||
            empty($data['borrow_date']) || empty($data['due_date'])) {
            return ['success' => false, 'error' => 'user_id, book_id, borrow_date and due_date are required.'];
        }

        $entity = parent::add($data);
        return ['success' => true, 'data' => $entity];
    }

    // Update existing loan
    public function updateLoan($id, $data) {
        $entity = parent::update($data, $id, 'loan_id');
        return ['success' => true, 'data' => $entity];
    }

    // Delete loan
    public function deleteLoan($id) {
        $ok = parent::delete($id);
        return ['success' => true, 'data' => ['deleted' => $ok]];
    }
}

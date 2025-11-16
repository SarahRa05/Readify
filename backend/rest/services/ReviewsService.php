<?php

require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReviewsDao.php';

class ReviewsService extends BaseService {

    public function __construct() {
        parent::__construct(new ReviewsDao());
    }

    public function getReviews() {
        return ['success' => true, 'data' => parent::get_all()];
    }

    public function getReview($id) {
        $r = parent::get_by_id($id);
        if (!$r) return ['success' => false, 'error' => 'Review not found'];
        return ['success' => true, 'data' => $r];
    }

    public function addReview($data) {
        if (empty($data['user_id']) || empty($data['book_id']) || empty($data['rating'])) {
            return ['success' => false, 'error' => 'user_id, book_id and rating are required.'];
        }

        if ($data['rating'] < 1 || $data['rating'] > 5) {
            return ['success' => false, 'error' => 'Rating must be between 1 and 5.'];
        }

        $entity = parent::add($data);
        return ['success' => true, 'data' => $entity];
    }

    public function updateReview($id, $data) {
        $entity = parent::update($data, $id, 'review_id');
        return ['success' => true, 'data' => $entity];
    }

    public function deleteReview($id) {
        $ok = parent::delete($id);
        return ['success' => true, 'data' => ['deleted' => $ok]];
    }
}

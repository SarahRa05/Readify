<?php
require 'vendor/autoload.php'; // after you install FlightPHP with Composer

require_once __DIR__ . '/dao/BooksDAO.php';
require_once __DIR__ . '/dao/CategoriesDAO.php';
require_once __DIR__ . '/dao/UsersDAO.php';
require_once __DIR__ . '/dao/LoansDAO.php';
require_once __DIR__ . '/dao/ReviewsDAO.php';

/**************
 * BOOKS
 **************/

Flight::route('GET /books', function() {
    $dao = new BooksDAO();
    Flight::json($dao->getAll());
});

Flight::route('GET /books/@id', function($id) {
    $dao = new BooksDAO();
    $book = $dao->getById($id);
    if ($book) {
        Flight::json($book);
    } else {
        Flight::halt(404, 'Book not found');
    }
});

Flight::route('POST /books', function() {
    $data = Flight::request()->data->getData();
    $dao = new BooksDAO();
    $newId = $dao->create($data);
    Flight::json(["book_id" => $newId], 201);
});

Flight::route('PUT /books/@id', function($id) {
    $data = Flight::request()->data->getData();
    $dao = new BooksDAO();
    $ok = $dao->update($id, $data);
    Flight::json(["updated" => $ok]);
});

Flight::route('DELETE /books/@id', function($id) {
    $dao = new BooksDAO();
    $ok = $dao->delete($id);
    Flight::json(["deleted" => $ok]);
});

/**************
 * CATEGORIES (just as an example)
 **************/

Flight::route('GET /categories', function() {
    $dao = new CategoriesDAO();
    Flight::json($dao->getAll());
});

Flight::start();

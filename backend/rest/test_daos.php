<?php
require_once __DIR__ . '/dao/UsersDao.php';
require_once __DIR__ . '/dao/CategoriesDao.php';
require_once __DIR__ . '/dao/BooksDao.php';
require_once __DIR__ . '/dao/LoansDao.php';
require_once __DIR__ . '/dao/ReviewsDao.php';

header('Content-Type: text/plain; charset=utf-8');

$usersDao      = new UsersDao();
$categoriesDao = new CategoriesDao();
$booksDao      = new BooksDao();
$loansDao      = new LoansDao();
$reviewsDao    = new ReviewsDao();

echo "=== getAll() checks ===\n";
echo "USERS:\n";      print_r($usersDao->getAll());      echo "\n";
echo "CATEGORIES:\n"; print_r($categoriesDao->getAll()); echo "\n";
echo "BOOKS:\n";      print_r($booksDao->getAll());      echo "\n";
echo "LOANS:\n";      print_r($loansDao->getAll());      echo "\n";
echo "REVIEWS:\n";    print_r($reviewsDao->getAll());    echo "\n";


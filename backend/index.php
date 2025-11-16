<?php

require 'vendor/autoload.php';

require_once __DIR__ . '/config.php';

// ================== USERS ==================
require_once __DIR__ . '/rest/services/UsersService.php';
Flight::register('users_service', 'UsersService');
require_once __DIR__ . '/rest/routes/UserRoutes.php';

// ================== CATEGORIES ==================
require_once __DIR__ . '/rest/services/CategoriesService.php';
Flight::register('categories_service', 'CategoriesService');
require_once __DIR__ . '/rest/routes/CategoryRoutes.php';

// ================== BOOKS ==================
require_once __DIR__ . '/rest/services/BooksService.php';
Flight::register('books_service', 'BooksService');
require_once __DIR__ . '/rest/routes/BooksRoutes.php';

// ================== LOANS ==================
require_once __DIR__ . '/rest/services/LoansService.php';
Flight::register('loans_service', 'LoansService');
require_once __DIR__ . '/rest/routes/LoansRoutes.php';

// ================== REVIEWS ==================
require_once __DIR__ . '/rest/services/ReviewsService.php';
Flight::register('reviews_service', 'ReviewsService');
require_once __DIR__ . '/rest/routes/ReviewsRoutes.php';

// ================== AUTH ==================
require_once __DIR__ . '/rest/services/AuthService.php';
Flight::register('auth_service', 'AuthService');
require_once __DIR__ . '/rest/routes/AuthRoutes.php';

// Start Flight
Flight::start();

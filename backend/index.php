<?php

require 'vendor/autoload.php';
require_once __DIR__ . '/rest/config.php';

require_once __DIR__ . '/rest/data/Roles.php';
require_once __DIR__ . '/rest/middleware/AuthMiddleware.php';

Flight::register('auth_middleware', 'AuthMiddleware');

// ================== SERVICES ==================
require_once __DIR__ . '/rest/services/UsersService.php';
Flight::register('users_service', 'UsersService');

require_once __DIR__ . '/rest/services/CategoriesService.php';
Flight::register('categories_service', 'CategoriesService');

require_once __DIR__ . '/rest/services/BooksService.php';
Flight::register('books_service', 'BooksService');

require_once __DIR__ . '/rest/services/LoansService.php';
Flight::register('loans_service', 'LoansService');

require_once __DIR__ . '/rest/services/ReviewsService.php';
Flight::register('reviews_service', 'ReviewsService');

require_once __DIR__ . '/rest/services/AuthService.php';
Flight::register('auth_service', 'AuthService');


// ================== CORS (needed for Live Server :5500) ==================
Flight::route('OPTIONS /*', function () {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authentication, Authorization");
  header("Access-Control-Max-Age: 86400");
  exit;
});

Flight::before('start', function () {
  header("Access-Control-Allow-Origin: *");
  header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
  header("Access-Control-Allow-Headers: Content-Type, Authentication, Authorization");
});



// GLOBAL AUTH (applies to ALL HTTP methods)
Flight::route('GET|POST|PUT|PATCH|DELETE /*', function () {

  $url = Flight::request()->url;

  // Allow auth + docs without token
  if (
    strpos($url, '/auth/login') === 0 ||
    strpos($url, '/auth/register') === 0 ||
    strpos($url, '/public') === 0 ||
    strpos($url, '/public/v1/docs') === 0 ||
    strpos($url, '/docs') === 0
  ) {
    return TRUE;
  }

  try {
    // :
    $token = Flight::request()->getHeader("Authentication");

    // Also allow Authorization:
    if (!$token) {
      $token = Flight::request()->getHeader("Authorization");
    }

    Flight::auth_middleware()->verifyToken($token);
    return TRUE;

  } catch (Exception $e) {
    Flight::halt(401, $e->getMessage());
  }
});


// ================== ROUTES ==================
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/CategoryRoutes.php';
require_once __DIR__ . '/rest/routes/BooksRoutes.php';
require_once __DIR__ . '/rest/routes/LoansRoutes.php';
require_once __DIR__ . '/rest/routes/ReviewsRoutes.php';

Flight::start();


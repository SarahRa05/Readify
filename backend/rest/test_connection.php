<?php
require_once __DIR__ . '/config.php';

try {
  // mirror professor’s pattern (use Config getters)
  $dsn = "mysql:host=" . Config::DB_HOST() .
         ";dbname=" . Config::DB_NAME() .
         ";port=" . Config::DB_PORT() .
         ";charset=utf8mb4";

  $pdo = new PDO($dsn, Config::DB_USER(), Config::DB_PASSWORD(), [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
  ]);

  echo "OK: Connected to " . Config::DB_NAME() . " on " . Config::DB_HOST() . ":" . Config::DB_PORT();
} catch (PDOException $e) {
  echo "ERROR: " . $e->getMessage();
}


<?php

// ---------------- ERROR REPORTING (DEV SAFE) ----------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL ^ (E_NOTICE | E_DEPRECATED));

// ---------------- CONFIG CLASS ----------------
class Config
{
    // Helper: read ENV variable or fallback to default
    public static function get_env($key, $default = null)
    {
        if (isset($_ENV[$key]) && trim($_ENV[$key]) !== '') {
            return $_ENV[$key];
        }

        if (getenv($key) !== false && trim(getenv($key)) !== '') {
            return getenv($key);
        }

        return $default;
    }

    // ---------------- DATABASE ----------------
    public static function DB_NAME()
    {
        return self::get_env('DB_NAME', 'readify');
    }

    public static function DB_PORT()
    {
        return self::get_env('DB_PORT', 3307);
    }

    public static function DB_USER()
    {
        return self::get_env('DB_USER', 'root');
    }

    public static function DB_PASSWORD()
    {
        return self::get_env('DB_PASSWORD', '');
    }

    public static function DB_HOST()
    {
        return self::get_env('DB_HOST', '127.0.0.1');
    }

    // ---------------- JWT ----------------
    public static function JWT_SECRET()
    {
        return self::get_env('JWT_SECRET', 'your_key_string');
    }
}

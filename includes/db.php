<?php
/**
 * MMCS DATABASE CONNECTION
 * ========================
 * Creates a PDO singleton ($pdo) used by every page.
 * Connection details come from config.php constants.
 * 
 * USAGE anywhere in the app:
 *   global $pdo;   // or just $pdo in the same scope
 *   $stmt = $pdo->prepare("SELECT ...");
 */

require_once dirname(__DIR__) . '/config.php';

try {
    // Build DSN string from config constants
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Real prepared statements
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    if (DEV_MODE) {
        // Show raw error detail during development
        error_log("Database connection failed: " . $e->getMessage());
        die("A system error occurred. Please try again later.");
    } else {
        // Show generic message on production
        http_response_code(500);
        die("We are experiencing database connectivity issues. Please check back later.");
    }
}

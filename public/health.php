<?php
/**
 * MMCS HEALTH CHECK ENDPOINT
 * ===========================
 * Returns JSON status for monitoring/load-balancer checks.
 * 
 * Access: Public (no auth required)
 * Usage:  GET /public/health.php
 * Returns: 200 OK if DB is reachable; 503 otherwise.
 */

header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');
require_once dirname(__DIR__) . '/includes/functions.php';
send_security_headers();

require_once dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/db.php';

$status = 'ok';
$http_code = 200;
$db_status = 'ok';
$db_latency = 0;

try {
    $start = microtime(true);
    $pdo->query("SELECT 1");
    $db_latency = round((microtime(true) - $start) * 1000, 2);
} catch (PDOException $e) {
    $status = 'degraded';
    $http_code = 503;
    $db_status = 'error';
    error_log("Health check failed: " . $e->getMessage());
}

http_response_code($http_code);

echo json_encode([
    'status'      => $status,
    'timestamp'   => date('c'),
    'service'     => 'MMCS Web Platform',
    'version'     => '1.0.0',
    'checks'      => [
        'database' => [
            'status'  => $db_status,
            'latency_ms' => $db_latency,
        ],
    ],
]);

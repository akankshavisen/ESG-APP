<?php

// Load .env file manually
$envFile = __DIR__ . "/../.env";

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);

        // Ignore comments
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }

        [$key, $value] = array_pad(explode('=', $line, 2), 2, '');

        $key = trim($key);
        $value = trim($value);

        $_ENV[$key] = $value;
    }
}

// Database settings
$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$database = $_ENV['DB_DATABASE'] ?? 'esg_sentinel';

// Connect to database
$conn = new mysqli(
    $host,
    $username,
    $password,
    $database
);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>
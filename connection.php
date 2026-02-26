<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Database configuration
$dbHost = "localhost";
$dbUser = "root";
$dbPass = "";
$dbName = "login";

try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    $conn->set_charset("utf8mb4"); // ensures proper encoding
} catch (mysqli_sql_exception $e) {
    // Stop execution if connection fails
    die("Database connection failed: " . $e->getMessage());
}
?>
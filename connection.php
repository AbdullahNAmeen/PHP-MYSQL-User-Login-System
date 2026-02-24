<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$password = "";
$database = "login";

try {
    $conn = new mysqli($host, $user, $password, $database);
    
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {

    die("Database connection failed: " . $e->getMessage());
}

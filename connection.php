<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


$server   = "localhost";
$username = "root";
$password = "";
$database = "login";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

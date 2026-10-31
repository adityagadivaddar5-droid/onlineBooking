<?php

$host = "localhost";
$dbname = "online_booking";
$username = "root";
$password = "";

try {

    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $conn->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch(PDOException $e) {

    die("Database connection failed.");

}

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

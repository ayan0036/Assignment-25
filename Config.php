<?php
$host = 'localhost';
$db   = 'internship_portal';
$user = 'root';
$pass = '';
$opt  = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4",$user,$pass,$opt);
} catch(PDOException $e){
    exit("DB Connection Failed: ".$e->getMessage());
}
session_start();
?>

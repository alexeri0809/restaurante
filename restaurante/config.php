<?php
$host = "localhost";
$user = "root";
$pass = ""; // vazio por padrão no XAMPP
$dbname = "restaurante";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}
?>

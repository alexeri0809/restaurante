<?php

//Este não faz nada, apenas é um apoio para os outros




if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "restaurante";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Erro na ligação: " . $conn->connect_error);
}


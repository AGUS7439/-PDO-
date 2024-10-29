<?php
function connection() {
    $server = "localhost";
    $user = "root";
    $password = "";
    $db = "estudiantes";

    // Conectar a la base de datos
    $pdo = new PDO("mysql:host=$server;dbname=$db;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    return $pdo;
}
?>



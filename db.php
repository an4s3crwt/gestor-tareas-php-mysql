<?php
$hostDB = 'localhost';
$nameDB = 'gestiontareas';
$userDB = 'root';
$passwdDB = '';

$dsn = "mysql:host=$hostDB;dbname=$nameDB;charset=utf8;";

try {
    $db = new PDO($dsn, $userDB, $passwdDB);
} catch (PDOException $e) {
    echo "Error en la conexión con la base de datos." . $e->getMessage();
}
?>
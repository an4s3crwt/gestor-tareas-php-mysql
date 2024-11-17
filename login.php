<?php
session_start();

require 'db.php';

$error = '';

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si es un intento de registro o inicio de sesión
    if (isset($_POST['register'])) {
        // Procesar el registro
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Comprobar si el nombre de usuario ya existe
            $checkUser = $db->prepare("SELECT COUNT(*) FROM usuarios WHERE username = ?");
            $checkUser->execute([$username]);
            if ($checkUser->fetchColumn() > 0) {
                $error = "El nombre de usuario ya está en uso.";
            } else {
                // Encriptar la contraseña antes de guardar
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insertar el nuevo usuario en la base de datos
                $sql = "INSERT INTO usuarios (username, password) VALUES (?, ?)";
                $stmt = $db->prepare($sql);
                $stmt->execute([$username, $hashedPassword]);

                $_SESSION['user_id'] = $db->lastInsertId();
                header('Location: index.php');
                exit;
            }
        } catch (Exception $e) {
            $error = "Error al registrar: " . $e->getMessage();
        }
    } else {
        // Procesar el inicio de sesión
        $username = $_POST['username'];
        $password = $_POST['password'];

        try {
            // Buscar el usuario en la base de datos
            $sql = "SELECT * FROM usuarios WHERE username = ?";
            $res = $db->prepare($sql);
            $res->execute([$username]);
            $user = $res->fetch();

            if (!$user) {
                $error = "Usuario no encontrado.";
            } else {
                // Verificar la contraseña
                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    header('Location: index.php');
                    exit;
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
include 'views/login.php';
?>

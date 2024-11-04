<?php 
session_start();

$error ='';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require 'clases/Usuario.php';
    $usuario = new Usuario($_POST['username'], $_POST['password']);

    if($usuario->login()){//si devuelve true
        header('Location: index.php');
    }else{
        $error = '<p>Usuario o contraseña incorrecto</p>';
    }
}

//la vista
include 'views/login.php';
?>
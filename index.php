<?php
//controlador de lista_tareas.php

session_start();
include 'db.php';

require 'clases/Usuario.php';
Usuario::verificarSesion();

require 'clases/Tarea.php';
require 'clases/ManagerTareas.php';
$m = new ManagerTareas($db);
$tareas = $m->obtenerTareas(); //mostrarlas todas


//mostrar los mensajes de error de eliminar.php
if(isset($_SESSION['mensaje'])) {
    echo "<p>{$_SESSION['mensaje']}</p>";
    unset($_SESSION['mensaje']);
}

if(isset($_SESSION['error'])){
    echo "<p>{$_SESSION['error']}</p>";
    unset($_SESSION['error']);
}

//la vista
include 'views/lista_tareas.php';
?>
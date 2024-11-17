<?php 
session_start();

require 'clases/ManagerTareas.php';
require 'clases/Tarea.php';
require 'db.php'; 


require 'clases/Usuario.php';


$error = '';
$m = new ManagerTareas($db);

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    

    $id = $_POST['id'];

    $nuevaT = new Tarea(
        $id,
        $_POST['nombre'],
        $_POST['descripcion'],
        $_POST['prioridad'],
        $_POST['fecha_limite']
    );

    try{

        $nuevaT->validar();
        $m->editarTarea($id, $nuevaT);

        $m->enviarNotificacionTarea("Editada", $nuevaT);

        header('Location: index.php');
        exit;
    }catch(Exception $e){
        $error = 'Error '. $e->getMessage();

    }
}else{ //obtener a través de GET escribiendolo en la url
    $id = $_GET['id'] ?? null;

    if ($id === null) {
        die("Error: ID de tarea no proporcionado.");
    }

    //utilizar el método que obtiene la tarea a través del id
    $tarea = $m->obtenerTarea($id);
}

//la vista
include 'views/editar_tarea.php';
?>
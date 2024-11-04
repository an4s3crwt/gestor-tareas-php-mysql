<?php 
session_start();
require 'clases/ManagerTareas.php';
require 'clases/Tarea.php';
require 'db.php';

$m = new ManagerTareas($db);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        $id = $_POST['id'];

        //llamar al eliminar tareas del manejador
        $m->eliminarTarea($id);

        $_SESSION['mensaje'] = "Se ha eliminado la tarea correctamente";

    }catch(Exception $e){
        $_SESSION['error'] =  $e->getMessage(); 
    }
}else{
    $_SESSION['error'] = 'Solicitud no válida';
}
header('Location: index.php');
?>

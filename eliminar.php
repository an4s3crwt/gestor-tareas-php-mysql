<?php 
session_start();
require 'clases/ManagerTareas.php';
require 'clases/Tarea.php';
require 'db.php';

$m = new ManagerTareas($db);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    try{
        $id = $_POST['id'];

        // Obtener la tarea antes de eliminarla para enviarla en la notificación
        $t = $m->obtenerTarea($id);

        //llamar al eliminar tareas del manejador
        $m->eliminarTarea($id);

        $_SESSION['mensaje'] = "Se ha eliminado la tarea correctamente";


        
        $m->enviarNotificacionTarea("Eliminada", $t);
        
    }catch(Exception $e){
        $_SESSION['error'] =  $e->getMessage(); 
    }
}else{
    $_SESSION['error'] = 'Solicitud no válida';
}
    header('Location: index.php');
    exit;
?>

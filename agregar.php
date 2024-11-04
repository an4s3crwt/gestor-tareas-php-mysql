<?php 
session_start();
include 'db.php';

require 'clases/Usuario.php';
Usuario::verificarSesion(); //solo

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    require 'clases/ManagerTareas.php';
    require 'clases/Tarea.php';

    try{
        $t = new Tarea(  //se crea el objeto
            null, //se añadirá luego en la clase ManagerTareas
            $_POST['nombre_tarea'],
            $_POST['descripcion'],
            $_POST['prioridad'],
            $_POST['fecha_limite']
        );
        $t->validar();

        $m = new ManagerTareas($db);
        $m->agregarTareas($t); //la agrega a la  base de datos y luego al array local

        header('Location: index.php');
    }catch(Exception $e){
        echo 'Error ' . $e->getMessage();
    }
}

//la vista
include 'views/agregar_tarea.php';

?>
<?php
include 'db.php'; //la conexión
require_once './correo.php'; // En lugar de require



class ManagerTareas {
    private $db;
    private $tareas = []; //alamacenar las tareas en local también(en la sesión)

    public function __construct($db) //para iniciar la conexión
    {
        $this->db = $db;//Gasí guarda la conexión de base de datos

        /*
        Si las tareas fueron almacenadas en la sesión como una cadena 
        de texto serializada, unserialize las convierte de nyuevo en un array
        **********************************************************************
        Si las tareass están almacenadas como array , las usa tal cual sin convertirlas
        */
        
        if (isset($_SESSION['tareas']) && is_string($_SESSION['tareas'])) {
            $this->tareas = unserialize($_SESSION['tareas']);
        } elseif (isset($_SESSION['tareas']) && is_array($_SESSION['tareas'])) {
            $this->tareas = $_SESSION['tareas'];
        }
        
    }


    /******************************************/

    public function agregarTareas($t){
        $sql = "insert into tarea(nombre, descripcion,prioridad,fecha_limite, user_id)values (?,?,?,?,?)";
        $res = $this->db->prepare($sql);
        $res->execute([
            $t->getNombre(),
            $t->getDescripcion(),
            $t->getPrioridad(),
            $t->getFechalimite(),
            $_SESSION['user_id']
        ]);

        $id = $this->db->lastInsertId();
        $t->setId($id);


        $this->tareas[$id] = $t;
        $_SESSION['tareas'] = serialize($this->tareas);
      
       
    }

    public function obtenerTareas() {
        // Filtra las tareas para el usuario que está logueado
        $sql = "SELECT * FROM tarea WHERE user_id = ?";
        $res = $this->db->prepare($sql);
        $res->execute([$_SESSION['user_id']]);
    
        // Crear array vacío para almacenar lo que se extrae de la base de datos
        $tareas = [];
    
        // Recorrer el resultado de cada fila
        foreach ($res as $fila) {
            $t = new Tarea(
                $fila['id'],
                $fila['nombre'],
                $fila['descripcion'],
                $fila['prioridad'],
                $fila['fecha_limite']
            );
    
            // Agregar cada objeto tarea al array local (el de la función, no el de clase)
            $tareas[] = $t;
        }
    
        return $tareas;
    }
    

    //solo una , para editarla o eliminarla
    public function obtenerTarea($id){
        $sql = "select * from tarea where id = ?";
        $res = $this->db->prepare($sql);
        $res->execute([$id]); //pasarla el id par acompletar la query

        $datos = $res->fetch();
        if($datos){// si existe tarea con ese id
            return new Tarea(
                $datos['id'],
                $datos['nombre'],
                $datos['descripcion'],
                $datos['prioridad'],
                $datos['fecha_limite']
            );
        }
        return null;

    }

    public function eliminarTarea($id) {
        // Asegurarse de que el usuario que intenta eliminar la tarea es el propietario
        $sql = "DELETE FROM tarea WHERE id = ? AND user_id = ?";
        $res = $this->db->prepare($sql);
        $res->execute([$id, $_SESSION['user_id']]);
    }
    
    public function editarTarea($id, $t) {
        // Asegurarse de que el usuario que intenta editar la tarea es el propietario
        $sql = "UPDATE tarea SET nombre=?, descripcion=?, prioridad=?, fecha_limite=? WHERE id=? AND user_id=?";
        $res = $this->db->prepare($sql);
        $res->execute([
            $t->getNombre(),
            $t->getDescripcion(),
            $t->getPrioridad(),
            $t->getFechalimite(),
            $id,
            $_SESSION['user_id']
        ]);
    
        // Actualizar el id local
        $t->setId($id);
        $this->tareas[$id] = $t;
        $_SESSION['tareas'] = serialize($this->tareas);
    }
    


    function enviarNotificacionTarea($accion, $tarea) {
        $asunto = "Notificación Tarea $accion";
        $mensaje = "
            <html>
                <body>
                    <h2>Tarea $accion</h2>
                    <p><strong>Nombre:</strong> {$tarea->getNombre()}</p>
                    <p><strong>Descripción:</strong> {$tarea->getDescripcion()}</p>
                    <p><strong>Prioridad:</strong> {$tarea->getPrioridad()}</p>
                    <p><strong>Fecha Límite:</strong> {$tarea->getFechaLimite()}</p>
                </body>
            </html>
        ";
        enviarCorreo($asunto, $mensaje);
    }
    
    
    
}


?>
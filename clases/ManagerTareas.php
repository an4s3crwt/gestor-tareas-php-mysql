<?php
include 'db.php'; //la conexión

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
        $sql = "insert into tarea(nombre, descripcion,prioridad,fecha_limite)values (?,?,?,?)";
        $res = $this->db->prepare($sql);
        $res->execute([
            $t->getNombre(),
            $t->getDescripcion(),
            $t->getPrioridad(),
            $t->getFechalimite()
        ]);

        $id = $this->db->lastInsertId();
        $t->setId($id);


        $this->tareas[$id] = $t;
        $_SESSION['tareas'] = serialize($this->tareas);
    }

    public function obtenerTareas(){
        $sql = "select * from tarea";
        $res = $this->db->prepare($sql);
        $res->execute();

        //crear array vacío para almacenar lo que se extrae de la base de datos
        $tareas = [];

        //recorrer el resultado de cada fila
        foreach($res as $fila){
            $t  =new Tarea (
                $fila['id'],
                $fila['nombre'],
                $fila['descripcion'],
                $fila['prioridad'],
                $fila['fecha_limite']
            );

            //agregar las cada objeto tarea al array local(el de la función no el de clase)
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

    public function editarTarea($id, $t){
        $sql = "update tarea set nombre=?, descripcion= ?, prioridad = ?, fecha_limite = ? where id= ?";
        $res = $this->db->prepare($sql);
        $res->execute([
            $t->getNombre(),
            $t->getDescripcion(),
            $t->getPrioridad(),
            $t->getFechalimite(),
            $id
        ]);


        //actualizar el id local
        $t->setId($id);
        $this->tareas[$id] = $t;
        $_SESSION['tareas'] = serialize($this->tareas);
    }






    public function eliminarTarea($id){
        $sql = "delete from tarea where id= ?";
        $res = $this->db->prepare($sql);
        $res->execute([$id]); //pasarle el id para completar la query
    }
}


?>
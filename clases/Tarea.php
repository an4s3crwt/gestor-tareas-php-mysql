<?php 
class Tarea {
    private $id;
    private $nombre;
    private $descripcion;
    private $prioridad;
    private $fecha_limite;

    public function __construct($id,$nombre,$descripcion,$prioridad,$fecha_limite)
    {
        $this->id = $id;
        $this->nombre =$nombre;
        $this->descripcion = $descripcion;
        $this->prioridad = $prioridad;
        $this->fecha_limite = $fecha_limite;
       
    }


    //añadir excepciones try_catch
    public function validar(){
        if (empty($this->nombre) || empty($this->descripcion) || empty($this->prioridad) || empty($this->fecha_limite)) {
            throw new Exception("Llena todos los campos.");
        }
        

        //Validar el formato de la FECHA (yyyy-mm-dd)
        if(!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->fecha_limite)){
            throw new Exception("Formato de fecha no válido");

        }

        //Que no sean más de 255 carácteres
        if(strlen($this->nombre) > 255){
            throw new Exception("El nombre de la tarea no puede sobrepasar los 255 carácteres.");

        }

        //que sea alguna de las tres
        if(!in_array($this->prioridad, ['baja', 'media', 'alta'])){
            throw new Exception('La prioridad ha de ser : baja, media o alta');

        }
    }

    public function getId(){
        return $this->id;
    }

    public function getFechalimite(){
        return $this->fecha_limite;
    }

    public function getNombre(){
        return $this->nombre;
    }
    public function getPrioridad(){
        return $this->prioridad;
    }

    public function getDescripcion(){
        return $this->descripcion;
    }

    public function setFechalimite($fecha){
        $this->fecha_limite = $fecha;
    }

    public function setId($id){
        $this->id = $id;
    }
    

    public function setNombre($nombre){
        $this->nombre = $nombre;
    }
    public function setDescripcion($desc){
        $this->descripcion = $desc;
    }
    public function setPrioridad($prioridad){
        $this->prioridad = $prioridad;
    }

}

?>
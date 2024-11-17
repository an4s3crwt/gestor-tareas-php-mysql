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
        $horaEnvio = date('H:i:s'); 
    
        $mensaje = "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Notificación Tarea</title>
            <style>
                @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&display=swap');
                body {
                    margin: 0;
                    padding: 0;
                    font-family: 'Playfair Display', serif;
                    background: #121212;
                    color: white;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    overflow: hidden;
                }
                .notification-container {
                    width: 90%;
                    max-width: 600px;
                    background: linear-gradient(145deg, #1c1c1c, #282828);
                    border-radius: 15px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
                    padding: 20px;
                    animation: fadeIn 1s ease-in-out;
                    position: relative;
                    overflow: hidden;
                }
                @keyframes fadeIn {
                    0% {
                        opacity: 0;
                        transform: scale(0.9);
                    }
                    100% {
                        opacity: 1;
                        transform: scale(1);
                    }
                }
                .notification-header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 1px solid #333;
                    padding-bottom: 15px;
                    animation: slideDown 0.8s ease-in-out;
                }
                @keyframes slideDown {
                    0% {
                        opacity: 0;
                        transform: translateY(-20px);
                    }
                    100% {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                .notification-header h2 {
                    font-size: 28px;
                    font-weight: 700;
                    color: white;
                    margin: 0;
                    letter-spacing: 1px;
                }
                .notification-item {
                    margin-top: 20px;
                    padding: 20px;
                    background: #191919;
                    border-radius: 10px;
                    box-shadow: inset 0 4px 6px rgba(0, 0, 0, 0.6);
                    transition: transform 0.3s ease, background-color 0.3s ease;
                    animation: slideInLeft 0.9s ease-in-out;
                }
                @keyframes slideInLeft {
                    0% {
                        opacity: 0;
                        transform: translateX(-20px);
                    }
                    100% {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }
                .notification-item:hover {
                    transform: translateY(-10px);
                    background: #252525;
                }
                .notification-item p {
                    font-size: 18px;
                    color: #e6e6e6;
                    margin: 10px 0;
                    transition: color 0.3s ease;
                }
                .notification-item:hover p {
                    color: #f4f4f4;
                }
                .notification-item p strong {
                    color: white;
                    transition: color 0.3s ease;
                }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 14px;
                    color: #777;
                    animation: fadeInFooter 1.5s ease-in-out;
                }
                @keyframes fadeInFooter {
                    0% {
                        opacity: 0;
                    }
                    100% {
                        opacity: 1;
                    }
                }
                .footer p {
                    margin: 0;
                    transition: color 0.3s ease;
                }
                .footer p:hover {
                    color: white;
                }
                     .hora-envio {
                margin-top: 10px;
                font-size: 16px;
                font-weight: bold;
                color: #e6e6e6;
            }
                .background-effects {
                    position: absolute;
                    top: -50%;
                    left: -50%;
                    width: 200%;
                    height: 200%;
                    background: radial-gradient(circle, rgba(255,255,255,0.1), rgba(0,0,0,0.8));
                    animation: rotate 10s linear infinite;
                }
                @keyframes rotate {
                    0% {
                        transform: rotate(0deg);
                    }
                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>
        </head>
        <body>
            <div class='notification-container'>
                <div class='background-effects'></div>
                <div class='notification-header'>
                    <h2>Tarea {$accion}</h2>
                </div>
                <div class='notification-item'>
                    <p><strong>Nombre:</strong> {$tarea->getNombre()}</p>
                    <p><strong>Descripción:</strong> {$tarea->getDescripcion()}</p>
                    <p><strong>Prioridad:</strong> {$tarea->getPrioridad()}</p>
                    <p><strong>Fecha Límite:</strong> {$tarea->getFechaLimite()}</p>
                </div>
                <div class='footer'>
                    <p>© 2024 Sistema de Gestión de Tareas</p>
                    <p class='hora-envio'>Hora de envío: {$horaEnvio}</p>
                </div>
            </div>
        </body>
        </html>
        ";
    
        enviarCorreo($asunto, $mensaje);
    }
    
    }
    
    
    
    



?>
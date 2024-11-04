<?php 

    class Usuario{
        private $username;
        private $password;

        public function __construct($username, $password)
        {
            $this->username = $username;
            $this->password = $password;
        }

        //función login aquí
        public function login(){
            if($this->username === 'admin' && $this->password === '1234'){//solo se puede acceder con estas credenciales
                $_SESSION['username'] = $this->username;
                return true;
            }
            return false;
        }

        public static function verificarSesion(){
            if(!isset($_SESSION['username'])){
                header('Location: login.php');
            }
        }

        
        public static function logout(){
            session_destroy();
            header("Location: login.php");
        }
    }
?>
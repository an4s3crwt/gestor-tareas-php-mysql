<?php
session_start();
require 'clases/Usuario.php';
Usuario::logout();//definido en la clase usuario que redirige al login.php
?>
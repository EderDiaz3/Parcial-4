<?php
namespace dist\DB;

use mysqli;

class Conexion{

    var $host = "";
    var $user = "";
    var $password = "";
    var $database = "";

    function __construct()
    {
        $this->host = "localhost";
        $this->user = "AlumnosPV";
        $this->password = "Prog.V2025";
        $this->database = "parcial4";
    }

    function Conectar(){
        $conexion = new mysqli($this->host, $this->user, $this->password, $this->database);

        if ($conexion->connect_error) {
            die("Error de conexión: " . $conexion->connect_error);
        }
        return $conexion;
    }


}


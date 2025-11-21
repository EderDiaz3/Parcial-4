<?php
namespace dist\Model;

use dist\DB\Conexion;

class Usuarios{
    var $UsuarioID = 0;
    var $Avatar = "Sin Avatar";
    var $FechaNacimiento = "0000-00-00";
    var $Correo = "Sin especificar";
    var $Contraseña = "Sin especificar";
    var $Telefono = "Sin especificar";
    var $Biografia = "Sin biografía";

    function __construct($UsuarioID = 0, $Avatar = "Sin Avatar", $FechaNacimiento = "0000-00-00", $Correo = "Sin especificar", $Contraseña = "Sin especificar", $Telefono = "Sin especificar", $Biografia = "Sin biografía")
    {
        $this->UsuarioID = $UsuarioID;
        $this->Avatar = $Avatar;
        $this->FechaNacimiento = $FechaNacimiento;
        $this->Correo = $Correo;
        $this->Contraseña = $Contraseña;
        $this->Telefono = $Telefono;
        $this->Biografia = $Biografia;
    }
    

}
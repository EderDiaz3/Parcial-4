<?php
session_start();

// Incluir archivos necesarios
require_once __DIR__ . '/../Controller/UsuariosController.php';
require_once __DIR__ . '/../Model/Usuarios.php';
require_once __DIR__ . '/../DB/Conexion.php';

use dist\Controller\UsuariosController;

// Verificar que se recibió un ID
if (!isset($_GET['id'])) {
    $_SESSION['mensaje_error'] = 'ID de usuario no proporcionado';
    header('Location: lista_usuarios.php');
    exit;
}

$usuarioID = $_GET['id'];
$controller = new UsuariosController();

// Intentar eliminar el usuario
$response = $controller->EliminarUsuario($usuarioID);

if ($response['success']) {
    $_SESSION['mensaje_exito'] = $response['message'];
} else {
    $_SESSION['mensaje_error'] = $response['message'];
}

// Redirigir a la lista de usuarios
header('Location: lista_usuarios.php');
exit;

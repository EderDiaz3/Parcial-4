<?php
session_start();

// Incluir el autoloader o los archivos necesarios
require_once __DIR__ . '/../Controller/UsuariosController.php';
require_once __DIR__ . '/../Model/Usuarios.php';
require_once __DIR__ . '/../DB/Conexion.php';

use dist\Controller\UsuariosController;

// Verificar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index2.php');
    exit;
}

$controller = new UsuariosController();
$response = ['success' => false, 'message' => ''];

// Determinar la acción (crear o actualizar)
$accion = $_POST['accion'] ?? 'crear';
$usuarioID = $_POST['usuario_id'] ?? null;

// Recopilar datos del formulario
$datos = [
    'correo' => $_POST['correo'] ?? '',
    'password' => $_POST['password'] ?? '',
    'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
    'telefono' => $_POST['telefono'] ?? '',
    'biografia' => $_POST['biografia'] ?? ''
];

// Validar que las contraseñas coincidan si se proporcionan
if (!empty($_POST['password']) && $_POST['password'] !== $_POST['password_confirm']) {
    $_SESSION['mensaje_error'] = 'Las contraseñas no coinciden';
    header('Location: index2.php');
    exit;
}

try {
    if ($accion === 'actualizar' && $usuarioID) {
        // Actualizar usuario existente
        $response = $controller->ActualizarUsuario($usuarioID, $datos);
    } else {
        // Crear nuevo usuario
        $response = $controller->CrearUsuario($datos);
    }

    // Guardar mensaje en sesión
    if ($response['success']) {
        $_SESSION['mensaje_exito'] = $response['message'];
        if (isset($response['usuarioID'])) {
            $_SESSION['usuario_id'] = $response['usuarioID'];
        }
    } else {
        $_SESSION['mensaje_error'] = $response['message'];
    }
} catch (Exception $e) {
    $_SESSION['mensaje_error'] = 'Error del servidor: ' . $e->getMessage();
}

// Redirigir: si se creó un usuario, ir a la lista; si se actualizó, volver al formulario
if ($response['success'] && $accion === 'crear') {
    header('Location: lista_usuarios.php');
} else {
    header('Location: index2.php' . ($usuarioID ? "?id=$usuarioID" : ''));
}
exit;

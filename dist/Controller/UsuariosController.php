<?php

namespace dist\Controller;

use dist\Model\Usuarios;
use dist\DB\Conexion;

class UsuariosController{
    
    /**
     * Obtiene un usuario por su ID
     * @param int $UsuarioID
     * @return Usuarios|null
     */
    public function ObtenerUsuarioPorID($UsuarioID){
        $conexion = new Conexion();
        $conn = $conexion->Conectar();

        $sql = "SELECT * FROM usuario WHERE UsuarioID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $UsuarioID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $usuario = new Usuarios(
                $row['UsuarioID'],
                $row['Avatar'],
                $row['FechaNacimiento'],
                $row['Correo'],
                $row['Contraseña'],
                $row['Telefono'],
                $row['Biografia']
            );
            $stmt->close();
            $conn->close();
            return $usuario;
        } else {
            $stmt->close();
            $conn->close();
            return null;
        }
    }

    /**
     * Crea un nuevo usuario
     * @param array $datos Array con los datos del usuario
     * @return array Respuesta con éxito o error
     */
    public function CrearUsuario($datos){
        try {
            // Validar datos requeridos
            if (empty($datos['correo']) || empty($datos['password'])) {
                return ['success' => false, 'message' => 'Correo y contraseña son obligatorios'];
            }

            // Verificar si el correo ya existe
            if ($this->ExisteCorreo($datos['correo'])) {
                return ['success' => false, 'message' => 'El correo ya está registrado'];
            }

            // Procesar el avatar si existe
            $avatarPath = $this->ProcesarAvatar($_FILES['avatar'] ?? null);

            // Hashear la contraseña
            $passwordHash = password_hash($datos['password'], PASSWORD_DEFAULT);

            // Crear el usuario
            $conexion = new Conexion();
            $conn = $conexion->Conectar();

            // Preparar variables para bind_param (no puede usar expresiones directamente)
            $fechaNacimiento = $datos['fecha_nacimiento'] ?? '0000-00-00';
            $telefono = $datos['telefono'] ?? 'Sin especificar';
            $biografia = $datos['biografia'] ?? 'Sin biografía';

            $sql = "INSERT INTO usuario (Avatar, FechaNacimiento, Correo, Contraseña, Telefono, Biografia) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ssssss",
                $avatarPath,
                $fechaNacimiento,
                $datos['correo'],
                $passwordHash,
                $telefono,
                $biografia
            );

            if ($stmt->execute()) {
                $usuarioID = $conn->insert_id;
                $stmt->close();
                $conn->close();
                return ['success' => true, 'message' => 'Usuario creado exitosamente', 'usuarioID' => $usuarioID];
            } else {
                $stmt->close();
                $conn->close();
                return ['success' => false, 'message' => 'Error al crear el usuario'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Actualiza los datos de un usuario
     * @param int $usuarioID
     * @param array $datos Array con los datos a actualizar
     * @return array Respuesta con éxito o error
     */
    public function ActualizarUsuario($usuarioID, $datos){
        try {
            // Validar que el usuario existe
            $usuarioExistente = $this->ObtenerUsuarioPorID($usuarioID);
            if (!$usuarioExistente) {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }

            $conexion = new Conexion();
            $conn = $conexion->Conectar();

            // Procesar avatar si se subió uno nuevo
            $avatarPath = $usuarioExistente->Avatar;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $nuevoAvatar = $this->ProcesarAvatar($_FILES['avatar']);
                if ($nuevoAvatar) {
                    // Eliminar avatar anterior si no es el default
                    if ($avatarPath !== 'Sin Avatar' && file_exists($avatarPath)) {
                        unlink($avatarPath);
                    }
                    $avatarPath = $nuevoAvatar;
                }
            }

            // Preparar la actualización
            $fechaNacimiento = $datos['fecha_nacimiento'] ?? $usuarioExistente->FechaNacimiento;
            $correo = $datos['correo'] ?? $usuarioExistente->Correo;
            $telefono = $datos['telefono'] ?? $usuarioExistente->Telefono;
            $biografia = $datos['biografia'] ?? $usuarioExistente->Biografia;

            // Si se proporciona nueva contraseña, hashearla
            $password = $usuarioExistente->Contraseña;
            if (!empty($datos['password'])) {
                $password = password_hash($datos['password'], PASSWORD_DEFAULT);
            }

            // Verificar si el correo ya está en uso por otro usuario
            if ($correo !== $usuarioExistente->Correo && $this->ExisteCorreo($correo, $usuarioID)) {
                $conn->close();
                return ['success' => false, 'message' => 'El correo ya está en uso por otro usuario'];
            }

            // Actualizar en la base de datos
            $sql = "UPDATE usuario SET Avatar=?, FechaNacimiento=?, Correo=?, Contraseña=?, Telefono=?, Biografia=? WHERE UsuarioID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi", $avatarPath, $fechaNacimiento, $correo, $password, $telefono, $biografia, $usuarioID);

            if ($stmt->execute()) {
                $stmt->close();
                $conn->close();
                return ['success' => true, 'message' => 'Usuario actualizado exitosamente'];
            } else {
                $stmt->close();
                $conn->close();
                return ['success' => false, 'message' => 'Error al actualizar el usuario'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Elimina un usuario
     * @param int $usuarioID
     * @return array Respuesta con éxito o error
     */
    public function EliminarUsuario($usuarioID){
        try {
            $usuario = $this->ObtenerUsuarioPorID($usuarioID);
            if (!$usuario) {
                return ['success' => false, 'message' => 'Usuario no encontrado'];
            }

            $conexion = new Conexion();
            $conn = $conexion->Conectar();

            $sql = "DELETE FROM usuario WHERE UsuarioID=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $usuarioID);

            if ($stmt->execute()) {
                // Eliminar avatar si existe
                if ($usuario->Avatar !== 'Sin Avatar' && file_exists($usuario->Avatar)) {
                    unlink($usuario->Avatar);
                }
                $stmt->close();
                $conn->close();
                return ['success' => true, 'message' => 'Usuario eliminado exitosamente'];
            } else {
                $stmt->close();
                $conn->close();
                return ['success' => false, 'message' => 'Error al eliminar el usuario'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Error: ' . $e->getMessage()];
        }
    }

    /**
     * Obtiene todos los usuarios
     * @return array Lista de usuarios
     */
    public function ObtenerTodosLosUsuarios(){
        $conexion = new Conexion();
        $conn = $conexion->Conectar();

        $sql = "SELECT * FROM usuario";
        $result = $conn->query($sql);

        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = new Usuarios(
                $row['UsuarioID'],
                $row['Avatar'],
                $row['FechaNacimiento'],
                $row['Correo'],
                $row['Contraseña'],
                $row['Telefono'],
                $row['Biografia']
            );
        }

        $conn->close();
        return $usuarios;
    }

    /**
     * Verifica si un correo ya existe en la base de datos
     * @param string $correo
     * @param int|null $excluirUsuarioID Usuario a excluir de la búsqueda (para actualizaciones)
     * @return bool
     */
    private function ExisteCorreo($correo, $excluirUsuarioID = null){
        $conexion = new Conexion();
        $conn = $conexion->Conectar();

        if ($excluirUsuarioID) {
            $sql = "SELECT COUNT(*) as total FROM usuario WHERE Correo = ? AND UsuarioID != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $correo, $excluirUsuarioID);
        } else {
            $sql = "SELECT COUNT(*) as total FROM usuario WHERE Correo = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $correo);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        return $row['total'] > 0;
    }

    /**
     * Procesa y guarda el avatar subido
     * @param array|null $archivo Archivo subido desde $_FILES
     * @return string Ruta del avatar o 'Sin Avatar' si no hay archivo
     */
    private function ProcesarAvatar($archivo){
        if (!$archivo || $archivo['error'] === UPLOAD_ERR_NO_FILE) {
            return 'Sin Avatar';
        }

        if ($archivo['error'] !== UPLOAD_ERR_OK) {
            return 'Sin Avatar';
        }

        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($archivo['type'], $tiposPermitidos)) {
            return 'Sin Avatar';
        }

        // Validar tamaño (máximo 5MB)
        if ($archivo['size'] > 5 * 1024 * 1024) {
            return 'Sin Avatar';
        }

        // Crear directorio si no existe
        $directorioUploads = __DIR__ . '/../uploads/avatars/';
        if (!file_exists($directorioUploads)) {
            mkdir($directorioUploads, 0777, true);
        }

        // Generar nombre único
        $extension = pathinfo($archivo['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid('avatar_') . '.' . $extension;
        $rutaDestino = $directorioUploads . $nombreArchivo;

        // Mover archivo
        if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
            return '../uploads/avatars/' . $nombreArchivo;
        }

        return 'Sin Avatar';
    }

    /**
     * Valida las credenciales de un usuario (para login)
     * @param string $correo
     * @param string $password
     * @return array|null Datos del usuario si es válido, null si no
     */
    public function ValidarCredenciales($correo, $password){
        $conexion = new Conexion();
        $conn = $conexion->Conectar();

        $sql = "SELECT * FROM usuario WHERE Correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['Contraseña'])) {
                $stmt->close();
                $conn->close();
                return [
                    'UsuarioID' => $row['UsuarioID'],
                    'Avatar' => $row['Avatar'],
                    'FechaNacimiento' => $row['FechaNacimiento'],
                    'Correo' => $row['Correo'],
                    'Telefono' => $row['Telefono'],
                    'Biografia' => $row['Biografia']
                ];
            }
        }

        $stmt->close();
        $conn->close();
        return null;
    }
}
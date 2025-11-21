<?php
// Script para verificar y crear la tabla usuarios

$host = 'localhost';
$user = 'AlumnosPV';
$password = 'Prog.V2025';
$database = 'parcial4';

// Conectar a MySQL
$conn = new mysqli($host, $user, $password);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

echo "✓ Conexión exitosa a MySQL<br><br>";

// Crear base de datos si no existe
$sql = "CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
if ($conn->query($sql) === TRUE) {
    echo "✓ Base de datos '$database' verificada/creada<br><br>";
} else {
    echo "✗ Error creando base de datos: " . $conn->error . "<br><br>";
}

// Seleccionar la base de datos
$conn->select_db($database);

// Verificar si la tabla existe
$result = $conn->query("SHOW TABLES LIKE 'usuarios'");
if ($result->num_rows > 0) {
    echo "✓ La tabla 'usuarios' ya existe<br><br>";
    
    // Mostrar estructura de la tabla
    echo "<strong>Estructura actual de la tabla:</strong><br>";
    $result = $conn->query("DESCRIBE usuarios");
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Contar usuarios
    $result = $conn->query("SELECT COUNT(*) as total FROM usuarios");
    $row = $result->fetch_assoc();
    echo "Total de usuarios en la tabla: " . $row['total'] . "<br>";
} else {
    echo "✗ La tabla 'usuarios' NO existe. Creándola...<br><br>";
    
    // Crear la tabla
    $sql = "CREATE TABLE `usuarios` (
      `UsuarioID` int(11) NOT NULL AUTO_INCREMENT,
      `Avatar` varchar(255) DEFAULT 'Sin Avatar',
      `FechaNacimiento` date DEFAULT '0000-00-00',
      `Correo` varchar(100) NOT NULL,
      `Contraseña` varchar(255) NOT NULL,
      `Telefono` varchar(20) DEFAULT 'Sin especificar',
      `Biografia` text DEFAULT 'Sin biografía',
      PRIMARY KEY (`UsuarioID`),
      UNIQUE KEY `Correo` (`Correo`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";
    
    if ($conn->query($sql) === TRUE) {
        echo "✓ Tabla 'usuarios' creada exitosamente<br><br>";
        echo "<strong>Estructura de la tabla creada:</strong><br>";
        $result = $conn->query("DESCRIBE usuarios");
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "✗ Error creando la tabla: " . $conn->error . "<br>";
    }
}

$conn->close();

echo "<br><br><a href='../pages/index2.php'>Volver al formulario</a>";
?>

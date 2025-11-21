-- Base de datos: parcial4
-- Tabla: usuarios

CREATE DATABASE IF NOT EXISTS `parcial4` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `parcial4`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `UsuarioID` int(11) NOT NULL AUTO_INCREMENT,
  `Avatar` varchar(255) DEFAULT 'Sin Avatar',
  `FechaNacimiento` date DEFAULT '0000-00-00',
  `Correo` varchar(100) NOT NULL,
  `Contraseña` varchar(255) NOT NULL,
  `Telefono` varchar(20) DEFAULT 'Sin especificar',
  `Biografia` text DEFAULT 'Sin biografía',
  PRIMARY KEY (`UsuarioID`),
  UNIQUE KEY `Correo` (`Correo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertar un usuario de ejemplo
INSERT INTO `usuarios` (`Avatar`, `FechaNacimiento`, `Correo`, `Contraseña`, `Telefono`, `Biografia`) 
VALUES 
('Sin Avatar', '1990-01-01', 'usuario@ejemplo.com', 'password123', '555-1234', 'Esta es una biografía de ejemplo');

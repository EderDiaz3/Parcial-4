-- Base de datos: parcial4
-- Tabla: usuarios
--Query de la creacion de la base de datos y la tabla usuario
/*

  CREATE DATABASE parcial4;
  use parcial4;


	CREATE TABLE `usuario` (
 `UsuarioID` int NOT NULL AUTO_INCREMENT,
 `Avatar` varchar(300) COLLATE utf8mb4_es_0900_ai_ci NOT NULL,
 `FechaNacimiento` date NOT NULL,
 `Correo` varchar(200) COLLATE utf8mb4_es_0900_ai_ci NOT NULL,
 `Contraseña` varchar(250) COLLATE utf8mb4_es_0900_ai_ci NOT NULL,
 `Telefono` varchar(12) COLLATE utf8mb4_es_0900_ai_ci DEFAULT NULL,
 `Biografia` varchar(500) COLLATE utf8mb4_es_0900_ai_ci DEFAULT NULL,
 PRIMARY KEY (`UsuarioID`),
 UNIQUE KEY `Correo` (`Correo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_es_0900_ai_ci
*/



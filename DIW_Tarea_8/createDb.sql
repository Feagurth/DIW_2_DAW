SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `diw`
--
CREATE DATABASE `diw` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `diw`;


/**
* Tabla correo
**/
CREATE TABLE IF NOT EXISTS `correo` (
    `id_correo` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `usuario` varchar(16) COLLATE utf8_spanish_ci NOT NULL,
    `pass` varchar(16) COLLATE utf8_spanish_ci NOT NULL,
    `servidor` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
    `puerto` int NOT NULL,
    `seguridad` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
    `descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_spanish_ci;


/**
* Tabla usuario
**/
CREATE TABLE IF NOT EXISTS `usuario` (
    `id_usuario` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
    `pass` varchar(32) COLLATE utf8_spanish_ci NOT NULL,
    `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_spanish_ci;


/**
* Tabla fichero
**/
CREATE TABLE IF NOT EXISTS `fichero` (
    `id_fichero` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre` VARCHAR(30) NOT NULL,
    `tamanyo` VARCHAR(30) NOT NULL,
    `tipo` VARCHAR(30) NOT NULL,
    `descripcion` VARCHAR(50) NOT NULL,
    `fichero` LONGBLOB NOT NULL
)  ENGINE=InnoDB;

/**
* Tabla grupo
**/
CREATE TABLE IF NOT EXISTS `grupo` (
    `id_grupo` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
    `descripcion` varchar(50) COLLATE utf8_spanish_ci NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_spanish_ci;


/**
* Tabla empleado
**/
CREATE TABLE IF NOT EXISTS `empleado` (
    `id_empleado` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `nombre` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
	`apellido` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
	`telefono` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
	`especialidad` varchar(50) COLLATE utf8_spanish_ci NOT NULL,
	`cargo` varchar(15) COLLATE utf8_spanish_ci NOT NULL,
	`direccion` varchar(75) COLLATE utf8_spanish_ci NOT NULL,
    `email` varchar(30) COLLATE utf8_spanish_ci NOT NULL
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_spanish_ci;


/**
* Tabla envio
**/
CREATE TABLE IF NOT EXISTS `envio` (
    `id_envio` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `fecha_envio` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
	`id_correo` int NOT NULL,
	`id_fichero` int NOT NULL,
	FOREIGN KEY (id_correo) REFERENCES correo(id_correo),
	FOREIGN KEY (id_fichero) REFERENCES fichero(id_fichero)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_spanish_ci;


/**
* Tabla grupo_fichero
**/
CREATE TABLE IF NOT EXISTS `grupo_fichero` (
    `id_grupo_fichero` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_grupo` int NOT NULL,
    `id_fichero` int NOT NULL,
	FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo),
	FOREIGN KEY (id_fichero) REFERENCES fichero(id_fichero)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_spanish_ci;


/**
* Tabla grupo_empleado
**/
CREATE TABLE IF NOT EXISTS `grupo_empleado` (
    `id_grupo_empleado` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_grupo` int NOT NULL,
    `id_empleado` int NOT NULL,
	FOREIGN KEY (id_grupo) REFERENCES grupo(id_grupo),
	FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_spanish_ci;

/**
* Tabla envio_empleado
**/
CREATE TABLE IF NOT EXISTS `envio_empleado` (
    `id_envio_empleado` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `id_envio` int NOT NULL,
    `id_empleado` int NOT NULL,
	FOREIGN KEY (id_envio) REFERENCES envio(id_envio),
	FOREIGN KEY (id_empleado) REFERENCES empleado(id_empleado)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE = utf8_spanish_ci;





INSERT INTO `usuario` (`user`, `pass`, `nombre`) VALUES
('diw', md5('diw'), 'Usuario diw');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- Creamos el usuario 
CREATE USER `diw`
IDENTIFIED BY 'diw';

CREATE USER 'diw'@'localhost' 
IDENTIFIED BY 'diw';

-- Asignamos permisos de la tabla al usuario dwes    
GRANT ALL ON `diw`.*
TO `diw`;
<?php
/**
 * Servidor donde está alojada la base de datos
 */
$serv = "localhost";

/**
 * Nombre de la base de datos
 */
$base = "diw";

/**
 * usuario de la base de datos
 */
$usu = "diw";

/**
 * Contraseña de la base de datos
 */
$pas = "diw";

/**
 * E-Mail del administrador del sitio
 */
$emailAdmin = "admin@dominio.es";

/**
 * Nombre del administrador del sitio
 */
$nameAdmin = "Admin";

/**
 * Variable que controla el tamaño máximo de los ficheros a enviar en el formulario
 * Su valor debe coincidir con el valor de post_max_size y upload_max_filesize 
 * en el archivo de configuración php.ini del servidor Apache y con el valor 
 * de max_allowed_packet del archivo de configuración my.ini de mysql
 */
$maxFileSize = 10485760; // 10Mb en bytes

$nombreUsuario;
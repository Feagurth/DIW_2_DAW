<?php

/*
 * Copyright (C) 2015 Luis Caberizo Gómez
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


require_once './configuracion.inc.php';


// Comprobamos que tenemos información de la id del fichero a visualizar
if (isset($_POST['id_fichero']) && $_POST['id_fichero'] !== 0) {

    // Creamos un bloque try-catch para la inicialización de la base 
    // de datos
    try {

        // Creamos una conexión a la base de datos especificando el host, 
        // la base de datos, el usuario y la contraseña
        $db = new PDO('mysql:host=' . $serv . ';dbname=' . $base, $usu, $pas);

        // Especificamos atributos para que en caso de error, salte una excepción
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Si se produce una excepción almacenamos el mensaje asociado
        $mensajeError = $e->getMessage();
    }

    // Comprobamos si ha ocurrido algún error en la conexión con la base de 
    // datos. De no ser así, seguimos con la carga de datos de la página
    if (!isset($mensajeError)) {

        // Recuperamos el id del documento a mostrar
        $id = $_POST['id_fichero'];

        // Preparamos una consulta para que la base de datos devuelva todos los 
        // datos del documento especificado
        $consulta = $db->query("select * from fichero where id_fichero = $id");

        // Realizamos la consulta
        $fila = $consulta->fetchAll(PDO::FETCH_ASSOC);

        // Comprobamos que la consulta trae datos
        if (count($fila) == 1) {

            // Usamos header para asignar los valores del fichero de la base de 
            // datos correspondientes y permitir al navegador mostrar la información
            header("Content-length: " . $fila[0]['tamanyo']);
            header("Content-type: " . $fila[0]['tipo']);

            // Comprobamos el tipo del fichero a visualizar y si es un archivo .doc, .rtf u .odt, lo descargamos
            if ($fila[0]['tipo'] === "application/octet-stream") {
                header("Content-Disposition: attachment; filename=" . $fila[0]['nombre']);
            }

            // Finalmente mostrarmos el fichero
            echo $fila[0]['fichero'];
        }
    }
}

    
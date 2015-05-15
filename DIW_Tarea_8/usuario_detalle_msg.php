<?php

/*
 * Copyright (C) 2015 Super
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

// Instanciamos los ficheros necesarios
require_once './funciones.php';
require_once './configuracion.inc.php';
require_once './objetos/Usuario.php';

try {
    // Creamos un objeto Usuario
    $usuario = new Usuario(array("id_usuario" => "", "user" => "", "pass" => "", "nombre" => ""));

    // Recuperamos el valor del modo
    $modo = $_POST['modo'];

    // Comprobamos el modo. Si es alta, cambiamos el id_usuario a 0 para poder 
    // hacer una insercción, si no, cogemos el que se nos haya pasado
    $id_usuario = $modo === "A" ? "0" : $_POST['id_usuario'];

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Comprobamos el tipo de petición
    switch ($modo) {

        // Si la petición es un alta
        case "A": {

                // Creamos un objeto con la información que tenemos
                $usuario->setId_usuario($id_usuario);
                $usuario->setUser($_POST['user']);
                $usuario->setPass($_POST['pass']);
                $usuario->setNombre($_POST['nombre']);

                // Realizamos la insercción pasándo como 
                // parámetro el objeto Usuario, dejando la gestión de 
                // errores de la insercción a las excepciones que se 
                // puedan lanzar. El id resultante de la insercción, lo 
                // asignamos a la variable $id_usuario
                $id_usuario = $db->insertarUsuario($usuario);

                // Asignamos al objeto usuario el Id_usuario que 
                // hemos recibido de la insercción
                $usuario->setId_usuario($id_usuario);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto usuario serializado y codificado en formato JSON
                echo json_encode($usuario);

                break;
            }
        // Si la petición es una modificación
        case "M": {

                // Asignamos la informacón que se encuentra en post
                $usuario->setId_usuario($id_usuario);
                $usuario->setUser($_POST['user']);
                $usuario->setPass($_POST['pass']);
                $usuario->setNombre($_POST['nombre']);

                // Realizamos la modificación pasándo como parámetro el 
                // objeto Usuario, dejando la gestión de errores de la 
                // modificación a las excepciones que se puedan lanzar
                $db->modificarUsuario($usuario);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto usuario serializado y codificado en formato JSON
                echo json_encode($usuario);

                break;
            }
    }
} catch (Exception $ex) {
    // Recuperamos el mensaje de error
    $error = $ex->getMessage();

    // Especificamos las cabeceras para que devuelvan en formato JSON
    header('Content-Type: application/json');

    // Devolvemos el error
    echo $error;
}
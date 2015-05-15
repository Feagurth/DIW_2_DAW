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

//Iniciamos la sesion
session_start();

// Instanciamos los ficheros necesarios
require_once './funciones.php';
require_once './configuracion.inc.php';
require_once './objetos/Email.php';

try {
    // Inicializamos variables
    $error = "";


    // Creamos un objeto Email
    $email = new Email(array("id_email" => "", "usuario" => "", "pass" => "", "servidor" => "", "puerto" => "", "seguridad" => "", "autentificacion" => "", "descripcion" => ""));

    // Recuperamos los valores del modo 
    $modo = $_POST['modo'];

    // Comprobamos el modo. Si es alta, cambiamos el id_email a 0 para poder 
    // hacer una insercción, si no, cogemos el que se nos haya pasado
    $id_email = $modo === "A" ? "0" : $_POST['id_email'];

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Comprobamos el tipo de petición
    switch ($modo) {
        // Si la petición es un alta
        case "A": {

                // Si es así, asignamos la informacón introducida en los inputs 
                // y que se encuentra en post
                $email->setId_email($id_email);
                $email->setUsuario($_POST['usuario']);
                $email->setPass($_POST['pass']);
                $email->setServidor($_POST['servidor']);
                $email->setPuerto($_POST['puerto']);
                $email->setSeguridad($_POST['seguridad']);
                $email->setAutentificacion((isset($_POST['autentificacion']) ? "1" : "0"));
                $email->setDescripcion($_POST['descripcion']);


                // Realizamos la insercción pasándo como 
                // parámetro el objeto Email, dejando la gestión de 
                // errores de la insercción a las excepciones que se 
                // puedan lanzar. El id resultante de la insercción, lo 
                // asignamos a la variable $id_email
                $id_email = $db->insertarEmail($email);

                // Asignamos al objeto Email el Id_email que 
                // hemos recibido de la insercción
                $email->setId_email($id_email);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto email serializado y codificado en formato JSON
                echo json_encode($email);

                break;
            }


        // Si la petición es una modificación
        case "M": {

                // Asignamos la informacón introducida en los inputs 
                // y que se encuentra en post
                $email->setId_email($id_email);
                $email->setUsuario($_POST['usuario']);
                $email->setPass($_POST['pass']);
                $email->setServidor($_POST['servidor']);
                $email->setPuerto($_POST['puerto']);
                $email->setSeguridad($_POST['seguridad']);
                $email->setAutentificacion((isset($_POST['autentificacion']) ? "1" : "0"));
                $email->setDescripcion($_POST['descripcion']);

                // Realizamos la modificación pasándo como parámetro el 
                // objeto Email, dejando la gestión de errores de la 
                // modificación a las excepciones que se puedan lanzar
                $db->modificarEmail($email);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto email serializado y codificado en formato JSON
                echo json_encode($email);

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

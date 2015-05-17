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
require_once './objetos/Empleado.php';

try {
    // Inicializamos variables
    $error = "";

    // Creamos un objeto Empleado
    $empleado = new Empleado(array("id_empleado" => "", "nombre" => "", "apellido" => "", "telefono" => "", "especialidad" => "", "cargo" => "", "direccion" => "", "email" => ""));

    // Recuperamos los valores del modo 
    $modo = $_POST['modo'];

    // Comprobamos el modo. Si es alta, cambiamos el id_empleado a 0 para poder 
    // hacer una insercción, si no, cogemos el que se nos haya pasado
    $id_empleado = $modo === "A" ? "0" : $_POST['id_empleado'];

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Comprobamos el modo de la página
    switch ($modo) {

        // Si la petición es un alta
        case "A": {

                // Creamos un objeto con la información que tenemos
                $empleado->setId_empleado($id_empleado);
                $empleado->setNombre($_POST['nombre']);
                $empleado->setApellido($_POST['apellido']);
                $empleado->setTelefono($_POST['telefono']);
                $empleado->setEspecialidad($_POST['especialidad']);
                $empleado->setCargo($_POST['cargo']);
                $empleado->setDireccion($_POST['direccion']);
                $empleado->setEmail($_POST['email']);

                // Realizamos la insercción pasándo como 
                // parámetro el objeto Empleado, dejando la gestión de 
                // errores de la insercción a las excepciones que se 
                // puedan lanzar. El id resultante de la insercción, lo 
                // asignamos a la variable $id_empleado
                $id_empleado = $db->insertarEmpleado($empleado);

                // Asignamos al objeto empleado el id_empleado que 
                // hemos recibido de la insercción
                $empleado->setId_empleado($id_empleado);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto empleado serializado y codificado en formato JSON
                echo json_encode($empleado);


                break;
            }

        // Si la petición es una modificación
        case "M": {

                // Asignamos la informacón que se encuentra en post
                $empleado->setId_empleado($id_empleado);
                $empleado->setNombre($_POST['nombre']);
                $empleado->setApellido($_POST['apellido']);
                $empleado->setTelefono($_POST['telefono']);
                $empleado->setEspecialidad($_POST['especialidad']);
                $empleado->setCargo($_POST['cargo']);
                $empleado->setDireccion($_POST['direccion']);
                $empleado->setEmail($_POST['email']);


                // Realizamos la modificación pasándo como parámetro el 
                // objeto Empleado, dejando la gestión de errores de la 
                // modificación a las excepciones  que se puedan lanzar
                $db->modificarEmpleado($empleado);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto empleado serializado y codificado en formato JSON
                echo json_encode($empleado);

                break;
            }

        // Si la petición es una eliminación
        case "E": {

                // Eliminamos el empleado usando la función adecuada y 
                // pasándo su id como paráemtro
                $db->eliminarEmpleado($id_empleado);

                // Devolvemos true si no ha saltado ningún error
                echo true;

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
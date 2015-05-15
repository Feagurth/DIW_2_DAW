<?php

/*
 * Copyright (C) 2015 Luis Cabrerizo Gómez
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
require_once './objetos/Fichero.php';

try {
    // Inicializamos variables
    $error = "";

    // Creamos un objeto Fichero
    $fichero = new Fichero(array("id_fichero" => "", "nombre" => "", "tamanyo" => "", "tipo" => "", "descripcion" => "", "fichero" => ""));

    // Recuperamos los valores del modo 
    $modo = $_POST['modo'];

    // Comprobamos el modo. Si es alta, cambiamos el id_fichero a 0 para poder 
    // hacer una insercción, si no, cogemos el que se nos haya pasado
    $id_fichero = $modo === "A" ? "0" : $_POST['id_fichero'];

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Comprobamos el tipo de petición
    switch ($modo) {
        // Si la petición es un alta
        case "A": {
                // Validamos que se ha enviado un fichero durante el post
                if ($_FILES['addfile']['error'][0] === 0) {

                    // Creamos el objeto fichero con los datos enviados
                    crearObjetosInserccionFichero($fichero, $id_fichero);

                    // Realizamos la insercción pasándo como 
                    // parámetro el objeto Fichero, dejando la gestión de 
                    // errores de la insercción a las excepciones que se 
                    // puedan lanzar. El id resultante de la insercción, lo 
                    // asignamos a la variable $id_fichero
                    $id_fichero = $db->insertarFichero($fichero);

                    // Asignamos al objeto Ficheor el id_fichero que 
                    // hemos recibido de la insercción                    
                    $fichero->setId_fichero($id_fichero);

                    // Especificamos las cabeceras para que devuelvan en formato JSON
                    header('Content-Type: application/json');

                    // Devolvemos el objeto fichero serializado y codificado en formato JSON
                    echo json_encode($fichero);
                } else {
                    // Comprobamos el tipo de error que ha generado la subida 
                    // del fichero y mostramos un mensaje en consecuencia
                    switch ($_FILES['addfile']['error'][0]) {
                        case 1: {
                                $error = "El archivo subido excede la directiva upload_max_filesize en php.ini." . PHP_EOL .
                                        "Pongase en contacto con el administrador: " . $emailAdmin;
                                break;
                            }
                        case 2: {
                                $error = "El archivo subido excede la directiva MAX_FILE_SIZE que fue especificada en el formulario HTML." . PHP_EOL .
                                        "Pongase en contacto con el administrador: " . $emailAdmin;
                                break;
                            }
                        case 3: {
                                $error = "El archivo subido fue sólo parcialmente cargado." . PHP_EOL .
                                        "Pongase en contacto con el administrador: " . $emailAdmin;
                                break;
                            }
                        case 4: {
                                $error = "Ningún archivo fue subido." . PHP_EOL .
                                        "Pongase en contacto con el administrador: " . $emailAdmin;
                                break;
                            }
                        case 6: {
                                $error = "Falta la carpeta temporal." . PHP_EOL .
                                        "Pongase en contacto con el administrador: " . $emailAdmin;
                                break;
                            }
                        case 7: {
                                $error = "No se pudo escribir el archivo en el disco." . PHP_EOL .
                                        "Pongase en contacto con el administrador: " . $emailAdmin;
                                break;
                            }
                        case 8: {
                                $error = "Una extensión de PHP detuvo la carga de archivos." . PHP_EOL .
                                        "Pongase en contacto con el administrador: " . $emailAdmin;
                                break;
                            }
                        default: {
                                $error = "Se ha producido un error no especificado durante la carga del archivo." . PHP_EOL .
                                        "Pongase en contacto con el administrador: " . $emailAdmin;
                                break;
                            }
                    }
                }

                break;
            }

        // Si la petición es una modificación
        case "M": {

                // Asignamos la informacón introducida en los inputs 
                // y que se encuentra en post
                $fichero->setId_fichero($id_fichero);
                $fichero->setNombre($_POST['nombre']);
                $fichero->setTamanyo($_POST['tamaño']);
                $fichero->setTipo($_POST['tipo']);
                $fichero->setDescripcion($_POST['descripcion']);
                $fichero->setFichero("");


                // Realizamos la modificación  pasándo como 
                // parámetro el objeto  Fichero, dejando la 
                // gestión de errores de la modificación a las 
                // excepciones  que se puedan lanzar
                $db->modificarFichero($fichero);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto email serializado y codificado en formato JSON
                echo json_encode($fichero);

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
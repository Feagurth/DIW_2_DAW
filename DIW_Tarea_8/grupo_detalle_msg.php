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
require_once './objetos/Grupo.php';
require_once './objetos/GrupoEmpleado.php';

try {


    // Inicializamos variables
    $error = "";

    // Creamos un objeto Grupo
    $grupo = new Grupo(array("id_grupo" => "", "nombre" => "", "descripcion" => ""));

    // Recuperamos el valor del modo
    $modo = $_POST['modo'];

    // Comprobamos el modo. Si es alta, cambiamos el id_grupo a 0 para poder 
    // hacer una insercción, si no, cogemos el que se nos haya pasado
    $id_grupo = $modo === "A" ? "0" : $_POST['id_grupo'];

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Comprobamos el tipo de petición
    switch ($modo) {

        case "GT": {
                // Recuperamos todos los empleados
                $empleados = $db->listarEmpleados("", "");

                // Recuperamos las relaciones de los empleados con el grupo que 
                // usaremos más adelante con la función comprobarRelaccionEmpleadoGrupo 
                // para marcar los checkboxes de los empleados que formen parte 
                // del grupo
                $grupoempleado = $db->listarRelacionesGrupoEmpleados($id_grupo);

                // Creamos un div y un formalario que contendrán el listado
                $cadena = '<div class="listadoSel">';

                $cadena .='<h2>Empleados integrantes del grupo</h2>';
                
                $cadena .='<form action="grupo_detalle.php" method="post">';

                // A continuación definimos la estructura de la tabla y su cabecera
                $cadena .='<table>';
                $cadena .='<thead>';
                $cadena .='<tr>';
                $cadena .='<td class="listadoCabecera">Nombre</td>';
                $cadena .='<td class="listadoCabecera">Apellido</td>';
                $cadena .='<td class="listadoCabecera">Telefono</td>';
                $cadena .='<td class="listadoCabecera">Especialidad</td>';
                $cadena .='<td class="listadoCabecera">Cargo</td>';
                $cadena .='<td class="listadoCabecera">Dirección</td>';
                $cadena .='<td class="listadoCabecera">E-Mail</td>';
                $cadena .='<td>Selección</td>';
                $cadena .='</tr>';
                $cadena .='</thead>';
                $cadena .='<tbody>';


                // Verificamos si tenemos algún tipo de error
                if ($error === "") {
                    // Inicializamos un contador para asignar los estilos a cada linea
                    $i = 0;

                    // Recorremos cada uno de los registros que hemos recuperado 
                    foreach ($empleados as $empleado) {

                        // Si el contador es un número par, le daremos un estilo y si 
                        // es impar le daremos otro
                        if ($i % 2 === 0) {
                            $cadena .='<tr class="pijama1">';
                        } else {
                            $cadena .='<tr class="pijama2">';
                        }

                        // Imprimimos celda con los valores recuperados de cada objeto 
                        // empleado que hay en los registros recuperados
                        $cadena .='<td title="' . $empleado->getNombre() . '">' . textoElipsis($empleado->getNombre(), 15) . '</td>';
                        $cadena .='<td title="' . $empleado->getApellido() . '">' . textoElipsis($empleado->getApellido(), 15) . '</td>';
                        $cadena .='<td title="' . $empleado->getTelefono() . '">' . textoElipsis($empleado->getTelefono(), 15) . '</td>';
                        $cadena .='<td title="' . $empleado->getEspecialidad() . '">' . textoElipsis($empleado->getEspecialidad(), 15) . '</td>';
                        $cadena .='<td title="' . $empleado->getCargo() . '">' . textoElipsis($empleado->getCargo(), 15) . '</td>';
                        $cadena .='<td title="' . $empleado->getDireccion() . '">' . textoElipsis($empleado->getDireccion(), 15) . '</td>';
                        $cadena .='<td title="' . $empleado->getEmail() . '">' . textoElipsis($empleado->getEmail(), 15) . '</td>';

                        // Añadimos una última fila con un botón un checkbox para marcar una relación con el grupo actual
                        $cadena .='<td>';
                        $cadena .='<input type="checkbox" name="seleccionadoEmpleado[]" title="Haga click para agregar el empleado al grupo" tabindex="20" ';
                        if (comprobarRelaccionEmpleadoGrupo($empleado->getId_empleado(), $grupoempleado)) {
                            $cadena .='checked="checked"';
                        }
                        $cadena .=" value='" . $empleado->getId_empleado() . "' />";
                        $cadena .='</td>';
                        $cadena .='</tr>';

                        // Incrementamos el contador
                        $i++;
                    }
                }

                // Cerramos el cuerpo de la tabla
                $cadena .='</tbody>';

                // Cerramos la tabla
                $cadena .='</table>';

                // Creamos dos campos ocultos que enviarán el modo de la página cuando 
                // se pulse el botón de actualizar las relaciones de los empleados y el 
                // identificador del grupo en el que estamos actualmente para que se 
                // refresquen sus datos
                $cadena .='<input class="oculto" name="modo" type="hidden" value="AE" />';
                $cadena .='<input class="oculto" name="id_grupo" type="hidden" value="' . $id_grupo . '" />';

                // Creamos el botón de actualizar las relaciones con los empleados
                $cadena .='<input type="submit" id="actualizar_empleados" tabindex="21" value="Actualizar Relaciones Empleados" title="Pulse para actualizar la relación de los empleados con el grupo" />';

                // Y finalmente cerramos el formulario
                $cadena .='</form>';
                $cadena .='</div>';

                // Devolvemos la cadena con la estrcutra de la tabla de relaciones
                echo $cadena;

                break;
            }


        // Si la petición es un alta de relaciones de empleados
        case "AE": {

                // Decodificamos el array desde el formato Json
                $empleadosSel = json_decode($_POST['seleccionadoEmpleado']);

                // Comprobamos el tamaño del array
                if (sizeof($empleadosSel) > 0) {
                    // Si tiene al menos un id de empleado seleccionado, insertamos
                    $db->insertarRelacionesGrupoEmpleado($id_grupo, $empleadosSel);
                } else {

                    // Si no tiene ningun id empleado, eliminamos todas las relaciones 
                    // que pueda tener el grupo
                    $db->eliminarRelacionesGrupoEmpleado($id_grupo);
                }

                break;
            }


        // Si la petición es un alta
        case "A": {

                // Creamos un objeto con la información que tenemos
                $grupo->setId_grupo($id_grupo);
                $grupo->setNombre($_POST['nombre']);
                $grupo->setDescripcion($_POST['descripcion']);



                // Realizamos la insercción pasándo como 
                // parámetro el objeto Grupo, dejando la gestión de 
                // errores de la insercción a las excepciones que se 
                // puedan lanzar. El id resultante de la insercción, lo 
                // asignamos a la variable $id_grupo
                $id_grupo = $db->insertarGrupo($grupo);

                // Asignamos al objeto grupo el id_grupo que 
                // hemos recibido de la insercción
                $grupo->setId_grupo($id_grupo);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto grupo serializado y codificado en formato JSON
                echo json_encode($grupo);

                break;
            }


        // Si la petición es una modificación
        case "M": {

                // Asignamos la informacón introducida en los inputs 
                // y que se encuentra en post
                $grupo->setId_grupo($id_grupo);
                $grupo->setNombre($_POST['nombre']);
                $grupo->setDescripcion($_POST['descripcion']);



                // Realizamos la modificación 
                // pasándo como parámetro el objeto Grupo, dejando 
                // la gestión de errores de la modificación a las excepciones 
                // que se puedan lanzar
                $db->modificarGrupo($grupo);

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto grupo serializado y codificado en formato JSON
                echo json_encode($grupo);

                break;
            }

        case "E": {
                // Eliminamos el grupo usando la función adecuada y 
                // pasándo su id como paráemtro
                $db->eliminarGrupo($id_grupo);

                // Devolvemos true si no ha saltado ningún error
                echo true;
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
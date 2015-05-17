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
require_once './objetos/Usuario.php';
require_once './objetos/Grupo.php';
require_once './objetos/Fichero.php';
require_once './objetos/Email.php ';
require_once './objetos/Envio.php';


// Recuperamos los valores del modo de visión de la página   
$modo = $_POST['modo'];

// Comprobamos el modo. Si es alta, cambiamos el id_envio a 0 para poder 
// hacer una insercción, si no, cogemos el que se nos haya pasado
$id_envio = $modo === "A" ? "0" : $_POST['id_envio'];


// Recuperamos el tipo de petición
$peticion = $_POST['peticion'];


// Inicializamos la variable de error    
$error = "";

try {

    // Creamos una isntacia de la base de datos
    $db = new DB();

    // Comprobamos el modo de la página
    switch ($peticion) {

        // Si la petición es un alta
        case "A": {

                // Recupermaos los datos de POST
                $grupossel = isset($_POST['gruposel']) ? json_decode($_POST['gruposel']) : NULL;
                $ficherossel = isset($_POST['ficherosel']) ? json_decode($_POST['ficherosel']) : NULL;
                $id_email = isset($_POST['id_email']) ? $_POST['id_email'] : 0;


                // Insertamos el envío en la base de datos
                $id_envio = $db->insertarEnvio($grupossel, $ficherossel, $id_email);

                // Creamos un nuevo objeto envío pasándole el identificador
                $envio = new Envio($id_envio);

                // Recuperamos los grupos del objeto envío
                $grupos = $envio->getGrupo();

                // Recuperamos los ficheros del objeto envío
                $ficheros = $envio->getFichero();

                // Especificamos las cabeceras para que devuelvan en formato JSON
                header('Content-Type: application/json');

                // Devolvemos el objeto envio serializado y codificado en formato JSON
                echo json_encode($envio);

                break;
            }
        case "GT": {

                $cadena = "";

                $cadena .= '<div class="tablaanidada left">';

                // Mostramos un encabezado u otro dependiendo del modo en el que se encuentre la página
                if ($modo === "A") {

                    // Recuperamos los grupos existentes
                    $grupos = $db->listarGrupos("", "");

                    // Recuperamos los ficheros existentes
                    $ficheros = $db->listarFicheros("", "");



                    $cadena .= "<p>Seleccione un grupo de usuario para realizar el envío</p>";
                } else {

                    // Creamos un formulario vación al principio de la tabla, 
                    // para permitir la creación del resto de formularios que 
                    // mostrarán despues los iconos de detalle
                    $cadena .= "<form action='grupo_detalle.php' method='post'>";
                    $cadena .= "</form>";


                    $cadena .= "<p>El envío se realizo al siguiente grupo, conteniendo los empleados que se muestran</p>";

                    // Creamos un nuevo objeto envío pasándole el identificador
                    $envio = new Envio($id_envio);


                    // Recuperamos los grupos del objeto envío
                    $grupos = $envio->getGrupo();

                    // Recuperamos los ficheros del objeto envío
                    $ficheros = $envio->getFichero();
                }

                $cadena .= "<table>";


                // Iteramos por todos los grupos
                foreach ($grupos as $grupo) {

                    // Si estamos en modo alta, mostramos toods los empleados en los grupos
                    if ($modo === "A") {
                        $empleadosEnGrupos = $db->listarEmpleadosEnGrupo($grupo->getId_grupo());
                    } else {
                        // Si no, recuperamos los empleados del objeto envío, 
                        // donde solo están aquellos a los que se les envió el fichero
                        $empleadosEnGrupos = $envio->getEmpleados();
                    }

                    $cadena .= "<tr class='tablaCabecera'>";
                    $cadena .= "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                    $cadena .= textoElipsis($grupo->getNombre(), 30);
                    $cadena .= "</td>";
                    $cadena .= "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                    $cadena .= "</td>";
                    $cadena .= "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                    $cadena .= "</td>";
                    $cadena .= "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                    $cadena .= "</td>";

                    // Si el modo es alta, mostramos checkboxex para marcar, 
                    // en caso contrario mostramos el icono de detalle
                    if ($modo === "A") {
                        $cadena .= "<td title='Haga click para seleccionar el grupo'>";
                        $cadena .= '<input tabindex="10" type="checkbox" name="gruposel[]" value="' . $grupo->getId_grupo() . '" id="gruposel' . $grupo->getId_grupo() . '"/>';
                        $cadena .= '<label class="oculto" for="gruposel' . $grupo->getId_grupo() . '">';
                        $cadena .= "Marque para seleccionar";
                        $cadena .= '</label>';
                    } else {
                        $cadena .= "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                        $cadena .= '</td>';
                        $cadena .= '<td>';
                        $cadena .= "<form action='grupo_detalle.php' method='post' >";
                        $cadena .= "<button tabindex='10' name='button' value='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles del grupo' /></button>";
                        $cadena .= "<input class='oculto' name='id_grupo' type='hidden' value='" . $grupo->getId_grupo() . "' />";
                        $cadena .= "<input class='oculto' name='modo' type='hidden' value='V' />";
                        $cadena .= "</form>";
                    }
                    $cadena .= "</td>";
                    $cadena .= "</tr>";

                    $i = 0;

                    // Iteramos por todos los empleados
                    foreach ($empleadosEnGrupos as $empleado) {


                        // Si el contador es un número par, le daremos un estilo y si 
                        // es impar le daremos otro
                        if ($i % 2 === 0) {
                            $cadena .= '<tr class="pijama1">';
                        } else {
                            $cadena .= '<tr class="pijama2">';
                        }

                        $cadena .= "<td title='" . textoElipsis($empleado->getNombre(), 30) . "' >";
                        $cadena .= textoElipsis($empleado->getNombre(), 30);
                        $cadena .= "</td>";
                        $cadena .= "<td title='" . textoElipsis($empleado->getApellido(), 30) . "' >";
                        $cadena .= textoElipsis($empleado->getApellido(), 30);
                        $cadena .= "</td>";
                        $cadena .= "<td title='" . textoElipsis($empleado->getEmail(), 30) . "' >";
                        $cadena .= textoElipsis($empleado->getEmail(), 30);
                        $cadena .= "</td>";
                        $cadena .= "<td title='" . textoElipsis($empleado->getEspecialidad(), 50) . "' >";
                        $cadena .= textoElipsis($empleado->getEspecialidad(), 50);
                        $cadena .= "</td>";
                        $cadena .= "<td title='" . textoElipsis($empleado->getCargo(), 15) . "' >";
                        $cadena .= textoElipsis($empleado->getCargo(), 15);
                        $cadena .= "</td>";

                        // Si el modo es visor mostramos el icono de detalle                                    
                        if ($modo === "V") {
                            $cadena .= '<td>';
                            $cadena .= "<form action='empleado_detalle.php' method='post' >";
                            $cadena .= "<button tabindex='11' name='button' value='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles del empleado' /></button>";
                            $cadena .= "<input class='oculto' name='id_empleado' type='hidden' value='" . $empleado->getId_empleado() . "' />";
                            $cadena .= "<input class='oculto' name='modo' type='hidden' value='V' />";
                            $cadena .= "</form>";
                            $cadena .= '</td>';
                        }

                        $cadena .= "</tr>";

                        // Incrementamos el contador
                        $i++;
                    }
                }

                $cadena .= '</table>';
                $cadena .= '</div>';
                $cadena .= '<div class="tablaanidada right">';

                // Mostramos un encabezado u otro dependiendo del modo en el que se encuentre la página
                if ($modo === "A") {
                    $cadena .= "<p>Seleccione los archivos a enviar</p>";
                } else {
                    $cadena .= "<p>Se envió el siguiente fichero</p>";
                }

                $cadena .= "<table>";

                // Iteramos por todos los ficheros recuperados
                foreach ($ficheros as $fichero) {

                    $cadena .= "<tr class='tablaCabecera'>";
                    $cadena .= "<td title='" . textoElipsis($fichero->getDescripcion(), 50) . "'>";
                    $cadena .= textoElipsis($fichero->getDescripcion(), 50);
                    $cadena .= "</td>";
                    $cadena .= "<td title='" . textoElipsis($fichero->getDescripcion(), 50) . "'>";
                    $cadena .= "</td>";

                    // Si el modo es alta, mostramos checkboxex para marcar, 
                    // en caso contrario mostramos el icono de detalle                                
                    if ($modo === "A") {
                        $cadena .= "<td title='Haga click para seleccionar el fichero'>";
                        $cadena .= '<input tabindex="12" type="checkbox" name="ficherosel[]" value="' . $fichero->getId_fichero() . '" id="ficherosel' . $fichero->getId_fichero() . '"/>';
                        $cadena .= '<label class="oculto" for="ficherosel' . $fichero->getId_fichero() . '">';
                        $cadena .= "Marque para seleccionar";
                        $cadena .= '</label>';
                    } else {
                        $cadena .= '<td>';
                        $cadena .= "<form action='fichero_detalle.php' method='post' >";
                        $cadena .= "<button tabindex='12' name='button' value='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles del fichero' /></button>";
                        $cadena .= "<input class='oculto' name='id_fichero' type='hidden' value='" . $fichero->getId_fichero() . "' />";
                        $cadena .= "<input class='oculto' name='modo' type='hidden' value='V' />";
                        $cadena .= "</form>";
                    }
                    $cadena .= "</td>";
                    $cadena .= "</tr>";


                    $cadena .= '<tr class="pijama1">';

                    $cadena .= "<td title='" . textoElipsis($fichero->getNombre(), 50) . "' >";
                    $cadena .= textoElipsis($fichero->getNombre(), 50);
                    $cadena .= "</td>";
                    $cadena .= "<td title='" . textoElipsis($fichero->getTipo(), 30) . "' >";
                    $cadena .= textoElipsis($fichero->getTipo(), 30);
                    $cadena .= "</td>";
                    $cadena .= "<td title='" . textoElipsis($fichero->getTamanyo(), 30) . "' >";
                    $cadena .= textoElipsis($fichero->getTamanyo(), 30);
                    $cadena .= "</td>";
                    $cadena .= "</tr>";
                }

                $cadena .= '</table>';
                $cadena .= '</div>';

                if ($modo === "A") {
                    $cadena .= '<div id="especial">';

                    $cadena .= '<input class="especialbtn" tabindex="13" name="boton" id="aceptar" type="submit" value="Aceptar" title="Pulse para confirmar las modificaciones">';

                    $cadena .= '<input class="especialbtn" tabindex="14" name="boton" id="cancelar" type="submit" value="Cancelar" title="Pulse para cancelar las modificaciones">';

                    // Creamos dos objetos ocultos para reenviar la información del modo de la página y del identificador de envio
                    $cadena .= "<input class='oculto' name='id_envio' type='hidden' value='" . $id_envio . "' />";
                    $cadena .= "<input class='oculto' name='modo' type='hidden' value='" . $modo . "' />";

                    $cadena .= '</div>';
                }

                echo $cadena;

                break;
            }
    }
} catch (Exception $ex) {
    $error = $ex->getMessage();

    echo $error;
}
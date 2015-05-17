<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Copyright (C) 2015 Luis Cabrerizo Gómez
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
-->

<?php
//Iniciamos la sesion
session_start();

// Instanciamos los ficheros necesarios
require_once './funciones.php';
require_once './configuracion.inc.php';
require_once './objetos/Usuario.php';
require_once './objetos/Grupo.php';
require_once './objetos/Fichero.php';
require_once './objetos/Email.php ';
require_once './objetos/Envio.php';


// Recupermaos el nombre de usuario
$nombreUsuario = $_SESSION['nombreUsuario'];

// Recuperamos los valores del modo de visión de la página   
$modo = $_POST['modo'];

// Recuperamos los valores de grupo de sesión si están ahí o en su defecto de post
$id_envio = isset($_SESSION['id_envio']) ? $_SESSION['id_envio'] : $_POST['id_envio'];


// Inicializamos la variable de error    
$error = "";

// Validamos el usuario
validarUsuario($_SESSION['user'], $_SESSION['pass']);

try {



// Creamos una isntacia de la base de datos
    $db = new DB();

// Listamos los emails
    $emails = $db->listarEmails("", "");

// Comprobamos el modo de la página
    switch ($modo) {

        case "A": {

                // Recuperamos los grupos existentes
                $grupos = $db->listarGrupos("", "");

                // Recuperamos los ficheros existentes
                $ficheros = $db->listarFicheros("", "");

                // Verificamos si en la información de post tenemos la información 
                // de los botones de confirmanción o de cancelación pulsados.
                // Verificamos si la información del botón es la de cancelar.
                if (isset($_POST['boton']) && $_POST['boton'] === "Cancelar") {
                    // Si cancelamos el añadir un grupo, pasamos a sesión el 
                    // indice como valor para la página index.php para que cargue 
                    // la plantilla de grupos
                    $_SESSION['indice'] = 4;

                    // Navegamos a la pagina index.php
                    header("location:index.php");
                }

                // Comprobamos si la información del botón es la de aceptar
                if (isset($_POST['boton']) && $_POST['boton'] === "Aceptar") {
                    // Comprobamos que la sesión es correcta
                    if (comprobarTokenSesion()) {

                        $grupossel = isset($_POST['gruposel']) ? $_POST['gruposel'] : NULL;
                        $ficherossel = isset($_POST['ficherosel']) ? $_POST['ficherosel'] : NULL;
                        $id_email = isset($_POST['email']) ? $_POST['email'] : 0;

                        // Validamos los datos introducidos
                        $validacion = validarDatosEnvio($grupossel, $ficherossel, $id_email);

                        // Comprobamos si hay mensaje de error en la validación
                        if ($validacion === "") {


                            $id_envio = $db->insertarEnvio($grupossel, $ficherossel, $id_email);

                            // Asignamos tambien el id_envio a la sesión para 
                            // prevenir inserciones extras por refrescos de página
                            $_SESSION['id_envio'] = $id_envio;

                            // Cambiamos el modo a visor
                            $modo = "V";

                            // Creamos un nuevo objeto envío pasándole el identificador
                            $envio = new Envio($id_envio);

                            // Recuperamos los grupos del objeto envío
                            $grupos = $envio->getGrupo();

                            // Recuperamos los ficheros del objeto envío
                            $ficheros = $envio->getFichero();
                        } else {
                            // Si hay error de validación, copiamos su valor a 
                            // la variable $error
                            $error = $validacion;
                        }
                    } else {
                        // Si la sesión no es válida, pasamos a sesión el 
                        // indice como valor para la página index.php para que cargue 
                        // la plantilla de grupos
                        $_SESSION['indice'] = 4;

                        $modo = "V";
                    }
                }

                break;
            }

        case "V";
            {

                // Creamos un nuevo objeto envío pasándole el identificador
                $envio = new Envio($id_envio);

                // Recuperamos los grupos del objeto envío
                $grupos = $envio->getGrupo();

                // Recuperamos los ficheros del objeto envío
                $ficheros = $envio->getFichero();
            }
    }
} catch (Exception $ex) {
    $error = $ex->getMessage();
}
?>


<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
        <title>Detalle Usuario</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type = "text/css" rel = "stylesheet" href = "./estilos.css"/>
        <script type="text/javascript" src="HTTP://code.jquery.com/jquery-latest.js"></script>        
        <script type="text/javascript" src="scripts/envio_detalle.js"></script>
        <script type="text/javascript" src="scripts/funciones.js"></script>                
    </head>
    <body>
        <div class="cabecera" id="index">
            <h1>Gestión Documental</h1>
        </div>
        <div>
            <?php
            include './menu.php';
            if (!isset($_SESSION['token'])) {
                $_SESSION['token'] = generarTokenSesion();
            }
            ?>

        </div>
        <div id="cuerpo">      
            <div id="botonera">
                <h2>Detalle de envíos</h2>
                <form id="añadir" action='envio_detalle.php' method='post' >
                    <input type='submit' tabindex="8" value='Añadir Envio' title="Pulse para añadir un envio nuevo"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='añadir' type='hidden' value='0' />
                    <input class='oculto' name='modo' type='hidden' value='A' />
                    <input class='oculto' name='id_envio' type='hidden' value='<?php echo $id_envio ?>' />
                </form>                
            </div>            
            <div id="detalle">
                <form id="formEnvio" action="envio_detalle.php" method="post">
                    <input type='hidden' name='token' id='token' value='<?php echo $_SESSION["token"] ?>'/>
                    <div id="emailEnvio">
                        <label id="lblEmail" for="email" title="E-Mail de Envío">E-Mail de Envío</label>
                        <select name="email" tabindex="9" title="Seleccione la cuenta de E-Mail desde la que se enviarán los ficheros" id="email" <?php echo deshabilitarPorModo($modo) ?> >
                            <?php
                            // Recorremos todos los registros de emails para crear el desplegable
                            foreach ($emails as $email) {
                                echo "<option value='" . $email->getId_email() . "' title='" . $email->getDescripcion() . "'";

                                // Si estamos en modo de visor y el id_mail de la iteración coincide con el id del 
                                // objeto correo del objeto envio, lo marcamos como seleccionado
                                if ($modo === "V" && $envio->getEmail()[0]->getId_email() === $email->getId_email()) {
                                    echo " selected=\"selected\" ";
                                }
                                echo ">" . $email->getDescripcion() . "</option>";
                            }
                            ?>

                        </select>     

                    </div>
                    <div class="tablaanidada left">
                        

                            <?php
                            // Mostramos un encabezado u otro dependiendo del modo en el que se encuentre la página
                            if ($modo === "A") {
                                echo "<p>Seleccione un grupo de usuario para realizar el envío</p>";
                            } else {

                                // Creamos un formulario vación al principio de la tabla, 
                                // para permitir la creación del resto de formularios que 
                                // mostrarán despues los iconos de detalle
                                echo "<form action='grupo_detalle.php' method='post'>";
                                echo "</form>";


                                echo "<p>El envío se realizo al siguiente grupo, conteniendo los empleados que se muestran</p>";
                            }
                            
                            echo "<table>";


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

                                echo "<tr class='tablaCabecera'>";
                                echo "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                                echo textoElipsis($grupo->getNombre(), 30);
                                echo "</td>";
                                echo "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                                echo "</td>";
                                echo "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                                echo "</td>";
                                echo "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                                echo "</td>";

                                // Si el modo es alta, mostramos checkboxex para marcar, 
                                // en caso contrario mostramos el icono de detalle
                                if ($modo === "A") {
                                    echo "<td title='Haga click para seleccionar el grupo'>";
                                    echo '<input tabindex="10" type="checkbox" name="gruposel[]" value="' . $grupo->getId_grupo() . '" id="gruposel' . $grupo->getId_grupo() . '"/>';
                                    echo '<label class="oculto" for="gruposel' . $grupo->getId_grupo() . '">';
                                    echo "Marque para seleccionar";
                                    echo '</label>';
                                } else {
                                    echo "<td title='" . textoElipsis($grupo->getDescripcion(), 50) . "'>";
                                    echo '</td>';
                                    echo '<td>';
                                    echo "<form action='grupo_detalle.php' method='post' >";
                                    echo "<button tabindex='10' name='button' value='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles del grupo' /></button>";
                                    echo "<input class='oculto' name='id_grupo' type='hidden' value='" . $grupo->getId_grupo() . "' />";
                                    echo "<input class='oculto' name='modo' type='hidden' value='V' />";
                                    echo "</form>";
                                }
                                echo "</td>";
                                echo "</tr>";


                                $i = 0;

                                // Iteramos por todos los empleados
                                foreach ($empleadosEnGrupos as $empleado) {


                                    // Si el contador es un número par, le daremos un estilo y si 
                                    // es impar le daremos otro
                                    if ($i % 2 === 0) {
                                        echo '<tr class="pijama1">';
                                    } else {
                                        echo '<tr class="pijama2">';
                                    }

                                    echo "<td title='" . textoElipsis($empleado->getNombre(), 30) . "' >";
                                    echo textoElipsis($empleado->getNombre(), 30);
                                    echo "</td>";
                                    echo "<td title='" . textoElipsis($empleado->getApellido(), 30) . "' >";
                                    echo textoElipsis($empleado->getApellido(), 30);
                                    echo "</td>";
                                    echo "<td title='" . textoElipsis($empleado->getEmail(), 30) . "' >";
                                    echo textoElipsis($empleado->getEmail(), 30);
                                    echo "</td>";
                                    echo "<td title='" . textoElipsis($empleado->getEspecialidad(), 50) . "' >";
                                    echo textoElipsis($empleado->getEspecialidad(), 50);
                                    echo "</td>";
                                    echo "<td title='" . textoElipsis($empleado->getCargo(), 15) . "' >";
                                    echo textoElipsis($empleado->getCargo(), 15);
                                    echo "</td>";

                                    // Si el modo es visor mostramos el icono de detalle                                    
                                    if ($modo === "V") {
                                        echo '<td>';
                                        echo "<form action='empleado_detalle.php' method='post' >";
                                        echo "<button tabindex='11' name='button' value='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles del empleado' /></button>";
                                        echo "<input class='oculto' name='id_empleado' type='hidden' value='" . $empleado->getId_empleado() . "' />";
                                        echo "<input class='oculto' name='modo' type='hidden' value='V' />";
                                        echo "</form>";
                                        echo '</td>';
                                    }

                                    echo "</tr>";

                                    // Incrementamos el contador
                                    $i++;
                                }
                            }
                            ?>
                        </table>
                    </div>
                    <div class="tablaanidada right">
                        

                            <?php
                            // Mostramos un encabezado u otro dependiendo del modo en el que se encuentre la página
                            if ($modo === "A") {
                                echo "<p>Seleccione los archivos a enviar</p>";
                            } else {
                                echo "<p>Se envió el siguiente fichero</p>";
                            }

                            echo "<table>";
                            
                            // Iteramos por todos los ficheros recuperados
                            foreach ($ficheros as $fichero) {

                                echo "<tr class='tablaCabecera'>";
                                echo "<td title='" . textoElipsis($fichero->getDescripcion(), 50) . "'>";
                                echo textoElipsis($fichero->getDescripcion(), 50);
                                echo "</td>";
                                echo "<td title='" . textoElipsis($fichero->getDescripcion(), 50) . "'>";
                                echo "</td>";

                                // Si el modo es alta, mostramos checkboxex para marcar, 
                                // en caso contrario mostramos el icono de detalle                                
                                if ($modo === "A") {
                                    echo "<td title='Haga click para seleccionar el fichero'>";
                                    echo '<input tabindex="12" type="checkbox" name="ficherosel[]" value="' . $fichero->getId_fichero() . '" id="ficherosel' . $fichero->getId_fichero() . '"/>';
                                    echo '<label class="oculto" for="ficherosel' . $fichero->getId_fichero() . '">';
                                    echo "Marque para seleccionar";
                                    echo '</label>';
                                    
                                } else {
                                    echo '<td>';
                                    echo "<form action='fichero_detalle.php' method='post' >";
                                    echo "<button tabindex='12' name='button' value='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles del fichero' /></button>";
                                    echo "<input class='oculto' name='id_fichero' type='hidden' value='" . $fichero->getId_fichero() . "' />";
                                    echo "<input class='oculto' name='modo' type='hidden' value='V' />";
                                    echo "</form>";
                                }
                                echo "</td>";
                                echo "</tr>";


                                echo '<tr class="pijama1">';

                                echo "<td title='" . textoElipsis($fichero->getNombre(), 50) . "' >";
                                echo textoElipsis($fichero->getNombre(), 50);
                                echo "</td>";
                                echo "<td title='" . textoElipsis($fichero->getTipo(), 30) . "' >";
                                echo textoElipsis($fichero->getTipo(), 30);
                                echo "</td>";
                                echo "<td title='" . textoElipsis($fichero->getTamanyo(), 30) . "' >";
                                echo textoElipsis($fichero->getTamanyo(), 30);
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>                            
                        </table>                        
                    </div>

                    <div id="especial">

                        <?php
                        // Comprobamos el modo en el que está la página. Si está en 
                        // modo Adicción, creamos un botón de aceptar  modificaciones 
                        // y otro de cancelarlas
                        if ($modo === "A") {
                            // Creamos el botón de aceptar
                            echo "<input class='especialbtn' tabindex='13' name='boton' id='aceptar' type='submit' value='Aceptar' title='Pulse para confirmar las modificaciones' />";

                            // Creamos el botón de cancelar
                            echo "<input class='especialbtn' tabindex='14' name='boton' id='cancelar' type='submit' value='Cancelar' title='Pulse para cancelar las modificaciones' />";

                            // Creamos dos objetos ocultos para reenviar la información del modo de la página y del identificador del usuario
                            echo "<input class='oculto' name='id_envio' type='hidden' value='$id_envio' />";
                            echo "<input class='oculto' name='modo' type='hidden' value='$modo' />";
                        }
                        ?>      
                    </div>                                        
                </form>               
                <div class="error">
                    <p><?php echo $error ?></p>
                </div>
            </div>                        
        </div>
        <div class="modal" ><!-- Posicionar al final del cuerpo de la página --></div>
    </body>
</html>
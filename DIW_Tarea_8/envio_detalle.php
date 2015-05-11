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


// Creamos una isntacia de la base de datos
$db = new DB();

// Listamos los emails
$emails = $db->listarEmails("", "");

// Recuperamos los grupos existentes
$grupos = $db->listarGrupos("", "");

// Recuperamos los ficheros existentes
$ficheros = $db->listarFicheros("", "");




// Comprobamos el modo de la página
switch ($modo) {

    case "A": {

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


                        $salida = $db->insertarEnvio($grupossel, $ficherossel, $id_email);

                        // Asignamos tambien el id_envio a la sesión para 
                        // prevenir inserciones extras por refrescos de página
                        $_SESSION['id_envio'] = $id_envio;

                        // Cambiamos el modo a visor
                        $modo = "V";
                    } else {
                        // Si hay error de validación, copiamos su valor a 
                        // la variable $error
                        $error = $validacion;
                    }
                } else {
                    // Si la sesión no es válida, recuperamos los datos 
                    // del grupo para mostrarlos en modo visor
                    //$grupo = $db->recuperarGrupo($id_grupo)[0];
                    //$modo = "V";
                }
            }

            break;
        }
}
?>


<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Detalle Usuario</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type = "text/css" rel = "stylesheet" href = "./estilos.css"/>
    </head>
    <body>
        <div class="cabecera" id="index" >
            <p>Gestión Documental</p>
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
                <h3>Detalle de envíos</h3>
                <form id="añadir" action='envio_detalle.php' method='post' >
                    <input type='submit' tabindex="8" value='Añadir Envio' alt='Añadir Envio' title="Pulse para añadir un envio nuevo"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='añadir' type='text' value='0' />
                    <input class='oculto' name='modo' type='text' value='A' />
                    <input class='oculto' name='id_envio' type='text' value='0' />
                </form>                
            </div>            
            <div id="detalle">
                <form action="envio_detalle.php" method="post">
                    <input type='hidden' name='token' id='token' value='<?php echo $_SESSION["token"] ?>'/>
                    <div id="emailEnvio">
                        <label id="lblEmail" name="lblEmail" for="email" title="E-Mail de Envío">E-Mail de Envío</label>
                        <select name="email" tabindex="9" title="Seleccione la cuenta de E-Mail desde la que se enviarán los ficheros" id="email" <?php echo deshabilitarPorModo($modo) ?> >
                            <?php
                            foreach ($emails as $email) {
                                echo "<option value='" . $email->getId_email() . "' title='" . $email->getDescripcion() . "'>" . $email->getDescripcion() . "</option>";
                            }
                            ?>
                        </select>     

                    </div>
                    <div class="tablaanidada left">
                        <table>
                            <caption>Seleccione un grupo de usuario para realizar el envío</caption>
                            <?php
                            foreach ($grupos as $grupo) {

                                $empleadosEnGrupos = $db->listarEmpleadosEnGrupo($grupo->getId_grupo());
                                echo "<thead>";
                                echo "<tr>";
                                echo "<td title='" . $grupo->getDescripcion() . "'>";
                                echo $grupo->getNombre();
                                echo "</td>";
                                echo "<td title='" . $grupo->getDescripcion() . "'>";
                                echo "</td>";
                                echo "<td title='" . $grupo->getDescripcion() . "'>";
                                echo "</td>";
                                echo "<td title='" . $grupo->getDescripcion() . "'>";
                                echo "</td>";
                                echo "<td title='Haga click para seleccionar el grupo'>";
                                echo '<input type="checkbox" name="gruposel[]" id="gruposel" value="' . $grupo->getId_grupo() . '" />';
                                echo "</td>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                $i = 0;

                                foreach ($empleadosEnGrupos as $empleado) {


                                    // Si el contador es un número par, le daremos un estilo y si 
                                    // es impar le daremos otro
                                    if ($i % 2 === 0) {
                                        echo '<tr class="pijama1">';
                                    } else {
                                        echo '<tr class="pijama2">';
                                    }

                                    echo "<td title='" . $empleado->getNombre() . "' />";
                                    echo $empleado->getNombre();
                                    echo "</td>";
                                    echo "<td title='" . $empleado->getApellido() . "' />";
                                    echo $empleado->getApellido();
                                    echo "</td>";
                                    echo "<td title='" . $empleado->getEmail() . "' />";
                                    echo $empleado->getEmail();
                                    echo "</td>";
                                    echo "<td title='" . $empleado->getEspecialidad() . "' />";
                                    echo $empleado->getEspecialidad();
                                    echo "</td>";
                                    echo "<td title='" . $empleado->getCargo() . "' />";
                                    echo $empleado->getCargo();
                                    echo "</td>";
                                    echo "</tr>";

                                    // Incrementamos el contador
                                    $i++;
                                }
                                echo "</tbody>";
                            }
                            ?>
                        </table>
                    </div>
                    <div class="tablaanidada right">
                        <table>
                            <caption>Seleccione los archivos a enviar</caption>
                            <?php
                            foreach ($ficheros as $fichero) {

                                echo "<thead>";
                                echo "<tr>";
                                echo "<td title='" . $fichero->getDescripcion() . "'>";
                                echo $fichero->getDescripcion();
                                echo "</td>";
                                echo "</td>";
                                echo "<td title='" . $fichero->getDescripcion() . "'>";
                                echo "</td>";
                                echo "<td title='Haga click para seleccionar el fichero'>";
                                echo '<input type="checkbox" name="ficherosel[]" id="ficherosel" value="' . $fichero->getId_fichero() . '" />';
                                echo "</td>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";

                                echo '<tr class="pijama1">';

                                echo "<td title='" . $fichero->getNombre() . "' />";
                                echo $fichero->getNombre();
                                echo "</td>";
                                echo "<td title='" . $fichero->getTipo() . "' />";
                                echo $fichero->getTipo();
                                echo "</td>";
                                echo "<td title='" . $fichero->getTamanyo() . "' />";
                                echo $fichero->getTamanyo();
                                echo "</td>";
                                echo "</tr>";
                                echo "</tbody>";
                            }
                            ?>                            
                        </table>                        
                    </div>
                    <hr />
                    <div id="especial">
                        <?php
                        // Comprobamos el modo en el que está la página. Si está en 
                        // modo Modificación o Adicción, creamos un botón de aceptar 
                        // modificaciones y otro de cancelarlas
                        if ($modo === "A") {
                            // Creamos el botón de aceptar
                            echo "<input class='especialbtn' tabindex='13' name='boton' id='aceptar' type='submit' value='Aceptar' alt='Aceptar' title='Pulse para confirmar las modificaciones' />";

                            // Creamos el botón de cancelar
                            echo "<input class='especialbtn' tabindex='14' name='boton' id='cancelar 'type='submit' value='Cancelar' alt='Cancelar' title='Pulse para cancelar las modificaciones' />";

                            // Creamos dos objetos ocultos para reenviar la información del modo de la página y del identificador del usuario
                            echo "<input class='oculto' name='id_envio' type='text' value='$id_envio' />";
                            echo "<input class='oculto' name='modo' type='text' value='$modo' />";
                        }
                        ?>      
                    </div>                                        
                </form>               
                <div class="error">
                    <p><?php echo $error ?></p>
                </div>

            </div>                        
        </div>
    </body>
</html>
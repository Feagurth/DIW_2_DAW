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
require_once './objetos/Grupo.php';
require_once './objetos/GrupoEmpleado.php';

try {


    // Inicializamos variables
    $error = "";

    // Recupermaos el nombre de usuario
    $nombreUsuario = $_SESSION['nombreUsuario'];

    // Creamos un objeto Grupo
    $grupo = new Grupo(array("id_grupo" => "", "nombre" => "", "descripcion" => ""));

    // Recuperamos los valores del modo de visión de la página   
    $modo = $_POST['modo'];


    // Recuperamos los valores de grupo de sesión si están ahí o en su defecto de post
    $id_grupo = isset($_SESSION['id_grupo']) ? $_SESSION['id_grupo'] : $_POST['id_grupo'];

    // Validamos el usuario
    validarUsuario($_SESSION['user'], $_SESSION['pass']);

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Comprobamos el modo de la página
    switch ($modo) {
        // Si la página está refrescando para actualizar las relaciones 
        // con los empleados
        case "AE": {

                if (isset($_POST['seleccionadoEmpleado'])) {
                    $empleadosSel = $_POST['seleccionadoEmpleado'];

                    $db->insertarRelacionesGrupoEmpleado($id_grupo, $empleadosSel);
                } else {
                    $db->eliminarRelacionesGrupoEmpleado($id_grupo);
                }

                // Cambiamos el modo de la página a visionado
                $modo = "V";

                // Eliminamos el break del case para que el flujo del programa 
                // continue como si se hubiese cargado en modo visión
                //break;
            }
        // Si la página está en modo visualización
        case "V": {
                // Recuperamos la información sobre el grupo pasándo 
                // su id como parámetro
                $grupo = $db->recuperarGrupo($id_grupo)[0];

                break;
            }
        // Si la página está en modo añadir
        case "A": {
                // Verificamos si en la información de post tenemos la información 
                // de los botones de confirmanción o de cancelación pulsados.
                // Verificamos si la información del botón es la de cancelar.
                if (isset($_POST['boton']) && $_POST['boton'] === "Cancelar") {
                    // Si cancelamos el añadir un grupo, pasamos a sesión el 
                    // indice como valor para la página index.php para que cargue 
                    // la plantilla de grupos
                    $_SESSION['indice'] = 2;

                    // Navegamos a la pagina index.php
                    header("location:index.php");
                }

                // Comprobamos si la información del botón es la de aceptar
                if (isset($_POST['boton']) && $_POST['boton'] === "Aceptar") {
                    // Comprobamos que la sesión es correcta
                    if (comprobarTokenSesion()) {
                        // Si es todo correcto asignamos la informacón introducida 
                        // en los inputs  y que se encuentra en post
                        $grupo->setId_grupo($id_grupo);
                        $grupo->setNombre($_POST['nombre']);
                        $grupo->setDescripcion($_POST['descripcion']);


                        // Validamos los datos introducidos
                        $validacion = validarDatosGrupo($grupo);

                        // Comprobamos si hay mensaje de error en la validación
                        if ($validacion === "") {

                            // Si no lo hay, realizamos la insercción pasándo como 
                            // parámetro el objeto Grupo, dejando la gestión de 
                            // errores de la insercción a las excepciones que se 
                            // puedan lanzar. El id resultante de la insercción, lo 
                            // asignamos a la variable $id_grupo
                            $id_grupo = $db->insertarGrupo($grupo);

                            // Asignamos tambien el id_grupo a la sesión para 
                            // prevenir inserciones extras por refrescos de página
                            $_SESSION['id_grupo'] = $id_grupo;

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
                        $grupo = $db->recuperarGrupo($id_grupo)[0];
                        $modo = "V";
                    }
                }

                break;
            }

        // Si es una eliminación
        case "E": {

                // Eliminamos el grupo usando la función adecuada y 
                // pasándo su id como paráemtro
                $db->eliminarGrupo($id_grupo);

                // Tras borrar el grupo volvemos a la pantalla index.php y para 
                // eso pasamos a sesión el indice como valor para la página 
                // index.php para que cargue la plantilla de grupos
                $_SESSION['indice'] = 2;

                // Navegamos a index.php
                header("location:index.php");

                break;
            }
        // Si la página se carga en modo modificación
        case "M": {

                // Comprobamos si ha pulsado el botón de confirmación o cancelación
                if (isset($_POST['boton'])) {

                    // Si se ha pulsado, vemos si ha sido el de cancelar
                    if ($_POST['boton'] === "Cancelar") {

                        // De ser así, recuperamos los datos origianles del 
                        // grupo, para que se sobreescriban sobre cualquier 
                        // moficación que se haya podido hacer
                        $grupo = $db->recuperarGrupo($id_grupo)[0];

                        // Cambiamos el modo de la página a visualización
                        $modo = "V";
                    }

                    // Si se ha pulsado el botón de aceptar
                    if ($_POST['boton'] === "Aceptar") {

                        // Comprobamos que la sesión es correcta
                        if (comprobarTokenSesion()) {

                            // Asignamos la informacón introducida en los inputs 
                            // y que se encuentra en post
                            $grupo->setId_grupo($id_grupo);
                            $grupo->setNombre($_POST['nombre']);
                            $grupo->setDescripcion($_POST['descripcion']);


                            // Realizamos la validación de los datos
                            $validacion = validarDatosGrupo($grupo);

                            // Comprobamos si la validación ha generado algún 
                            // mensaje de error
                            if ($validacion === "") {

                                // Si no hay mensaje de error, realizamos la modificación 
                                // pasándo como parámetro el objeto Grupo, dejando 
                                // la gestión de errores de la modificación a las excepciones 
                                // que se puedan lanzar
                                $db->modificarGrupo($grupo);

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
                            $grupo = $db->recuperarGrupo($id_grupo)[0];
                            $modo = "V";
                        }
                    }
                } else {
                    // Si no se ha pulsado ningún botón nos limitamos a 
                    // recuperar los datos del grupo para mostrarlos
                    $grupo = $db->recuperarGrupo($id_grupo)[0];
                }
                break;
            }
    }
} catch (Exception $ex) {
    // Recuperamos el mensaje de error
    $error = $ex->getMessage();

    // Cambiamos el modo de la página a visualización
    $modo = "V";
}
?>


<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
        <title>Detalle Grupo</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type = "text/css" rel = "stylesheet" href = "./estilos.css"/>
        <script type="text/javascript" src="HTTP://code.jquery.com/jquery-latest.js"></script>        
        <script type="text/javascript" src="scripts/grupo_detalle.js"></script>
        <script type="text/javascript" src="scripts/funciones.js"></script>                
    </head>
    <body>
        <div class="cabecera" id="index" >
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
                <h2>Detalle de grupos</h2>
                <form id="añadir" action='grupo_detalle.php' method='post' >
                    <input type='submit' tabindex="8" value='Añadir Grupo' title="Pulse para añadir un grupo nuevo"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='añadir' type='hidden' value='0' />
                    <input class='oculto' name='modo' type='hidden' value='A' />
                    <input class='oculto' name='id_grupo' type='hidden' value='0' />
                </form>                
                <form id="modificar" action='grupo_detalle.php' method='post' >
                    <input type='submit' tabindex="8" value='Modificar Grupo' title="Pulse para modificar el grupo actual"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='modificar' type='hidden' value='<?php echo $id_grupo ?>' />
                    <input class='oculto' name='modo' type='hidden' value='M' />
                    <input class='oculto' name='id_grupo' type='hidden' value='<?php echo $id_grupo ?>' />
                </form>
                <form id="eliminar" action='grupo_detalle.php' method='post' >
                    <input type='submit' tabindex="9" value='Eliminar Grupo' title="Pulse para eliminar el grupo actual"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='modo' type='hidden' value='E' />
                    <input class='oculto' name='id_grupo' type='hidden' value='<?php echo $id_grupo ?>' />
                </form>
            </div>
            <div id="detalle">
                <form action="grupo_detalle.php" method="post">
                    <input type='hidden' name='token' id='token' value='<?php echo $_SESSION["token"] ?>'/>
                    <label id="lblNombre" for="nombre">Nombre&nbsp;</label>
                    <input tabindex="10" type="text" name="nombre" id="nombre" maxlength="30" value="<?php if ($grupo !== NULL) echo $grupo->getNombre() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblDescripcion" for="descripcion">Descripcion</label>
                    <input tabindex="11" type="text" name="descripcion" id="descripcion" maxlength="50" value="<?php if ($grupo !== NULL) echo $grupo->getDescripcion() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <br />
                    <?php
                    // Comprobamos el modo en el que está la página. Si está en 
                    // modo Modificación o Adicción, creamos un botón de aceptar 
                    // modificaciones y otro de cancelarlas
                    if ($modo === "A" || $modo === "M") {

                        // Creamos el botón de aceptar
                        echo "<input tabindex='13' name='boton' id='aceptar' type='submit' value='Aceptar' title='Pulse para confirmar las modificaciones' />";

                        // Creamos el botón de cancelar
                        echo "<input tabindex='14' name='boton' id='cancelar' type='submit' value='Cancelar' title='Pulse para cancelar las modificaciones' />";

                        // Creamos dos objetos ocultos para reenviar la información del modo de la página y del identificador del grupo
                        echo "<input class='oculto' name='id_grupo' type='hidden' value='$id_grupo' />";
                        echo "<input class='oculto' name='modo' type='hidden' value='$modo' />";
                    }
                    ?>      
                </form>               
                <div class="error">
                    <p><?php echo $error ?></p>
                </div>
            </div>

            <?php
            // Si la página está en modo visión 
            if ($modo === "V") {                
                // Creamos la tabla de relaciones de empleados con el grupo
                crearTablaRelacionesEmpleados($id_grupo, $error);
            }
            ?>

        </div>
         <div class="modal" ><!-- Posicionar al final del cuerpo de la página --></div>
    </body>
</html>
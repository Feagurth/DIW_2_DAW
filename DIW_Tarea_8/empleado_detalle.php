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
require_once './objetos/Empleado.php';

try {
    // Inicializamos variables
    $error = "";

    // Recupermaos el nombre de usuario
    $nombreUsuario = $_SESSION['nombreUsuario'];

    // Creamos un objeto Empleado
    $empleado = new Empleado(array("id_empleado" => "", "nombre" => "", "apellido" => "", "telefono" => "", "especialidad" => "", "cargo" => "", "direccion" => "", "email" => ""));

    // Recuperamos los valores del modo de visión de la página y 
    // del id_empleado que hemos pasado
    $modo = $_POST['modo'];
    $id_empleado = $_POST['id_empleado'];

    // Validamos el usuario
    validarUsuario($_SESSION['user'], $_SESSION['pass']);

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Comprobamos el modo de la página
    switch ($modo) {
        // Si la página está en modo visualización
        case "V": {
                // Recuperamos la información sobre el empleado pasándo 
                // su id como parámetro
                $empleado = $db->recuperarEmpleado($id_empleado)[0];

                break;
            }
        // Si la página está en modo añadir
        case "A": {
                // Verificamos si en la información de post tenemos la información 
                // de los botones de confirmanción o de cancelación pulsados.
                // Verificamos si la información del botón es la de cancelar.
                if (isset($_POST['boton']) && $_POST['boton'] === "Cancelar") {
                    // Si cancelamos el añadir un empleado, pasamos a sesión el 
                    // indice como valor para la página index.php para que cargue 
                    // la plantilla de empleados
                    $_SESSION['indice'] = 1;

                    // Navegamos a la pagina index.php
                    header("location:index.php");
                }

                // Comprobamos si la información del botón es la de aceptar
                if (isset($_POST['boton']) && $_POST['boton'] === "Aceptar") {
                    // Si es así, asignamos la informacón introducida en los inputs 
                    // y que se encuentra en post
                    $empleado->setId_empleado($id_empleado);
                    $empleado->setNombre($_POST['nombre']);
                    $empleado->setApellido($_POST['apellido']);
                    $empleado->setTelefono($_POST['telefono']);
                    $empleado->setEspecialidad($_POST['especialidad']);
                    $empleado->setCargo($_POST['cargo']);
                    $empleado->setDireccion($_POST['direccion']);
                    $empleado->setEmail($_POST['email']);

                    // Validamos los datos introducidos
                    $validacion = validarEmpleado($empleado);

                    // Comprobamos si hay mensaje de error en la validación
                    if ($validacion === "") {

                        // Si no lo hay, realizamos la insercción pasándo como 
                        // parámetro el objeto Empleado, dejando la gestión de 
                        // errores de la insercción a las excepciones que se 
                        // puedan lanzar. El id resultante de la insercción, lo 
                        // asignamos a la variable $id_empleado
                        $id_empleado = $db->insertarEmpleado($empleado);

                        // Cambiamos el modo a visor
                        $modo = "V";
                    } else {
                        // Si hay error de validación, copiamos su valor a 
                        // la variable $error
                        $error = $validacion;
                    }
                }

                break;
            }

        // Si es una eliminación
        case "E": {

                // Eliminamos el empleado usando la función adecuada y 
                // pasándo su id como paráemtro
                $db->eliminarEmpleado($id_empleado);

                // Tras borrar el empleado volvemos a la pantalla index.php y para 
                // eso pasamos a sesión el indice como valor para la página 
                // index.php para que cargue la plantilla de empleados                
                $_SESSION['indice'] = 1;

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
                        // empleado, para que se sobreescriban sobre cualquier 
                        // moficación que haya podido hacer el usuario
                        $empleado = $db->recuperarEmpleado($id_empleado)[0];

                        // Cambiamos el modo de la página a visualización
                        $modo = "V";
                    }

                    // Si se ha pulsado el botón de aceptar
                    if ($_POST['boton'] === "Aceptar") {
                        // Asignamos la informacón introducida en los inputs 
                        // y que se encuentra en post
                        $empleado->setId_empleado($id_empleado);
                        $empleado->setNombre($_POST['nombre']);
                        $empleado->setApellido($_POST['apellido']);
                        $empleado->setTelefono($_POST['telefono']);
                        $empleado->setEspecialidad($_POST['especialidad']);
                        $empleado->setCargo($_POST['cargo']);
                        $empleado->setDireccion($_POST['direccion']);
                        $empleado->setEmail($_POST['email']);

                        // Realizamos la validación de los datos
                        $validacion = validarEmpleado($empleado);

                        // Comprobamos si la validación ha generado algún 
                        // mensaje de error
                        if ($validacion === "") {

                            // Si no hay mensaje de error, realizamos la modificación 
                            // pasándo como parámetro el objeto  Empleado, dejando 
                            // la gestión de errores de la modificación a las excepciones 
                            // que se puedan lanzar
                            $db->modificarEmpleado($empleado);

                            // Cambiamos el modo a visor
                            $modo = "V";
                        } else {
                            // Si hay error de validación, copiamos su valor a 
                            // la variable $error                            
                            $error = $validacion;
                        }
                    }
                } else {
                    // Si no se ha pulsado ningún botón nos limitamos a 
                    // recuperar los datos del empleado y mostarselos al usuario
                    $empleado = $db->recuperarEmpleado($id_empleado)[0];
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


<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Detalle Empleado</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type = "text/css" rel = "stylesheet" href = "./estilos.css"/>
    </head>
    <body>
        <div class="cabecera" id="index" >
            <p>Gestión Documental</p>
        </div>
        <div>
            <?php include './menu.php'; ?>
        </div>
        <div id="cuerpo">      
            <div id="botonera">
                <h3>Detalle de empleados</h3>
                <form id="añadir" action='empleado_detalle.php' method='post' >
                    <input type='submit' tabindex="8" value='Añadir Empleado' alt='Añadir Empleado' title="Pulse para anañadir un nuevo empleado"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='añadir' type='text' value='0' />
                    <input class='oculto' name='modo' type='text' value='A' />
                    <input class='oculto' name='id_empleado' type='text' value='0' />
                </form>
                
                <form id="modificar" action='empleado_detalle.php' method='post' >
                    <input type='submit' tabindex="8" value='Modificar Empleado' alt='Modificar Empleado' title="Pulse para modificar el empleado actual"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='modificar' type='text' value='<?php echo $id_empleado ?>' />
                    <input class='oculto' name='modo' type='text' value='M' />
                    <input class='oculto' name='id_empleado' type='text' value='<?php echo $id_empleado ?>' />
                </form>
                <form id="eliminar" action='empleado_detalle.php' method='post' >
                    <input type='submit' tabindex="9" value='Eliminar Empleado' alt='Eliminar Empleado' title="Pulse para eliminar el empleado actual"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='modo' type='text' value='E' />
                    <input class='oculto' name='id_empleado' type='text' value='<?php echo $id_empleado ?>' />
                </form>
            </div>
            <div id="detalle">
                <form action="empleado_detalle.php" method="post">
                    <label id="lblNombre" for="nombre">Nombre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input tabindex="10" type="text" name="nombre" id="nombre" maxlength="30" value="<?php if ($empleado !== NULL) echo $empleado->getNombre() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblApellido" for="apellido">Apellido</label>
                    <input tabindex="11" type="text" name="apellido" id="apellido" maxlength="30" value="<?php if ($empleado !== NULL) echo $empleado->getApellido() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblTelefono" for="telefono">Telefono </label>
                    <input tabindex="12" type="text" name="telefono" id="telefono" maxlength="20" value="<?php if ($empleado !== NULL) echo $empleado->getTelefono() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblEmail" for="email">E-Mail </label>
                    <input tabindex="13" type="text" name="email" id="email" maxlength="30" value="<?php if ($empleado !== NULL) echo $empleado->getEmail() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <br />
                    <label id="lblEspecialidad" for="especialidad">Especialidad </label>
                    <input tabindex="14" type="text" name="especialidad" id="especialidad" maxlength="50" value="<?php if ($empleado !== NULL) echo $empleado->getEspecialidad() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblCargo" for="cargo">Cargo </label>
                    <input tabindex="15" type="text" name="cargo" id="cargo" maxlength="15" value="<?php if ($empleado !== NULL) echo $empleado->getCargo() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <br />
                    <label id="lblDireccion" for="direccion">Direccion&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input  tabindex="16" type="text" name="direccion" id="direccion" maxlength="75" value="<?php if ($empleado !== NULL) echo $empleado->getDireccion() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <br />

                    <?php
                    // Comprobamos el modo en el que está la página. Si está en 
                    // modo Modificación o Adicción, creamos un botón de aceptar 
                    // modificaciones y otro de cancelarlas
                    if ($modo === "A" || $modo === "M") {

                        // Creamos el botón de aceptar
                        echo "<input tabindex='17' name='boton' id='aceptar' type='submit' value='Aceptar' alt='Aceptar' title='Pulse para confirmar las modificaciones' />";

                        // Creamos el botón de cancelar
                        echo "<input tabindes='18' name='boton' id='cancelar 'type='submit' value='Cancelar' alt='Cancelar' title='Pulse para cancelar las modificaciones' />";

                        // Creamos dos objetos ocultos para reenviar la información del modo de la página y del identificador del empleado
                        echo "<input class='oculto' name='id_empleado' type='text' value='$id_empleado' />";
                        echo "<input class='oculto' name='modo' type='text' value='$modo' />";
                    }
                    ?>      
                </form>               
                <div class="error">
                    <p><?php echo $error ?></p>
                </div>
            </div>            
        </div>
    </body>
</html>
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
require_once './objetos/objEmpleado.php';

// Inicializamos variables
$error = "";

// Recupermaos el nombre de usuario
$nombreUsuario = $_SESSION['nombreUsuario'];

try {
    // Validamos el usuario
    validarUsuario($_SESSION['user'], $_SESSION['pass']);

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    xdebug_break();


    if (isset($_POST['id_empleado'])) {

        $id_empleado = $_POST['id_empleado'];

        if ($id_empleado === "0") {
            $modo = "A";
        } else {
            $modo = "V";
        }

        $empleado = NULL;
    } else {
        if (isset($_POST['modificar'])) {
            $id_empleado = $_POST['modificar'];
            $modo = "M";
        }

        if (isset($_POST['eliminar'])) {
            $id_empleado = $_POST['eliminar'];
            $modo = "E";
        }
        if (isset($_POST['cancelar'])) {
            if ($_POST['cancelar'] === "0") {
                $_SESSION['indice'] = 1;
                header("location:index.php");
            }
            else
            {
                $modo = "V";
                $id_empleado = $_POST['cancelar'];
            }
        }
    }

    if ($id_empleado !== "0") {

        if ($modo !== "E") {
            $empleado = $db->recuperarEmpleado($id_empleado)[0];
        } else {

            $db->eliminarEmpleado($id_empleado);

            $_SESSION['indice'] = 1;
            header("location:index.php");
        }
    }
} catch (Exception $ex) {
    $error = $ex->getMessage();
}
?>


<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Indice</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type = "text/css" rel = "stylesheet" href = "./estilos.css"/>
    </head>
    <body>
        <div class="cabecera" id="index" >
            <p>Gestión Documental</p>
        </div>
        <nav id="menu">
            <ul>
                <li>
                    <form action='index.php' method='post' >
                        <input type='submit' value='Empleados' tabindex="1" alt="Gestor de Empleados" />
                        <input class="oculto" name='indice' type='text' value='1' />
                    </form>                    
                </li>
                <li>
                    <form action='index.php' method='post' >
                        <input type='submit' value='Grupos' tabindex="2" alt="Gestor de Grupos" />
                        <input class="oculto" name='indice' type='text' value='2' />
                    </form>                    
                </li>
                <li>
                    <form action='index.php' method='post' >
                        <input type='submit' value='Documentos' tabindex="3" alt="Gestor de Documentos" />
                        <input class="oculto" name='indice' type='text' value='3' />
                    </form>                    
                </li>
                <li>
                    <form action='index.php' method='post' >
                        <input type='submit' value='Envíos' tabindex="4" alt="Gestor de Envíos" />
                        <input class="oculto" name='indice' type='text' value='4' />
                    </form>                    
                </li>
                <li>
                    <form action='index.php' method='post' >
                        <input type='submit' value='Cuentas Email' tabindex="5" alt="Gestor de Cuentas de Email" />
                        <input class="oculto" name='indice' type='text' value='5' />
                    </form>                    
                </li>
                <li>
                    <form action='index.php' method='post' >
                        <input type='submit' value='Acceso' tabindex="6" alt="Gestor de Accesos" />
                        <input class="oculto" name='indice' type='text' value='6' />
                    </form>                    
                </li>
                <li>
                    <form action='login.php' method='post' >
                        <input type='submit' tabindex="7" value="<?php echo 'Desconectar (' . textoElipsis($nombreUsuario, 15) . ')' ?>" alt="<?php echo 'Desconectar (' . textoElipsis($nombreUsuario, 15) . ')' ?>" />
                        <input class="oculto" name='clear' type='text' value='1' />
                    </form>                    
                </li>                
            </ul>
        </nav>        
        <hr />
        <div id="cuerpo">      
            <div id="botonera">
                <h3>Detalle de empleados</h3>
                <form id="modificar" action='detalle_empleado.php' method='post' >
                    <input type='submit' tabindex="9" value='Modificar Empleado' alt='Modificar Empleado' title="Pulse para modificar el empleado actual"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='modificar' type='text' value='<?php echo $id_empleado ?>' />
                </form>
                <form id="eliminar" action='detalle_empleado.php' method='post' >
                    <input type='submit' tabindex="10" value='Eliminar Empleado' alt='Eliminar Empleado' title="Pulse para eliminar el empleado actual"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='eliminar' type='text' value='<?php echo $id_empleado ?>' />
                </form>
            </div>
            <div id="detalle">
                <form action="detalle_empleado.php" method="post">
                    <label id="lblNombre" for="nombre">Nombre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input tabindes="11" type="text" id="nombre" maxlength="30" value="<?php if ($empleado !== NULL) echo $empleado->getNombre() ?> " <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblApellido" for="apellido">Apellido</label>
                    <input tabindes="12" type="text" id="apellido" maxlength="30" value="<?php if ($empleado !== NULL) echo $empleado->getApellido() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblTelefono" for="telefono">Telefono </label>
                    <input tabindes="13" type="text" id="telefono" maxlength="20" value="<?php if ($empleado !== NULL) echo $empleado->getTelefono() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblEmail" for="email">E-Mail </label>
                    <input tabindes="14" type="text" id="email" maxlength="30" value="<?php if ($empleado !== NULL) echo $empleado->getEmail() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <br />
                    <label id="lblEspecialidad" for="especialidad">Especialidad </label>
                    <input tabindes="15" type="text" id="especialidad" maxlength="50" value="<?php if ($empleado !== NULL) echo $empleado->getEspecialidad() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <label id="lblCargo" for="cargo">Cargo </label>
                    <input tabindes="16" type="text" id="cargo" maxlength="15" value="<?php if ($empleado !== NULL) echo $empleado->getCargo() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <br />
                    <label id="lblDireccion" for="direccion">Direccion&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input tabindes="17" type="text" id="direccion" maxlength="75" value="<?php if ($empleado !== NULL) echo $empleado->getDireccion() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <br />
                </form>
<?php
// Comprobamos el modo en el que está la página. Si está en 
// modo Modificación o Adicción, creamos un botón de aceptar 
// modificaciones y otro de cancelarlas
if ($modo === "A" || $modo === "M") {
    echo "<form id='aceptar' action='detalle_empleado.php' method='post' >";
    echo "<input tabindes='18' type='submit' value='Aceptar' alt='Aceptar' title='Pulse para confirmar las modificaciones' />";
    echo "<input class='oculto' name='aceptar' type='text' value='0' />";
    echo "</form>";
    echo "<form id='cancelar' action='detalle_empleado.php' method='post' >";
    echo "<input tabindes='19' type='submit' value='Cancelar' alt='Cancelar' title='Pulse para cancelar las modificaciones' />";
    echo "<input class='oculto' name='cancelar' type='text' value='$id_empleado' />";
    echo "</form>";
}
?>

            </div>
        </div>
    </body>
</html>
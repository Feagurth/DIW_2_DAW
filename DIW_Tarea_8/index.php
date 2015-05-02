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

// Inicializamos variables
$error = "";

// Recupermaos el nombre de usuario
$nombreUsuario = $_SESSION['nombreUsuario'];

try {
    // Validamos el usuario
    validarUsuario($_SESSION['user'], $_SESSION['pass']);
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
                        <input type='submit' tabindex="7" value="<?php echo textoElipsis($nombreUsuario, 15) ?>" alt="<?php echo textoElipsis($nombreUsuario, 15) ?>" />
                        <input class="oculto" name='clear' type='text' value='1' />
                    </form>                    
                </li>                
            </ul>
        </nav>        
        <hr />
        <div id="cuerpo">            
            <?php
            // Si no es así, comprobamos si tenemos que cargar alguna plantilla
            if (isset($_POST['indice'])) {

                // Cargamos la plantilla que sea dependiendo del valor del 
                // índice pasado por POST
                switch ($_POST['indice']) {
                    case '1': {
                            include './empleados.php';
                            break;
                        }
                    case '2': {
                            include './empleados.php';
                            break;
                        }
                    case '3': {
                            include './empleados.php';
                            break;
                        }
                    case '4': {
                            include './empleados.php';
                            break;
                        }
                    case '5': {
                            include './empleados.php';
                            break;
                        }
                }
            }
            ?>
        </div>
    </body>
</html>
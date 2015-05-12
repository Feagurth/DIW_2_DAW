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

// Iniciamos sesión
session_start();

// Cargamos los archivos necesarios
require_once './Db.php';
require_once './funciones.php';

// Comprobamos si hay una petición de limpieza de usuario y password de 
// sesión, lo que siginificariá que el usuario logeado desea deslogearse
if (isset($_POST['clear'])) {
    // Eliminamos la informaicón del POST sobre usuario y contraseña
    unset($_POST['user']);
    unset($_POST['pass']);
    unset($_POST['nombreUsuario']);
    
}

// Definimos e inicializamos la variable de errores
$error = " ";

try {

    // Comprobamos si POST trae informaicón de usuario y password
    if (isset($_POST['user']) && isset($_POST['pass'])) {
        // Creamos un nuevo objeto de acceso a base de datos 

        if (validarCadenaConNumeros($_POST['user']) && validarCadenaConNumeros($_POST['pass'])) {

            $db = new DB();

            // Obtenemos el listado de todas las personas
            if ($db->validarUsuario($_POST['user'], md5($_POST['pass']))) {
                // Guardamos el hash del usuario y del password en sesión
                $_SESSION['user'] = $_POST['user'];
                $_SESSION['pass'] = md5($_POST['pass']);
                $_SESSION['nombreUsuario'] = $nombreUsuario;

                // Eliminamos la informaicón del POST sobre usuario y contraseña
                unset($_POST['user']);
                unset($_POST['pass']);
                unset($_POST['nombreUsuario']);

                // Limpiamos la variable de errores
                $error = " ";

                // Navegamos a la página de inicio de la aplicación
                header("location:index.php");
            } else {
                // Mostramos un mensaje de error
                $error = "Usuario o contraseña incorrectos";
            }
        } else {
            $error = "Usuario o contraseña con caracteres incorrectos";
        }
    }

    // Comprobamos si se trae información de error en el POST de la web
    if (isset($_POST['error'])) {

        // Dependiendo del error devuelto, mostramos un mensaje diferente al usuario
        switch ($_POST['error']) {
            case 1:
                // Mensaje error de validación
                $error = "Debe introducir un usuario y una contraseña válidos";
                break;

            default:
                // Mensaje por defecto
                $error = "Error!. Pongase en contacto con el administrador en la siguiente dirección: $emailAdmin";
                break;
        }
    }
} catch (Exception $ex) {
    // Si se produce una excepción, asignamos el mensaje a la variable de error
    $error = $ex->getMessage();
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" id="login" >
    <head>
        <title>Login</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type = "text/css" rel = "stylesheet" href = "./estilos.css"/>
        <script type="text/javascript" src="HTTP://code.jquery.com/jquery-latest.js"></script>
        <script type="text/javascript" src="scripts/login.js"></script>        
    </head>
    <body>
        <div id="divlogin">
            <form action="login.php" method="post">
                <h3>Acceso de usuario</h3>
                <div>
                    <input type="text" id="user" name="user" maxlength="16" placeholder="Introduzca el usuario" title="Introduzca el usuario para hacer login"/>
                </div>
                <div>
                    <input type="password" id="pass" name="pass" maxlength="16" placeholder="Introduzca la contraseña" title="Introduzca la contraseña para hacer login"/>
                </div>
                <div>
                    <input type="submit" id="submit" name="submit" value="Enviar" alt="enviar" title="Pulse para validar el usuario y contraseña introducidos para hacer login"/>
                </div>
            </form>
            <div id="error">
                <p><?php echo $error ?></p>
            </div>                               
        </div>
    </body>        
</html>

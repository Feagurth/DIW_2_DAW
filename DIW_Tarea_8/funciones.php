<?php

include_once './Db.php';

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

function validarCadenaConNumeros($dato) {
    // Inicializamos la variable de salida al valor que tendría si 
    // toda la validación fuese correcta
    $salida = TRUE;

    // Verificamos con expresiones regulares que los caracteres 
    // introducidos para el remitente son los permitidos
    if (!preg_match("/^[0-9a-zA-ZñÑáÁéÉíÍóÓúÚ ]+$/", $dato)) {
        // Si la validación no se cumple, asignamos el valor 
        // correspondiente a la variable de salida
        $salida = FALSE;
    }

    // Devolvemos la variable con el resultado de la validación
    return $salida;
}

/**
 * Método para validar el usuario y la contraseña de un usuario logeado y actuar 
 * en consecuencia
 * @param type $usuario Usuario a validar
 * @param type $password Contraseña a validar
 * @throws Exception Se lanza una excepción si se ha producido un error
 */
function validarUsuario($usuario, $password) {
    // Comprobamos si tenemos en sesión usuario y password
    if (!isset($usuario) || !isset($password)) {
        // De no ser así volvemos a la página login.php para pedirselos al usuario
        header("location:login.php");
    } else {
        // En caso contrario crearemos una conexión con la base de datos para 
        // verificar el usuario y el password
        try {
            $db = new DB();

            if (!$db->validarUsuario($usuario, $password)) {
                // En el caso de que devuelva cualquier valor distinto de 1, eso 
                // quiere decir que el usuario y la contraseña son erróneos, 
                // por tanto volvemos a la página index.php tras limpiar la sesión
                session_unset();

                // volvemos a la página login.php para hacer que el usuario se valide
                header("location:login.php");
            }
        } catch (Exception $ex) {
            // En el caso de que devuelva cualquier valor distinto de 1, eso 
            // quiere decir que el usuario y la contraseña son erróneos, 
            // por tanto volvemos a la página index.php tras limpiar la sesión
            session_unset();

            // Lanzamos una excepción
            throw $ex;
        }
    }
}

/**
 * Función que nos permite recortar el texto hasta una cantidad de valores establecidos
 * @param string $texto La descripción del nombre del usuario
 * @param int $cantidad La cantidad de caracteres permitidos
 * @return string Una cadena con tantos caracteres como se permitan menos 3 
 * concatenado con 3 puntos suspensivos
 */
function textoElipsis($texto, $cantidad) {

    // Almacenamos el valor del nombre del usuario para trabajar con éñ.
    $ret = $texto;

    // Comprobamos el tamaño del nombre del usuario, si es muy largo lo 
    // recortaremos, si no, lo dejaremos tal como está
    if (strlen($ret) > $cantidad) {
        
        // Cogemos los primeros doce caracteres del nombre del usuario y le 
        // agregamos unos puntos suspensivos para que hagan de elipsis
        $ret = substr($ret, 0, ($cantidad-3)) . "...";
    }

    // Devolvemos la cadena anexándole el nombre de usuario
    return $ret;
}

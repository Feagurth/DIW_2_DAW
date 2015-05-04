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
        $ret = substr($ret, 0, ($cantidad - 3)) . "...";
    }

    // Devolvemos la cadena anexándole el nombre de usuario
    return $ret;
}

/**
 * Función que sirve para habilitar o deshabilitar los controles input de texto 
 * en las páginas de detalle
 * @param string $modo El modo en el que se encuentra la página de detalle
 * @return string Devuelve la palabra disabled para deshabilitar el campo input 
 * si el modo de la página es A (Alta) o M (Modificación)
 */
function deshabilitarPorModo($modo) {

    // Inicializamos la variable de salida
    $salida = "";

    // Comprobamos el modo
    if ($modo !== "M" && $modo !== "A") {
        // Modificamos la variable de salida si la condición se cumple
        $salida = "disabled = 'disabled'";
    }

    // Devolvemos el resultado
    return $salida;
}

/**
 * Función que sirve para habilitar o deshabilitar los botnes
 * en las páginas de detalle
 * @param string $modo El modo en el que se encuentra la página de detalle
 * @return string Devuelve la palabra disabled y añade la clase deshabilitado al objeto 
 * para deshabilitar el botón si el modo de la página es A (Alta) o M (Modificación)
 */
function deshabilitarBotonesPorModo($modo) {
    // Inicializamos la variable de salida
    $salida = "";

    // Comprobamos el modo
    if ($modo === "M" || $modo === "A") {
        // Modificamos la variable de salida si la condición se cumple
        $salida = "disabled = 'disabled' class='deshabilitado'";
    }

    // Devolvemos el resultado
    return $salida;
}

/**
 * Función para validar datos de un empleado
 * @param Empleado $empleado Objeto empleado
 * @return string Cadena vacía si la validación es correcta y un mensaje de 
 * error si no lo es
 */
function validarEmpleado($empleado) {

    // Inicializamos una variable de salida
    $salida = "";

    // Comprobamos si tenemos información de dirección de usuario
    if ($empleado->getDireccion()) {
        // Validamos la dirección del empleado
        // Valida letras y números con espacios en blanco, vocales con acento, 
        // la ñ, puntos y comas y los símbolos ºª
        if (!preg_match("/^[a-zA-Z0-9ñÑ áéíóú.,ºª]+$/", $empleado->getDireccion())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La dirección del empleado contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir una dirección para el empleado.";
    }

    // Comprobamos si tenemos información de cargo de usuario
    if ($empleado->getCargo()) {
        // Validamos el cargo del empleado
        // Valida letras y números con espacios en blanco, vocales con acento y la ñ
        if (!preg_match("/^[a-zA-ZñÑ áéíóú]+$/", $empleado->getCargo())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El cargo del empleado contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un cargo para el empleado.";
    }


    // Comprobamos si tenemos información de especialidad de usuario
    if ($empleado->getEspecialidad()) {
        // Validamos la especialidad del empleado
        // Valida letras y números con espacios en blanco, vocales con acento y la ñ
        if (!preg_match("/^[a-zA-ZñÑ áéíóú]+$/", $empleado->getEspecialidad())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La especialidad del empleado contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir una especialidad para el empleado.";
    }

    // Comprobamos si tenemos información de email de usuario
    if ($empleado->getEmail()) {
        // Validamos el email del empleado
        // Valida letras y números con espacios en blanco, vocales con acento y la ñ
        if (!preg_match("/^(((([a-zA-Z\d][\.\-\+_]?)*)[a-zA-Z0-9])+)\@(((([a-zA-Z\d][\.\-_]?){0,62})[a-z\d])+)\.([a-zA-Z\d]{2,6})$/", $empleado->getEmail())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El E-Mail del empleado contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un E-Mail para el empleado.";
    }


    // Comprobamos si tenemos información de telefono de usuario
    if ($empleado->getTelefono()) {
        // Validamos el teléfono.
        // Valida los patrones  +34 [9|6|7]XX XX XX XX [9|6|7]XX XX XX XX [9|6|7]XX XX XX XX [9|6|7]XX-XX-XX-XX [9|6|7]XXXXXXXX
        if (!preg_match("/^((\+?34([ \t|\-])?)?[9|6|7]((\d{1}([ \t|\-])?[0-9]{3})|(\d{2}([ \t|\-])?[0-9]{2}))([ \t|\-])?[0-9]{2}([ \t|\-])?[0-9]{2})$/", $empleado->getTelefono())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El teléfono del empleado contiene carecteres inválidos o no tiene el formato correcto.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un telefono para el empleado.";
    }

    // Comprobamos si tenemos información de apellido de usuario
    if ($empleado->getApellido()) {
        // Validamos el apellido del empleado
        // Valida letras y números con espacios en blanco, vocales con acento y la ñ
        if (!preg_match("/^[a-zA-ZñÑ áéíóú]+$/", $empleado->getApellido())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El apellido del empleado contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida        
        $salida = "Debe introducir un apellido para el empleado.";
    }

    // Comprobamos si tenemos información de nombre de usuario
    if ($empleado->getNombre()) {
        // Validamos el nombre del empleado
        // Valida letras y números con espacios en blanco, vocales con acento y la ñ
        if (!preg_match("/^[a-zA-ZñÑ áéíóú]+$/", $empleado->getNombre())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El nombre del empleado contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un nombre para el empleado.";
    }

    // Devolvemos el resultado de la validación
    return $salida;
}

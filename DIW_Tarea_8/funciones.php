<?php

include_once './Db.php';
include_once './objetos/GrupoEmpleado.php';
include_once './objetos/Empleado.php';
include_once './objetos/Fichero.php';
include_once './configuracion.inc.php';
require './phpMailer/PHPMailerAutoload.php';

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
 * 
 * 
 */

// <editor-fold defaultstate="collapsed" desc=" Funciones de Validación de Datos ">

/**
 * Función que nos permite validar una cadena para que solo contenga letras y números
 * @package Funciones
 * @param string $dato Cadena a validar
 * @return boolean True si la cadena es correcta, False en caso contrario
 */
function validarCadenaConNumeros($dato) {
    // Inicializamos la variable de salida al valor que tendría si 
    // toda la validación fuese correcta
    $salida = TRUE;

    // Verificamos con expresiones regulares que los caracteres 
    // introducidos para el remitente son los permitidos
    if (!preg_match("/^[0-9a-zA-Z]+$/", $dato)) {
        // Si la validación no se cumple, asignamos el valor 
        // correspondiente a la variable de salida
        $salida = FALSE;
    }

    // Devolvemos la variable con el resultado de la validación
    return $salida;
}

/**
 * Función para validar datos de un empleado
 * @package Funciones
 * @param Empleado $empleado Objeto empleado
 * @return string Cadena vacía si la validación es correcta y un mensaje de 
 * error si no lo es
 */
function validarDatosEmpleado($empleado) {

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
        // Valida patrones como aaaaa@bbbb.com aaa12@bbb.edu   aaaaa.bbbb@ccc.tv
        if (!preg_match("/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/", $empleado->getEmail())) {
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

/**
 * Función para validar datos de un usuario
 * @package Funciones
 * @param Email $email Objeto Email
 * @return string Cadena vacía si la validación es correcta y un mensaje de 
 * error si no lo es
 */
function validarDatosEmail($email) {

    // Inicializamos una variable de salida
    $salida = "";

    // Comprobamos si tenemos información del usuairo del email
    if ($email->getUsuario()) {
        // Validamos el usuario del email
        // Valida letras y números, el punto y la arroba, sin espacios en blanco
        if (!preg_match("/^[a-zA-Z0-9@.]+$/", $email->getUsuario())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La usuario del E-Mail contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un usuario para el E-Mail.";
    }

    // Comprobamos si tenemos información de contraseña de email
    if ($email->getPass()) {
        // Validamos la contraseña del email
        // Valida letras y números
        if (!preg_match("/^[a-zA-Z0-9]+$/", $email->getPass())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La contraseña del E-MAil contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir una contraseña para el E-Mail.";
    }

    // Comprobamos si tenemos información del servidor del email
    if ($email->getServidor()) {
        // Validamos el servidor del email
        // Valida direcciones smpt con el formato smtp.azAz.abc o smtp.azAz.ab    
        if (!preg_match("/^(smtp)\.([\w\-]+)\.[\w\-]{2,3}$/", $email->getServidor())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El servidor de salida del E-Mail contiene caracteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un servidor de salida para el E-Mail.";
    }

    // Comprobamos si tenemos información del puerto del email
    if ($email->getPuerto()) {
        // Validamos el puerto del email
        // Valida solo números
        if (!preg_match("/^[0-9]+$/", $email->getPuerto())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El puerto del E-Mail contiene caracteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un puerto para el E-Mail.";
    }

    // Comprobamos si tenemos información del tipo de seguridad del email
    if ($email->getSeguridad()) {
        // Validamos el tipo de seguridad del email
        // Valida solo letras
        if (!preg_match("/^[a-zA-Z ]+$/", $email->getSeguridad())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El tipo de seguridad del E-Mail contiene caracteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un tipo de seguridad para el E-Mail.";
    }

    // Comprobamos si tenemos información de la descripción del email
    if ($email->getDescripcion()) {
        // Valida letras y números con espacios en blanco, vocales con acento, 
        // la ñ
        if (!preg_match("/^[a-zA-Z0-9ñÑ áéíóú]+$/", $email->getDescripcion())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La descripción del E-Mail contiene caracteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir una descripción para el E-Mail.";
    }

    // Devolvemos el resultado de la validación
    return $salida;
}

/**
 * Función para validar datos de un usuario
 * @package Funciones
 * @param Usuario $usuario Objeto usuario
 * @return string Cadena vacía si la validación es correcta y un mensaje de 
 * error si no lo es
 */
function validarDatosUsuario($usuario) {

    // Inicializamos una variable de salida
    $salida = "";

    // Comprobamos si tenemos información de nombre de usuario
    if ($usuario->getNombre()) {
        // Validamos el nombre del usuario
        // Valida letras y números con espacios en blanco, vocales con acento, 
        // la ñ
        if (!preg_match("/^[a-zA-Z0-9ñÑ áéíóúÁÉÍÓÚ]+$/", $usuario->getNombre())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La descripción del usuario contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir una descripción para el usuario.";
    }

    // Comprobamos si tenemos información de contraseña de usuario
    if ($usuario->getPass()) {
        // Validamos la contraseña del usuario
        // Valida letras y números
        if (!preg_match("/^[a-zA-Z0-9]+$/", $usuario->getPass())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La contraseña del usuario contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir una contraseña para el usuario.";
    }

    // Comprobamos si tenemos información del login del usuario
    if ($usuario->getUser()) {
        // Validamos la especialidad del empleado
        // Valida letras y números con espacios en blanco
        if (!preg_match("/^[a-zA-Z0-9]+$/", $usuario->getUser())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El login del usuario contiene caracteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un login para el usuario.";
    }

    // Devolvemos el resultado de la validación
    return $salida;
}

/**
 * Función que nos permite validar los datos de un objeto Fichero antes de 
 * insertarlo en la base de datos
 * @package Funciones
 * @param Fichero $fichero Objeto con la información del fichero a insertar
 * @return string Un mensaje de error si existe alguno y una cadena vacia en caso contrario
 */
function validarDatosFichero($fichero) {

    // Inicializamos una variable de salida
    $salida = "";

    // Comprobamos si tenemos información de la descripción del fichero
    if ($fichero->getDescripcion()) {
        // Validamos la descripción
        // Valida letras y números con espacios en blanco, vocales con acento, 
        // la ñ y el punto
        if (!preg_match("/^[0-9a-zA-ZñÑáÁéÉíÍóÓúÚ ]+$/", $fichero->getDescripcion())) {

            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La descripción del fichero contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir una descripción para el fichero.";
    }

    // Comprobamos que se haya seleccionado un fichero
    if ($fichero->getFichero() === FALSE) {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe seleccionar un fichero para poder insertarlo.";
    }

    // Devolvemos el resultado de la validación
    return $salida;
}

/**
 * Función para validar datos de un grupo
 * @package Funciones
 * @param Grupo $grupo Objeto grupo
 * @return string Cadena vacía si la validación es correcta y un mensaje de 
 * error si no lo es
 */
function validarDatosGrupo($grupo) {

    // Inicializamos una variable de salida
    $salida = "";

    // Comprobamos si tenemos información de nombre de grupo
    if ($grupo->getNombre()) {
        // Validamos el nombre del grupo
        // Valida letras y números con espacios en blanco, vocales con acento, 
        // la ñ
        if (!preg_match("/^[a-zA-Z0-9ñÑ áéíóú]+$/", $grupo->getNombre())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "El nombre del grupo contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir un nombre para el grupo.";
    }

    // Comprobamos si tenemos información de descripción de grupo
    if ($grupo->getDescripcion()) {
        // Validamos la contraseña del grupo
        // Valida letras y números con espacios en blanco, vocales con acento, 
        // la ñ
        if (!preg_match("/^[a-zA-Z0-9ñÑ áéíóú]+$/", $grupo->getDescripcion())) {
            // Si no se cumple, cambiamos el valor de la variable de salida
            $salida = "La descripción del grupo contiene carecteres inválidos.";
        }
    } else {
        // Si no se cumple, cambiamos el valor de la variable de salida
        $salida = "Debe introducir una descripción para el grupo.";
    }

    // Devolvemos el resultado de la validación
    return $salida;
}

/**
 * Función que nos permite validar los datos de un envío
 * @package Funciones
 * @param Array $grupos Array con los identificadores de grupos seleccionados
 * @param Array $ficheros Array con los identificadores de ficheros seleccionados
 * @param int $id_email Identificador de la cuenta de email desde la que se realizará el envío
 * @return string Cadena vacía si la validación es correcta y un mensaje de 
 * error si no lo es
 */
function validarDatosEnvio($grupos, $ficheros, $id_email) {

    // Inicializamos una variable de salida
    $salida = "";

    // Comprobamos si hay seleccionado algún email.
    if ($id_email === 0) {
        $salida = "Debe seleccionar un email con el que enviar los ficheros.";
    }

    // Comprobamos si se ha seleccionado algún grupo
    if ($grupos === NULL) {
        $salida = "Debe seleccionar un grupo al que enviar los ficheros.";
    }

    // Comprobamos si se ha seleccionado algún fichero
    if ($ficheros === NULL) {
        $salida = "Debe seleccionar un fichero para enviar a los grupos.";
    }

    // Devolvemos el resultado de la validación
    return $salida;
}

// </editor-fold>

/**
 * Método para validar el usuario y la contraseña de un usuario logeado y actuar 
 * en consecuencia
 * @package Funciones
 * @param string $usuario Usuario a validar
 * @param string $password Contraseña a validar
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
 * @package Funciones
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
 * @package Funciones
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
 * @package Funciones
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
 * Función que nos permite estructurar los datos de un objeto fichero
 * @package Funciones
 * @param Fichero $registro El objeto fichero que deseamos estructurar
 * @param id_fichero El identificador de ficher que se asignará al registro
 */
function crearObjetosInserccionFichero(&$registro, $id_fichero) {

    // Si es una insercción, volcamos los valores a insertar en 
    // variables directamente desde el POST de la página
    $descripcion = $_POST['descripcion'];

    // Comprobamos si hay información en los ficheros subidos al 
    // servidor y si se ha producido algún error en la subida de 
    // los mismos
    if (isset($_FILES['addfile'])) {

        // Reordenamos los ficheros que hay en $_FILES para que 
        // nos sea más facil trabajar luego con ellos
        $archivos = ordenarFicheros($_FILES);

        // Recorremos todos los archivos para tratarlos
        foreach ($archivos as $file) {

            // Asignamos el valor de id como valor para el id_fichero
            // al crear el objeto
            $registro->setId_fichero($id_fichero);

            // Le asignamos el nombre recortandolo al tamaño máximo que 
            // permite la tabla donde se almacena
            $registro->setNombre(recortaNombreFichero($file['name'], 50));

            // Le asignamos el tamaño
            $registro->setTamanyo($file['size']);

            // Le asignamos el tipo
            $registro->setTipo($file['type']);

            // Asignamos la descripción
            $registro->setDescripcion($descripcion);

            // Recuperamos la información del fichero con la función 
            // fopen especificando 'rb' como parámetro para que lea 
            // el fichero en binario, guardandolo en una variable 
            // tipo stream y lo asignamos al fichero
            $registro->setFichero(fopen($file['tmp_name'], 'rb'));
        }
    }
}

/**
 * Función que nos permite reordenar los ficheros subidos al servidor y 
 * alojados en $_FILES dandoles una estructura más comoda para procesarlos
 * @package Funciones
 * @param Array $ficheros Los ficheros alojados en $_FILES
 * @return Array Un array con la información de los ficheros ordenada por fichero
 */
function ordenarFicheros($ficheros) {
    // Creamos un nuevo array para almacenar los datos y devolverlos 
    // posteriormente
    $salida = array();

    // Comprobamos y almacenamos el número de ficheros que se han subido
    $cuenta = count($ficheros['addfile']['name']);

    // Recuperamos las claves del array de ficheros
    $claves = array_keys($ficheros['addfile']);

    // Iteramos tantas veces como ficheros haya
    for ($i = 0; $i < $cuenta; $i++) {

        // Iteramos por todas las claves que hay en el array de entrada
        foreach ($claves as $clave) {

            // Asignamos al fichero de salida cada uno de las claves del 
            // array de entrada para cada iteración de ficheros
            $salida[$i][$clave] = $ficheros['addfile'][$clave][$i];
        }
    }

    // Finalmente devolvemos el resultado
    return $salida;
}

/**
 * Función que nos permite comprobar si un empleado forma parte de un grupo
 * @package Funciones
 * @param string $id_empleado Identificador del empleado
 * @param GrupoEmpleado[] $datosgrupoempleado Array de objetos GrupoEmpleado con la información de las relaciones
 * @return bool True si pertenece al grupo, False en caso contrario
 */
function comprobarRelaccionEmpleadoGrupo($id_empleado, $datosgrupoempleado) {

    // Buscamos en el array de objetos GrupoEmpleado usando una función que 
    // compara el id_empleado de cada objeto que forma parte del array con 
    // el id del empleado que estamos buscando
    $salida = array_filter(
            $datosgrupoempleado, function ($e) use (&$id_empleado) {
        return $e->getId_empleado() == $id_empleado;
    }
    );

    // Como array_filter devuelve un array, comprobamos su tamaño, si es 0, 
    // no ha encontrado resultados, si es mayor que cero, tenemos resultados.
    // Tal como está diseñada la aplicación la cantidad de registros devueltos 
    // por la comparación tendrá un maximo de un registro
    return sizeof($salida) > 0 ? TRUE : FALSE;
}

/**
 * Función que nos permite crear de forma dinámica una tabla con la información 
 * de las relaciones entre empleados y grupos
 * @package Funciones
 * @param int $id_grupo Identificador del grupo del que se mirarán las relacciones
 * @param string $error Variable para almacenar los mensajes de error
 */
function crearTablaRelacionesEmpleados($id_grupo, &$error) {


    // Creamos una instancia de la base de datos
    $db = new DB();

    // Recuperamos todos los empleados
    $empleados = $db->listarEmpleados("", "");

    // Recuperamos las relaciones de los empleados con el grupo que 
    // usaremos más adelante con la función comprobarRelaccionEmpleadoGrupo 
    // para marcar los checkboxes de los empleados que formen parte 
    // del grupo
    $grupoempleado = $db->listarRelacionesGrupoEmpleados($id_grupo);

    // Creamos un div y un formalario que contendrán el listado
    echo '<div class="listadoSel">';

    echo '<h2>Empleados integrantes del grupo</h2>';

    echo '<form action="grupo_detalle.php" method="post">';

    // A continuación definimos la estructura de la tabla y su cabecera
    echo '<table>';
    echo '<thead>';
    echo '<tr>';
    echo '<td class="listadoCabecera">Nombre</td>';
    echo '<td class="listadoCabecera">Apellido</td>';
    echo '<td class="listadoCabecera">Teléfono</td>';
    echo '<td class="listadoCabecera">Especialidad</td>';
    echo '<td class="listadoCabecera">Cargo</td>';
    echo '<td class="listadoCabecera">Dirección</td>';
    echo '<td class="listadoCabecera">E-Mail</td>';
    echo '<td>Selección</td>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';


    // Verificamos si tenemos algún tipo de error
    if ($error === "") {
        // Inicializamos un contador para asignar los estilos a cada linea
        $i = 0;

        // Recorremos cada uno de los registros que hemos recuperado 
        foreach ($empleados as $empleado) {

            // Si el contador es un número par, le daremos un estilo y si 
            // es impar le daremos otro
            if ($i % 2 === 0) {
                echo '<tr class="pijama1">';
            } else {
                echo '<tr class="pijama2">';
            }

            // Imprimimos celda con los valores recuperados de cada objeto 
            // empleado que hay en los registros recuperados
            echo '<td title="' . $empleado->getNombre() . '">' . textoElipsis($empleado->getNombre(), 15) . '</td>';
            echo '<td title="' . $empleado->getApellido() . '">' . textoElipsis($empleado->getApellido(), 15) . '</td>';
            echo '<td title="' . $empleado->getTelefono() . '">' . textoElipsis($empleado->getTelefono(), 15) . '</td>';
            echo '<td title="' . $empleado->getEspecialidad() . '">' . textoElipsis($empleado->getEspecialidad(), 15) . '</td>';
            echo '<td title="' . $empleado->getCargo() . '">' . textoElipsis($empleado->getCargo(), 15) . '</td>';
            echo '<td title="' . $empleado->getDireccion() . '">' . textoElipsis($empleado->getDireccion(), 15) . '</td>';
            echo '<td title="' . $empleado->getEmail() . '">' . textoElipsis($empleado->getEmail(), 15) . '</td>';

            // Añadimos una última fila con un botón un checkbox para marcar una relación con el grupo actual
            echo '<td>';
            echo '<input type="checkbox" name="seleccionadoEmpleado[]" title="Haga click para agregar el empleado al grupo" tabindex="20" ';
            if (comprobarRelaccionEmpleadoGrupo($empleado->getId_empleado(), $grupoempleado)) {
                echo 'checked="checked"';
            }
            echo " value='" . $empleado->getId_empleado() . "' />";
            echo '</td>';
            echo '</tr>';

            // Incrementamos el contador
            $i++;
        }
    }

    // Cerramos el cuerpo de la tabla
    echo '</tbody>';

    // Cerramos la tabla
    echo '</table>';

    // Creamos dos campos ocultos que enviarán el modo de la página cuando 
    // se pulse el botón de actualizar las relaciones de los empleados y el 
    // identificador del grupo en el que estamos actualmente para que se 
    // refresquen sus datos
    echo '<input class="oculto" name="modo" type="hidden" value="AE" />';
    echo '<input class="oculto" name="id_grupo" type="hidden" value="' . $id_grupo . '" />';

    // Creamos el botón de actualizar las relaciones con los empleados
    echo '<input type="submit" id="actualizar_empleados" tabindex="21" value="Actualizar Relaciones Empleados" title="Pulse para actualizar la relación de los empleados con el grupo" />';

    // Y finalmente cerramosel formulario
    echo '</form>';
    echo '</div>';
}

/**
 * Función que nos permite generar un número aleatorio como indicador de la sesión.
 * @package Funciones
 * @return int Un número aleatorio entre 1 y 100000
 */
function generarTokenSesion() {
    return rand(1, 100000);
}

/**
 * Función que nos permite comprobar la sesión en la que nos encontramos y 
 * de este modo poder controlar insercciones extras por refrescos de páginas
 * @package Funciones
 * @return boolean True si la sesión es correcta, False si no lo es
 */
function comprobarTokenSesion() {

    // Comprobamos que el token de sesión es el mismo que el token que se envía 
    // por post. Estos números serán iguales si en envío de los datos del post 
    // no es un refresco de página
    if ($_SESSION['token'] == $_POST['token']) {

        // Se genera un nuevo número aleatorio de sesión con el fin que si ahora
        // se refresca la página no coincidirá el nuevo número con el recibido
        $_SESSION['token'] = generarTokenSesion();

        // Si es todo correcto devolvemos True
        return TRUE;
    } else {
        // En caso contrario, False
        return FALSE;
    }
}

/**
 * Función que nos permite recortar el nombre de un fichero para ajustarlo al 
 * tamaño máximo especificado sin perder la extensión del mismo
 * @package Funciones
 * @param string $nombrefichero Nombre del fichero a modificar
 * @param int $tamañoMax Tamaño máximo que puede tener el nombre del fichero
 * @return string El nombre del fichero reducido a su tamaño máximo
 */
function recortaNombreFichero($nombrefichero, $tamañoMax) {

    // Localizamos el primer punto que haya en la cadena empezando por detrás.
    $pos = strrpos($nombrefichero, '.', -1);

    // Todo lo que haya entre la posición siguiente al punto y el final de la 
    // cadena será la extensión del archivo
    $ext = substr($nombrefichero, $pos + 1, (strlen($nombrefichero) - $pos));

    // El nombre del fichero será desde el inicio de la cadena hasta la posición 
    // del punto
    $nombre = substr($nombrefichero, 0, $pos);

    // Recortamos el nombre del fichero hasta el tamaño máximo menos el tamaño 
    // de la extensión más uno, correspondiente al punto de la extensión
    $nombre = substr($nombre, 0, ($tamañoMax - (strlen($ext) + 1)));

    // Concatenamos todo y lo devolvemos como resultado
    return $nombre . '.' . $ext;
}

/**
 * Función que nos permite grabar un fichero con un nombre y una extensión 
 * específica
 * @package Funciones
 * @param string $nombre Nombre del fichero a grabar
 * @param stream $datos Datos que contendrá el fichero codificados en binario
 * @return boolean TRUE si la operación es correcta, FALSE en caso contrario
 */
function grabarFichero($nombre, $datos) {

    // Definimos una variable de salida con TRUE como valor específico. 
    // Si algo sale mal, se cambiará el valor de la variable y se devolverá 
    // como resultado
    $salida = TRUE;

    // Abrimos el fichero
    $file = fopen($nombre, "w");

    // Si se ha abierto correctamente
    if ($file) {
        // Escribimos en el fichero en contenido 
        // del archivo recuperado de la base de 
        // datos
        if (!fwrite($file, $datos)) {
            // Si no se han grabado los datos de forma correcta, 
            // cambiamos el valor de la variable de salida
            $salida = FALSE;
        }
        // Cerramos el fichero
        fclose($file);
    } else {
        // Si no se ha abierto de forma correcta, cambiamos 
        // el valor de la variable de salida
        $salida = FALSE;
    }

    // Devolvemos el resultado de la operación
    return $salida;
}

/**
 * Función que nos permite enviar un fichero por correo electrónico a varios empleados usando un email
 * @package Funciones
 * @global string $emailAdmin Email del administrador
 * @global string $nameAdmin Nombre del administrador
 * @param Email $email Objeto Email que contiene la información de configuracion del mismo
 * @param Fichero $fichero Objeto Fichero con la información del fichero
 * @param Empleado[] $empleados Array de objetos Empleado que contiene la información de los empleados
 * @throws Exception Se lanza una excepción si se produce un error
 */
function enviarCorreo(Email $email, Fichero $fichero, $empleados) {

    try {

        // Recuperamos el los datos de contacto del administrador
        global $emailAdmin;
        global $nameAdmin;

        //Creamos una instacia de PHPMailer
        $mail = new PHPMailer;

        // Configuración de debug
        // 0 -> Producción
        // 1 -> Mensajes Cliente
        // 2 -> Mensajes Servidor
        $mail->SMTPDebug = 2;

        // Salida de debug en modo html
        $mail->Debugoutput = 'html';

        //Indicamos que use SMTP
        $mail->isSMTP();

        // Cambiamos el código de caracteres a UTF-8
        $mail->CharSet = 'UTF-8';

        // Especificamos el nombre del servidor
        $mail->Host = $email->getServidor();

        // Especificamos el puerto de conexión
        $mail->Port = $email->getPuerto();

        // Especificamos el tipo de seguridad
        $mail->SMTPSecure = strtolower($email->getPuerto());

        // Indicamos si la conexión tiene que estar autentificada
        $mail->SMTPAuth = $email->getAutentificacion() === "1" ? TRUE : FALSE;

        // Si hay que autentificarse
        if ($mail->SMTPAuth) {
            // Especificamos el usuario
            $mail->Username = $email->getUsuario();

            // Y la contraseña
            $mail->Password = $email->getPass();
        }

        // Definimos la dirección de la persona que envía el correo con los 
        // datos del administrador
        $mail->setFrom($emailAdmin, $nameAdmin);


        // Itermaos por los empleados para añadir sus direcciones de correo a 
        // la lista de enviados
        foreach ($empleados as $empleado) {
            $mail->addAddress($empleado->getEmail(), $empleado->getNombre() . " " . $empleado->getApellido());     // Add a recipient            
        }


        // Ponemos el título del email
        $mail->Subject = 'Documento de su interés';

        // Especificamos el cuerpo del mensaje
        $mail->Body = '<b>Se le ha enviado este documento que puede ser de su interés</b>';

        // Especificamos el cuerpo del mensaje alternativo para dispositivos que no soporten HTML
        $mail->AltBody = 'Se le ha enviado este documento que puede ser de su interés';

        // Grabamos el fichero en el disco duro
        grabarFichero($fichero->getNombre(), $fichero->getFichero());

        // Lo añadimos como adjunto
        $mail->addAttachment($fichero->getNombre());

        // Enviamos el mensaje
        if (!$mail->send()) {
            // Si no puede enviar el mensaje, lanzamos una excepción
            throw new Exception("Se ha producido un erroe al enviar el E-Mail. Error: " . $mail->ErrorInfo);
        }

        unlink($fichero->getNombre());
    } catch (Exception $ex) {
        // Si se produce alguna excepción la lanzamos
        throw $ex;
    }
}

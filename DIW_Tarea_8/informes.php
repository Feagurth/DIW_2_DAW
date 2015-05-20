<?php
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

require_once './Db.php';

try {

    // Recuperamos los valores de los filtros
    $filtro = (isset($_POST['filtro']) ? $_POST['filtro'] : "");
    $tipoFiltro = (isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : "1");
    $tipoOrden = (isset($_POST['tipoOrden']) ? $_POST['tipoOrden'] : "1");
    $tipoInforme = (isset($_POST['tipoInforme']) ? $_POST['tipoInforme'] : "");
    $ascendente = (isset($_POST['ordenAscendente']) ? "1" : "0");

    // Inicializamos las variables de columnas y de error
    $columnas = "";
    $error = "";


    // Dependiendo del tipo de informe que se pida, se cargarán unos valores u otros
    switch ($tipoInforme) {

        // En el caso de un informe de usuarios
        case "usuario": {

                // Definimos el texto de la cabecera
                $textoCabecera = "Usuarios";

                // Definimos las columnas que tiene la tabla y que se mostrarán 
                // en los desplegables de orden y filtro
                $columnas = ["Usuario", "Nombre"];

                // Comprobamos si hay información de columnas seleccionadas 
                // para el informe, lo que significa que se ha realizado una 
                // petición de filtro y que tendremos que devolver datos al 
                // usuario
                if (isset($_POST['columnas'])) {

                    // Creamos dos arrays que contendrán el nombre de las 
                    // columnas a recuperar en la sentencia SQL y el nombre que 
                    // se mostrará para el campo en la columna de la tabla de 
                    // resultados
                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    // Iteramos por todos los valores de columnas pulsados por el usuario
                    foreach ($_POST['columnas'] as $valColumna) {

                        // Dependiendo del valor de la columna pulsada añadiremos unos valores y otros
                        switch ($valColumna) {
                            case 1: {
                                    array_push($columnasFiltro, 'user');
                                    array_push($cabeceraColumnas, 'Usuario');

                                    break;
                                }
                            case 2: {
                                    array_push($columnasFiltro, 'nombre');
                                    array_push($cabeceraColumnas, 'Nombre');
                                    break;
                                }
                        }
                    }

                    // Creamos una nueva instancia de la base de datos
                    $db = new DB();

                    // Llamamos a la función de crear informes para usuario, pasándole los parámetros que hemos creado
                    $datos = $db->listarInformeUsuario($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                } else {
                    // Comprobamos si se ha realizado una petición
                    if (isset($_POST['tipoFiltro'])) {
                        // Si no se ha seleccionado ninguna columna, mostramos un mensaje de error
                        $error = "Debe seleccionar al menos una columna para poder visualizar un informe";
                    }
                }

                break;
            }

        // En el caso de un informe de empleados
        case 'empleado': {

                // Definimos el texto de la cabecera
                $textoCabecera = "Empleados";

                // Definimos las columnas que tiene la tabla y que se mostrarán 
                // en los desplegables de orden y filtro                
                $columnas = ["Nombre", "Apellido", "Teléfono", "Especialidad", "Cargo", "Dirección", "E-Mail"];

                // Comprobamos si hay información de columnas seleccionadas 
                // para el informe, lo que significa que se ha realizado una 
                // petición de filtro y que tendremos que devolver datos al 
                // usuario                                
                if (isset($_POST['columnas'])) {

                    // Creamos dos arrays que contendrán el nombre de las 
                    // columnas a recuperar en la sentencia SQL y el nombre que 
                    // se mostrará para el campo en la columna de la tabla de 
                    // resultados                    
                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    // Iteramos por todos los valores de columnas pulsados por el usuario
                    foreach ($_POST['columnas'] as $valColumna) {

                        // Dependiendo del valor de la columna pulsada añadiremos unos valores y otros
                        switch ($valColumna) {
                            case 1: {
                                    array_push($columnasFiltro, 'nombre');
                                    array_push($cabeceraColumnas, 'Nombre');
                                    break;
                                }
                            case 2: {
                                    array_push($columnasFiltro, 'apellido');
                                    array_push($cabeceraColumnas, 'Apellido');
                                    break;
                                }
                            case 3: {
                                    array_push($columnasFiltro, 'telefono');
                                    array_push($cabeceraColumnas, 'Teléfono');
                                    break;
                                }
                            case 4: {
                                    array_push($columnasFiltro, 'especialidad');
                                    array_push($cabeceraColumnas, 'Especialidad');
                                    break;
                                }
                            case 5: {
                                    array_push($columnasFiltro, 'cargo');
                                    array_push($cabeceraColumnas, 'Cargo');
                                    break;
                                }
                            case 6: {
                                    array_push($columnasFiltro, 'direccion');
                                    array_push($cabeceraColumnas, 'Dirección');
                                    break;
                                }
                            case 7: {
                                    array_push($columnasFiltro, 'email');
                                    array_push($cabeceraColumnas, 'E-Mail');
                                    break;
                                }
                        }
                    }

                    // Creamos una nueva instancia de la base de datos
                    $db = new DB();

                    // Llamamos a la función de crear informes para empleado, pasándole los parámetros que hemos creado
                    $datos = $db->listarInformeEmpleado($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                } else {
                    // Comprobamos si se ha realizado una petición
                    if (isset($_POST['tipoFiltro'])) {
                        // Si no se ha seleccionado ninguna columna, mostramos un mensaje de error
                        $error = "Debe seleccionar al menos una columna para poder visualizar un informe";
                    }
                }

                break;
            }

        // En el caso de un informe de E-Mails
        case "email": {

                // Definimos el texto de la cabecera
                $textoCabecera = "E-Mails";

                // Definimos las columnas que tiene la tabla y que se mostrarán 
                // en los desplegables de orden y filtro                
                $columnas = ["Descripción", "Usuario", "Servidor", "Puerto", "Seguridad"];

                // Comprobamos si hay información de columnas seleccionadas 
                // para el informe, lo que significa que se ha realizado una 
                // petición de filtro y que tendremos que devolver datos al 
                // usuario                
                if (isset($_POST['columnas'])) {

                    // Creamos dos arrays que contendrán el nombre de las 
                    // columnas a recuperar en la sentencia SQL y el nombre que 
                    // se mostrará para el campo en la columna de la tabla de 
                    // resultados                    
                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    // Iteramos por todos los valores de columnas pulsados por el usuario
                    foreach ($_POST['columnas'] as $valColumna) {

                        // Dependiendo del valor de la columna pulsada añadiremos unos valores y otros
                        switch ($valColumna) {
                            case 1: {
                                    array_push($columnasFiltro, 'descripcion');
                                    array_push($cabeceraColumnas, 'Descripción');

                                    break;
                                }
                            case 2: {
                                    array_push($columnasFiltro, 'usuario');
                                    array_push($cabeceraColumnas, 'Usuario');
                                    break;
                                }
                            case 3: {
                                    array_push($columnasFiltro, 'servidor');
                                    array_push($cabeceraColumnas, 'Servidor');
                                    break;
                                }
                            case 4: {
                                    array_push($columnasFiltro, 'puerto');
                                    array_push($cabeceraColumnas, 'Puerto');
                                    break;
                                }
                            case 5: {
                                    array_push($columnasFiltro, 'seguridad');
                                    array_push($cabeceraColumnas, 'Seguridad');
                                    break;
                                }
                        }
                    }

                    // Creamos una nueva instancia de la base de datos
                    $db = new DB();

                    // Llamamos a la función de crear informes para E-Mails, pasándole los parámetros que hemos creado
                    $datos = $db->listarInformeEmail($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                } else {
                    // Comprobamos si se ha realizado una petición
                    if (isset($_POST['tipoFiltro'])) {
                        // Si no se ha seleccionado ninguna columna, mostramos un mensaje de error
                        $error = "Debe seleccionar al menos una columna para poder visualizar un informe";
                    }
                }



                break;
            }

        // En el caso de un informe de documentos
        case "fichero": {

                // Definimos el texto de la cabecera
                $textoCabecera = "Documentos";

                // Definimos las columnas que tiene la tabla y que se mostrarán 
                // en los desplegables de orden y filtro                
                $columnas = ["Nombre", "Tamaño", "Tipo", "Descripción"];

                // Comprobamos si hay información de columnas seleccionadas 
                // para el informe, lo que significa que se ha realizado una 
                // petición de filtro y que tendremos que devolver datos al 
                // usuario
                if (isset($_POST['columnas'])) {

                    // Creamos dos arrays que contendrán el nombre de las 
                    // columnas a recuperar en la sentencia SQL y el nombre que 
                    // se mostrará para el campo en la columna de la tabla de 
                    // resultados                    
                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    // Iteramos por todos los valores de columnas pulsados por el usuario
                    foreach ($_POST['columnas'] as $valColumna) {

                        // Dependiendo del valor de la columna pulsada añadiremos unos valores y otros
                        switch ($valColumna) {
                            case 1: {
                                    array_push($columnasFiltro, 'nombre');
                                    array_push($cabeceraColumnas, 'Nombre');

                                    break;
                                }
                            case 2: {
                                    array_push($columnasFiltro, 'tamanyo');
                                    array_push($cabeceraColumnas, 'Tamaño');
                                    break;
                                }
                            case 3: {
                                    array_push($columnasFiltro, 'tipo');
                                    array_push($cabeceraColumnas, 'Tipo');
                                    break;
                                }
                            case 4: {
                                    array_push($columnasFiltro, 'descripcion');
                                    array_push($cabeceraColumnas, 'Descripción');
                                    break;
                                }
                        }
                    }

                    // Creamos una nueva instancia de la base de datos
                    $db = new DB();

                    // Llamamos a la función de crear informes para documentos, pasándole los parámetros que hemos creado
                    $datos = $db->listarInformeFichero($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                } else {
                    // Comprobamos si se ha realizado una petición
                    if (isset($_POST['tipoFiltro'])) {
                        // Si no se ha seleccionado ninguna columna, mostramos un mensaje de error
                        $error = "Debe seleccionar al menos una columna para poder visualizar un informe";
                    }
                }



                break;
            }

        // En el caso de un informe de envíos
        case "envio": {

                // Definimos el texto de la cabecera
                $textoCabecera = "Envíos";

                // Definimos las columnas que tiene la tabla y que se mostrarán 
                // en los desplegables de orden y filtro                
                $columnas = ["Fecha", "E-Mail", "Nombre Empleado", "Apellido Empleado", "E-Mail Empleado", "Cargo Empleado", "Descripción Fichero", "Grupo"];

                // Comprobamos si hay información de columnas seleccionadas 
                // para el informe, lo que significa que se ha realizado una 
                // petición de filtro y que tendremos que devolver datos al 
                // usuario                
                if (isset($_POST['columnas'])) {

                    // Creamos dos arrays que contendrán el nombre de las 
                    // columnas a recuperar en la sentencia SQL y el nombre que 
                    // se mostrará para el campo en la columna de la tabla de 
                    // resultados                    
                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    // Iteramos por todos los valores de columnas pulsados por el usuario
                    foreach ($_POST['columnas'] as $valColumna) {

                        // Dependiendo del valor de la columna pulsada añadiremos unos valores y otros
                        switch ($valColumna) {
                            case 1: {
                                    array_push($columnasFiltro, 'e.fecha_envio');
                                    array_push($cabeceraColumnas, 'Fecha');

                                    break;
                                }
                            case 2: {
                                    array_push($columnasFiltro, 'em.descripcion');
                                    array_push($cabeceraColumnas, 'E-Mail');
                                    break;
                                }
                            case 3: {
                                    array_push($columnasFiltro, 'emp.nombre');
                                    array_push($cabeceraColumnas, 'Nombre Empleado');
                                    break;
                                }
                            case 4: {
                                    array_push($columnasFiltro, 'emp.apellido');
                                    array_push($cabeceraColumnas, 'Apellido Empleado');
                                    break;
                                }
                            case 5: {
                                    array_push($columnasFiltro, 'emp.email');
                                    array_push($cabeceraColumnas, 'E-Mail Empleado');
                                    break;
                                }
                            case 6: {
                                    array_push($columnasFiltro, 'emp.cargo');
                                    array_push($cabeceraColumnas, 'Cargo Empleado');
                                    break;
                                }
                            case 7: {
                                    array_push($columnasFiltro, 'f.descripcion');
                                    array_push($cabeceraColumnas, 'Descripción Fichero');
                                    break;
                                }
                            case 8: {
                                    array_push($columnasFiltro, 'g.nombre');
                                    array_push($cabeceraColumnas, 'Grupo');
                                    break;
                                }
                        }
                    }

                    // Creamos una nueva instancia de la base de datos
                    $db = new DB();

                    // Llamamos a la función de crear informes para envíos, pasándole los parámetros que hemos creado
                    $datos = $db->listarInformeEnvio($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                } else {
                    // Comprobamos si se ha realizado una petición
                    if (isset($_POST['tipoFiltro'])) {
                        // Si no se ha seleccionado ninguna columna, mostramos un mensaje de error
                        $error = "Debe seleccionar al menos una columna para poder visualizar un informe";
                    }
                }



                break;
            }
        // En el caso de un informe de grupos
        case "grupo": {

                // Definimos el texto de la cabecera
                $textoCabecera = "Grupos";

                // Definimos las columnas que tiene la tabla y que se mostrarán 
                // en los desplegables de orden y filtro                
                $columnas = ["Descripción Grupo", "Nombre Grupo", "Nombre Empleado", "Apellido Empleado", "Teléfono Empleado", "Cargo Empleado", "Especialidad Empleado", "E-Mail Empleado"];

                // Comprobamos si hay información de columnas seleccionadas 
                // para el informe, lo que significa que se ha realizado una 
                // petición de filtro y que tendremos que devolver datos al 
                // usuario                
                if (isset($_POST['columnas'])) {

                    // Creamos dos arrays que contendrán el nombre de las 
                    // columnas a recuperar en la sentencia SQL y el nombre que 
                    // se mostrará para el campo en la columna de la tabla de 
                    // resultados                    
                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    // Iteramos por todos los valores de columnas pulsados por el usuario
                    foreach ($_POST['columnas'] as $valColumna) {

                        // Dependiendo del valor de la columna pulsada añadiremos unos valores y otros
                        switch ($valColumna) {
                            case 1: {
                                    array_push($columnasFiltro, 'p.descripcion');
                                    array_push($cabeceraColumnas, 'Descripción Grupo');

                                    break;
                                }
                            case 2: {
                                    array_push($columnasFiltro, 'p.nombre');
                                    array_push($cabeceraColumnas, 'Nombre Grupo');
                                    break;
                                }
                            case 3: {
                                    array_push($columnasFiltro, 'e.nombre');
                                    array_push($cabeceraColumnas, 'Nombre Empleado');
                                    break;
                                }
                            case 4: {
                                    array_push($columnasFiltro, 'e.apellido');
                                    array_push($cabeceraColumnas, 'Apellido Empleado');
                                    break;
                                }
                            case 5: {
                                    array_push($columnasFiltro, 'e.telefono');
                                    array_push($cabeceraColumnas, 'Teléfono Empleado');
                                    break;
                                }
                            case 6: {
                                    array_push($columnasFiltro, 'e.especialidad');
                                    array_push($cabeceraColumnas, 'Especialidad Empleado');
                                    break;
                                }

                            case 7: {
                                    array_push($columnasFiltro, 'e.cargo');
                                    array_push($cabeceraColumnas, 'Cargo Empleado');
                                    break;
                                }
                            case 8: {
                                    array_push($columnasFiltro, 'e.direccion');
                                    array_push($cabeceraColumnas, 'Dirección Empleado');
                                    break;
                                }
                            case 9: {
                                    array_push($columnasFiltro, 'e.email');
                                    array_push($cabeceraColumnas, 'E-Mail Empleado');
                                    break;
                                }
                        }
                    }

                    // Creamos una nueva instancia de la base de datos
                    $db = new DB();

                    // Llamamos a la función de crear informes para grupos, pasándole los parámetros que hemos creado
                    $datos = $db->listarInformeGrupo($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                } else {
                    // Comprobamos si se ha realizado una petición
                    if (isset($_POST['tipoFiltro'])) {
                        // Si no se ha seleccionado ninguna columna, mostramos un mensaje de error
                        $error = "Debe seleccionar al menos una columna para poder visualizar un informe";
                    }
                }


                break;
            }
    }
} catch (Exception $ex) {

    // Si se produce algún error, lo capturamos y pasamos el mensaje del mismo a la variable de error
    $error = $ex->getMessage();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
    <head>
        <title>Informes</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type = "text/css" rel = "stylesheet" href = "./estilos.css"/>
    </head>
    <body id="informe">
        <div class="cabecera" id="index" >
            <h1>Informes de <?php echo $textoCabecera ?></h1>
        </div>
        <div class="error">
            <p><?php echo $error ?></p>
        </div>
        <div id="botonera">

            <form id="filtro" action='informes.php' method='post' >
                <div id="tablas">
                    <?php
                    // Inicializamos una variable que nos servirá para dar 
                    // valor a cada uno de los checkboxes creados
                    $i = 1;

                    // Iteramos por todas las columnas que se han definido 
                    // dependiendo del tipo de informe que se haya creado
                    foreach ($columnas as $columna) {

                        // Creamos el checkbox dentro de una etiqueta
                        echo "<label title='Seleccione la tabla para que se muestre en el informe'>"
                        . "<input tabindex='3' type='checkbox' name='columnas[]' title='Seleccione la tabla para que se muestre en el informe' value='" . $i . "' ";

                        // Comprobamos si el valor del checkbox creado se 
                        // corresponde con el valor de columnas que se nos 
                        // devuelve en el post, lo que significaría que 
                        // el usuario había seleccionado previamente el checkbox
                        if (isset($_POST['columnas'])) {
                            if (in_array($i, $_POST['columnas'])) {
                                echo "checked='checked'";
                            }
                        }

                        // Cerramos el checkbox y la etiqueta
                        echo " />" . $columna . "</label>";

                        // Incrementamos el valor de la variable
                        $i++;
                    }
                    ?>                                    
                </div>                
                <br />
                <div id="controles">
                    <input type="hidden" name="tipoInforme" id="tipoInforme" value="<?php echo $tipoInforme ?>" />
                    <input type='submit' tabindex="11" value='Filtrar resultados' title="Pulse el botón para filtrar los resultados"/>            
                    <select name="tipoFiltro" tabindex="10" title="Seleccione el tipo de filtro">
                        <?php
                        // Creamos una variable que nos servirá para dar valor 
                        // a cada una de las opciones de selección que iremos 
                        // creando
                        $i = 1;

                        // Iteramos por todas las columnas que se han definido 
                        // dependiendo del tipo de informe que se haya creado
                        foreach ($columnas as $columna) {

                            // Creamos la opción
                            echo "<option ";

                            // Verificamos si el valor de la opción es igual al 
                            // valor del tipo de filtro, lo que quiere decir 
                            // que anteriormente el usuario seleccionó esta 
                            // opción al realizar la consulta, y por tanto lo 
                            // seleccionamos
                            if ($tipoFiltro == $i) {
                                echo "selected=\"selected\" ";
                            }

                            // Acabamos la creación de la opción
                            echo "value='" . $i . "'>" . $columna . "</option>";

                            // Aumentamos el contador
                            $i++;
                        }
                        ?>

                    </select>

                    <input id="textoFiltro" tabindex="9" type="text" maxlength="30" title="Introduzca la cadena por la que filtrar los resultados" name="filtro"  value="<?php echo $filtro ?>" />
                    <label><input tabindex="8" type="checkbox" name="ordenAscendente" id="ordenAscendente" title="Pulse para ordenar de forma ascendente" <?php if ($ascendente === '1') echo "checked='checked'"; ?> />Ascendente</label>
                    <select name="tipoOrden" id="tipoOrden" title="Seleccione una columna por la que ordenar el informe" tabindex="7">
                        <?php
                        // Creamos una variable que nos servirá para dar valor 
                        // a cada una de las opciones de selección que iremos 
                        // creando                             
                        $i = 1;

                        // Iteramos por todas las columnas que se han definido 
                        // dependiendo del tipo de informe que se haya creado                        
                        foreach ($columnas as $columna) {

                            // Creamos la opción
                            echo "<option ";

                            // Verificamos si el valor de la opción es igual al 
                            // valor del tipo de orden, lo que quiere decir 
                            // que anteriormente el usuario seleccionó esta 
                            // opción al realizar la consulta, y por tanto lo 
                            // seleccionamos                            
                            if ($tipoOrden == $i) {
                                echo "selected=\"selected\" ";
                            }

                            // Acabamos la creación de la opción                            
                            echo "value='" . $i . "'>" . $columna . "</option>";

                            // Aumentamos el contador
                            $i++;
                        }
                        ?>                
                    </select>
                    <label for="tipoOrden">Ordenado por: </label>
                </div>
                <br />
            </form>        
        </div>
        <div id="listadoInforme" class="listado">

            <?php
            // Comprobamos si tenemos datos de una consulta
            if (isset($datos)) {

                // Si es asi definimos una tabla
                echo "<table>";
                echo "<thead>";
                echo "<tr>";

                // Iteramos por las descripciones de las columnas que ha 
                // seleccionado el usuario
                foreach ($cabeceraColumnas as $columna) {
                    echo "<td>" . $columna . "</td>";
                }

                // Cerramos la cabecera de la tabla y abrimos el cuerpo de la misma
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                // Creamos una variable para ayudarnos a definir las clases pijama
                $i = 0;

                // Itermaos por todas las filas de datos que tenemos
                foreach ($datos as $fila) {

                    // Comprobamos si la fila es par
                    if ($i % 2 === 0) {
                        // Si es par, creamos una fila nueva y le asignamos la clase pijama1
                        echo "<tr class='pijama1'>";
                    } else {
                        // Si es impar, creamos una fila nueva y le asignamos la clase pijama2
                        echo "<tr class='pijama2'>";
                    }

                    // Iteramos por todas las columnas que hay en cada fila de los datos
                    foreach ($fila as $valor) {
                        // Rellenamos las columnas con los valores
                        echo "<td>" . $valor . "</td>";
                    }

                    // Cerramos la fila
                    echo "</tr>";

                    // Incrementamos el contador
                    $i++;
                }

                // Finalmente cerramos el cuerpo y la tabla
                echo "</tbody>";
                echo "</table>";
            }
            ?>
        </div>
    </body>
</html>
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

    $columnas = "";

    $error = "";



    switch ($tipoInforme) {
        case "usuario": {
                $textoCabecera = "Usuarios";

                $columnas = ["Usuario", "Nombre"];


                if (isset($_POST['columnas'])) {

                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    foreach ($_POST['columnas'] as $valColumna) {

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

                    $db = new DB();


                    $datos = $db->listarInformeUsuario($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                }


                break;
            }

        case 'empleado': {
                $textoCabecera = "Empleados";

                $columnas = ["Nombre", "Apellido", "Teléfono", "Especialidad", "Cargo", "Dirección", "E-Mail"];

                if (isset($_POST['columnas'])) {
                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    foreach ($_POST['columnas'] as $valColumna) {

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

                    $db = new DB();

                    $datos = $db->listarInformeEmpleado($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                }

                break;
            }

        case "email": {
                $textoCabecera = "E-Mails";

                $columnas = ["Descripción", "Usuario", "Servidor", "Puerto", "Seguridad"];

                if (isset($_POST['columnas'])) {

                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    foreach ($_POST['columnas'] as $valColumna) {

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

                    $db = new DB();


                    $datos = $db->listarInformeEmail($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                }


                break;
            }
        case "fichero": {
                $textoCabecera = "Documentos";

                $columnas = ["Nombre", "Tamaño", "Tipo", "Descripción"];


                if (isset($_POST['columnas'])) {

                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    foreach ($_POST['columnas'] as $valColumna) {

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

                    $db = new DB();


                    $datos = $db->listarInformeFichero($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                }


                break;
            }

        case "envio": {
                $textoCabecera = "Envíos";

                $columnas = ["Fecha", "E-Mail", "Nombre Empleado", "Apellido Empleado", "E-Mail Empleado", "Cargo Empleado", "Descripción Fichero", "Grupo"];

                if (isset($_POST['columnas'])) {

                    $columnasFiltro = array();
                    $cabeceraColumnas = array();

                    foreach ($_POST['columnas'] as $valColumna) {

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

                    $db = new DB();


                    $datos = $db->listarInformeEnvio($filtro, $tipoFiltro, $tipoOrden, $ascendente, $columnasFiltro);
                }


                break;
            }
        default: {
                $textoCabecera = "";
                break;
            }
    }
} catch (Exception $ex) {

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
                    $i = 1;
                    foreach ($columnas as $columna) {
                        echo "<label title='Seleccione la tabla para que se muestre en el informe'>"
                        . "<input tabindex='3' type='checkbox' name='columnas[]' title='Seleccione la tabla para que se muestre en el informe' value='" . $i . "'";

                        if (isset($_POST['columnas'])) {
                            if (in_array($i, $_POST['columnas'])) {
                                echo "checked='checked'";
                            }
                        }

                        echo " />" . $columna . "</label>";
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
                        $i = 1;
                        foreach ($columnas as $columna) {
                            echo "<option ";
                            if ($tipoFiltro == $i) {
                                echo "selected=\"selected\" ";
                            }
                            echo "value='" . $i . "'>" . $columna . "</option>";

                            $i++;
                        }
                        ?>

                    </select>

                    <input id="textoFiltro" tabindex="9" type="text" maxlength="30" title="Introduzca la cadena por la que filtrar los resultados" name="filtro"  value="<?php echo $filtro ?>" />
                    <label><input tabindex="8" type="checkbox" name="ordenAscendente" id="ordenAscendente" title="Pulse para ordenar de forma ascendente" <?php if ($ascendente === '1') echo "checked='checked'"; ?> />Ascendente</label>
                    <select name="tipoOrden" id="tipoOrden" title="Seleccione una columna por la que ordenar el informe" tabindex="7">
                        <?php
                        $i = 1;
                        foreach ($columnas as $columna) {
                            echo "<option ";
                            if ($tipoOrden == $i) {
                                echo "selected=\"selected\" ";
                            }
                            echo "value='" . $i . "'>" . $columna . "</option>";

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
            if (isset($datos)) {
                echo "<table>";
                echo "<thead>";
                echo "<tr>";
                foreach ($cabeceraColumnas as $columna) {
                    echo "<td>" . $columna . "</td>";
                }

                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";

                $i = 0;

                foreach ($datos as $fila) {

                    if ($i % 2 === 0) {
                        echo "<tr class='pijama1'>";
                    } else {
                        echo "<tr class='pijama2'>";
                    }

                    foreach ($fila as $valor) {



                        echo "<td>" . $valor . "</td>";
                    }


                    echo "</tr>";

                    $i++;
                }



                echo "</tbody>";
                echo "</table>";
            }
            ?>


        </div>
    </body>
</html>
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
require_once './objetos/Fichero.php';

try {
    // Inicializamos variables
    $error = "";

    // Recupermaos el nombre de usuario
    $nombreUsuario = $_SESSION['nombreUsuario'];

    // Creamos un objeto Fichero
    $fichero = new Fichero(array("id_fichero" => "", "nombre" => "", "tamanyo" => "", "tipo" => "", "descripcion" => "", "fichero" => ""));

    // Recuperamos los valores del modo de visión de la página y 
    // del id_fichero que hemos pasado
    $modo = $_POST['modo'];

    // Recuperamos los valores de id_fichero de sesión si están ahí o en su defecto de post
    $id_fichero = isset($_SESSION['id_fichero']) ? $_SESSION['id_fichero'] : $_POST['id_fichero'];

    // Validamos el usuario
    validarUsuario($_SESSION['user'], $_SESSION['pass']);

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Comprobamos el modo de la página
    switch ($modo) {
        // Si la página está en modo visualización
        case "V": {
                // Recuperamos la información sobre el fichero pasándo 
                // su id como parámetro
                $fichero = $db->recuperarFichero($id_fichero)[0];

                break;
            }
        // Si la página está en modo añadir
        case "A": {
                // Verificamos si en la información de post tenemos la información 
                // de los botones de confirmanción o de cancelación pulsados.
                // Verificamos si la información del botón es la de cancelar.
                if (isset($_POST['boton']) && $_POST['boton'] === "Cancelar") {
                    // Si cancelamos el añadir un fichero, pasamos a sesión el 
                    // indice como valor para la página index.php para que cargue 
                    // la plantilla de ficheros
                    $_SESSION['indice'] = 3;

                    // Navegamos a la pagina index.php
                    header("location:index.php");
                }

                // Comprobamos si la información del botón es la de aceptar
                if (isset($_POST['boton']) && $_POST['boton'] === "Aceptar") {

                    // Validamos que se ha enviado un fichero durante el post
                    if ($_FILES['addfile']['error'][0] === 0) {

                        // Comprobamos que la sesión es correcta
                        if (comprobarTokenSesion()) {

                            // Creamos el objeto fichero con los datos enviados
                            crearObjetosInserccionFichero($fichero);

                            // Validamos los datos introducidos
                            $validacion = validarDatosFichero($fichero);

                            // Comprobamos si hay mensaje de error en la validación
                            if ($validacion === "") {

                                // Si no lo hay, realizamos la insercción pasándo como 
                                // parámetro el objeto Fichero, dejando la gestión de 
                                // errores de la insercción a las excepciones que se 
                                // puedan lanzar. El id resultante de la insercción, lo 
                                // asignamos a la variable $id_fichero
                                $id_fichero = $db->insertarFichero($fichero);

                                // Cambiamos el modo a visor
                                $modo = "V";
                            } else {
                                // Si hay error de validación, copiamos su valor a 
                                // la variable $error
                                $error = $validacion;
                            }
                        } else {
                            // Si la sesión no es válida, recuperamos los datos 
                            // del fichero para mostrarlos en modo visor
                            $fichero = $db->recuperarFichero($id_fichero)[0];
                            $modo = "V";
                        }
                    } else {

                        // Comprobamos el tipo de error que ha generado la subida 
                        // del fichero y mostramos un mensaje en consecuencia
                        switch ($_FILES['addfile']['error'][0]) {
                            case 1: {
                                    $error = "El archivo subido excede la directiva upload_max_filesize en php.ini." . PHP_EOL .
                                            "Pongase en contacto con el administrador: " . $emailAdmin;
                                    break;
                                }
                            case 2: {
                                    $error = "El archivo subido excede la directiva MAX_FILE_SIZE que fue especificada en el formulario HTML." . PHP_EOL .
                                            "Pongase en contacto con el administrador: " . $emailAdmin;
                                    break;
                                }
                            case 3: {
                                    $error = "El archivo subido fue sólo parcialmente cargado." . PHP_EOL .
                                            "Pongase en contacto con el administrador: " . $emailAdmin;
                                    break;
                                }
                            case 4: {
                                    $error = "Ningún archivo fue subido." . PHP_EOL .
                                            "Pongase en contacto con el administrador: " . $emailAdmin;
                                    break;
                                }
                            case 6: {
                                    $error = "Falta la carpeta temporal." . PHP_EOL .
                                            "Pongase en contacto con el administrador: " . $emailAdmin;
                                    break;
                                }
                            case 7: {
                                    $error = "No se pudo escribir el archivo en el disco." . PHP_EOL .
                                            "Pongase en contacto con el administrador: " . $emailAdmin;
                                    break;
                                }
                            case 8: {
                                    $error = "Una extensión de PHP detuvo la carga de archivos." . PHP_EOL .
                                            "Pongase en contacto con el administrador: " . $emailAdmin;
                                    break;
                                }
                            default: {
                                    $error = "Se ha producido un error no especificado durante la carga del archivo." . PHP_EOL .
                                            "Pongase en contacto con el administrador: " . $emailAdmin;
                                    break;
                                }
                        }
                    }
                }

                break;
            }

        // Si es una eliminación
        case "E": {

                // Eliminamos el fichero usando la función adecuada y 
                // pasándo su id como paráemtro
                $db->eliminarFichero($id_fichero);

                // Tras borrar el fichero volvemos a la pantalla index.php y para 
                // eso pasamos a sesión el indice como valor para la página 
                // index.php para que cargue la plantilla de ficheros                
                $_SESSION['indice'] = 3;

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
                        // fichero, para que se sobreescriban sobre cualquier 
                        // moficación que haya podido hacer el usuario
                        $fichero = $db->recuperarFichero($id_fichero)[0];

                        // Cambiamos el modo de la página a visualización
                        $modo = "V";
                    }

                    // Si se ha pulsado el botón de aceptar
                    if ($_POST['boton'] === "Aceptar") {
                        // Comprobamos que la sesión es correcta
                        if (comprobarTokenSesion()) {

                            // Asignamos la informacón introducida en los inputs 
                            // y que se encuentra en post
                            $fichero->setId_fichero($id_fichero);
                            $fichero->setNombre($_POST['nombre']);
                            $fichero->setTamanyo($_POST['tamaño']);
                            $fichero->setTipo($_POST['tipo']);
                            $fichero->setDescripcion($_POST['descripcion']);
                            $fichero->setFichero("");

                            // Realizamos la validación de los datos
                            $validacion = validardatosFichero($fichero);

                            // Comprobamos si la validación ha generado algún 
                            // mensaje de error
                            if ($validacion === "") {

                                // Si no hay mensaje de error, realizamos la modificación 
                                // pasándo como parámetro el objeto  Fichero, dejando 
                                // la gestión de errores de la modificación a las excepciones 
                                // que se puedan lanzar
                                $db->modificarFichero($fichero);

                                // Cambiamos el modo a visor
                                $modo = "V";
                            } else {
                                // Si hay error de validación, copiamos su valor a 
                                // la variable $error                            
                                $error = $validacion;
                            }
                        } else {
                            //// Si la sesión no es válida, recuperamos los datos 
                            // del fichero para mostrarlos en modo visor
                            $fichero = $db->recuperarFichero($id_fichero)[0];
                            $modo = "V";
                        }
                    }
                } else {
                    // Si no se ha pulsado ningún botón nos limitamos a 
                    // recuperar los datos del fichero y mostarselos al usuario
                    $fichero = $db->recuperarFichero($id_fichero)[0];
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
        <title>Detalle Ficheros</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link type = "text/css" rel = "stylesheet" href = "./estilos.css"/>
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
                <h2>Detalle de Ficheros</h2>
                <form id="añadir" action='fichero_detalle.php' method='post' >
                    <input type='submit' tabindex="8" value='Añadir Fichero' title="Pulse para anañadir un nuevo Fichero"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='añadir' type='hidden' value='0' />
                    <input class='oculto' name='modo' type='hidden' value='A' />
                    <input class='oculto' name='id_fichero' type='hidden' value='0' />
                </form>

                <form id="modificar" action='fichero_detalle.php' method='post' >
                    <input type='submit' tabindex="8" value='Modificar Fichero' title="Pulse para modificar el Fichero actual"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='modificar' type='hidden' value='<?php echo $id_fichero ?>' />
                    <input class='oculto' name='modo' type='hidden' value='M' />
                    <input class='oculto' name='id_fichero' type='hidden' value='<?php echo $id_fichero ?>' />
                </form>
                <form id="eliminar" action='fichero_detalle.php' method='post' >
                    <input type='submit' tabindex="9" value='Eliminar Fichero' title="Pulse para eliminar el Fichero actual"  <?php echo deshabilitarBotonesPorModo($modo) ?> />
                    <input class='oculto' name='modo' type='hidden' value='E' />
                    <input class='oculto' name='id_fichero' type='hidden' value='<?php echo $id_fichero ?>' />
                </form>
            </div>
            <div id="detalle">                
                <?php
                if ($modo === "V") {
                    echo "<form id='btnVisor' action='visor.php' method='post' target='_blank'>";
                    echo "<button name='button' value='Ver Fichero' title='Pulse para ver el fichero'><img src='imagenes/download.png' alt='Ver fichero' title='Pulse para ver el fichero' /></button>";
                    echo "<input class='oculto' name='id_fichero' type='hidden' value='" . $id_fichero . "' />";
                    echo "</form>";
                }
                ?>                    

                <form action="fichero_detalle.php" method="post" enctype="multipart/form-data" >
                    <input type='hidden' name='token' id='token' value='<?php echo $_SESSION["token"] ?>'/>
                    <label id="lblDescripcion" for="descripcion">Descripcion&nbsp;</label>
                    <input  tabindex="10" title="Introduzca la descripción del fichero" type="text" name="descripcion" id="descripcion" maxlength="50" value="<?php if ($fichero !== NULL) echo $fichero->getDescripcion() ?>" <?php echo deshabilitarPorModo($modo) ?> />
                    <?php
                    // Comprobamos si el modo de la página es el de añadir. En ese caso mostramos un botón para adjuntar el fichero
                    if ($modo === "A") {
                        //MAX_FILE_SIZE debe preceder el campo de entrada de archivo para limitar el tamaño del fichero a enviar
                        echo '<input type="hidden" name="MAX_FILE_SIZE" value="' . $maxFileSize . '" />';
                        echo '<input title="Haga click para seleccionar el fichero a insertar en la base de datos" tabindex ="11" type="file" id="addfile" name="addfile[]" readonly="readonly" value="" accept=".bmp,.jpg,.gif,.png,.pdf,.doc,.odt,.rtf"/>';
                    }
                    ?>
                    <br />                                                            
                    <label id="lblNombre" for="nombre">Nombre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input tabindex="12" title="Nombre del fichero" type="text" name="nombre" id="nombre" maxlength="50" value="<?php if ($fichero !== NULL) echo $fichero->getNombre() ?>" disabled="disabled" />
                    <label id="lblTamaño" for="tamaño">Tamaño&nbsp;</label>
                    <input tabindex="13" title= "Tamaño del fichero en bytes" type="text" name="descripcion" id="tamaño" maxlength="50" value="<?php if ($fichero !== NULL) echo $fichero->getTamanyo() ?>" disabled="disabled" />
                    <label id="lblTipo" for="tipo">Tipo</label>
                    <input tabindex="14" title="Tipo de fichero en formato MIME" type="text" name="tipo" id="tipo" maxlength="50" value="<?php if ($fichero !== NULL) echo $fichero->getTipo() ?>" disabled="disabled" />
                    <input id="ocultonombre" name="nombre" type="hidden" value="<?php if ($fichero !== NULL) echo $fichero->getNombre() ?>" />
                    <input id="ocultotamaño" name="tamaño" type="hidden" value="<?php if ($fichero !== NULL) echo $fichero->getTamanyo() ?>" />
                    <input id="ocultotipo" name="tipo" type="hidden" value="<?php if ($fichero !== NULL) echo $fichero->getTipo() ?>" />                                        
                    <br />                                        
                    <?php
                    // Comprobamos el modo en el que está la página. Si está en 
                    // modo Modificación o Adicción, creamos un botón de aceptar 
                    // modificaciones y otro de cancelarlas
                    if ($modo === "A" || $modo === "M") {

                        // Creamos el botón de aceptar
                        echo "<input tabindex='17' name='boton' id='aceptar' type='submit' value='Aceptar' title='Pulse para confirmar las modificaciones' />";

                        // Creamos el botón de cancelar
                        echo "<input tabindex='18' name='boton' id='cancelar' type='submit' value='Cancelar' title='Pulse para cancelar las modificaciones' />";

                        // Creamos dos objetos ocultos para reenviar la información del modo de la página y del identificador del fichero
                        echo "<input class='oculto' name='id_fichero' type='hidden' value='$id_fichero' />";
                        echo "<input class='oculto' name='modo' type='hidden' value='$modo' />";
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
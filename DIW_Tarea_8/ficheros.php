<?php
require_once './objetos/Fichero.php';

try {

    // Recuperamos los valores de los filtros
    $filtro = (isset($_POST['filtro']) ? $_POST['filtro'] : "");
    $tipoFiltro = (isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : "1");
    
    // Limpiamos el valor de id_fichero de la sesión, dejando así la pantalla 
    // de detalles lista para volver a usar
    unset($_SESSION['id_fichero']);    
    
    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();
    
    // Obtenemos el listado de ficheros pasándole los valores de filtro
    $ficheros = $db->listarFicheros($filtro, $tipoFiltro);    
} catch (Exception $ex) {
    $error = $ex->getMessage();
}
?>
<div class="listado">
    <div id="botonera">
        <h2>Listado de Ficheros</h2>
        <form id="nuevo" action='fichero_detalle.php' method='post' >
            <input type='submit' tabindex="8" value='Nuevo Fichero' title="Pulse el botón para crear un nuevo Fichero" />
            <input class='oculto' name='id_fichero' type='hidden' value='0' />
            <input class='oculto' name='modo' type='hidden' value='A' />
        </form>
        <form id="filtro" action='index.php' method='post' >
            <input type='submit' tabindex="11" value='Filtrar resultados' title="Pulse el botón para filtrar los resultados"/>            
            <select name="tipoFiltro" tabindex="10" title="Seleccione el tipo de filtro">
                <option <?php if ($tipoFiltro === "1") echo "selected=\"selected\" " ?> value="1">Nombre</option>
                <option <?php if ($tipoFiltro === "2") echo "selected=\"selected\" " ?> value="2">Tamaño</option>
                <option <?php if ($tipoFiltro === "3") echo "selected=\"selected\" " ?> value="3">Tipo</option>
                <option <?php if ($tipoFiltro === "4") echo "selected=\"selected\" " ?> value="4">Descripcion</option>
            </select>
            <input id="textoFiltro" tabindex="9" type="text" maxlength="30" title="Introduzca la cadena por la que filtrar los resultados" name="filtro"  value="<?php echo $filtro ?>" />
            <input class='oculto' name='indice' type='hidden' value='3' />
        </form>
    </div>
    <div class="error">
        <p><?php echo $error ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <td>Nombre</td>    
                <td>Tamaño</td>    
                <td>Tipo</td>    
                <td>Descripción</td>    
                <td>Detalles</td>    
            </tr>        
        </thead>    
        <tbody>
            <?php
            
            // Verificamos si tenemos algún tipo de error
            if ($error === "") {
                // Inicializamos un contador para asignar los estilos a cada linea
                $i = 0;

                // Recorremos cada uno de los registros que hemos recuperado 
                // mediante la consulta a la base de datos
                foreach ($ficheros as $fichero) {

                    // Si el contador es un número par, le daremos un estilo y si 
                    // es impar le daremos otro
                    if ($i % 2 === 0) {
                        echo '<tr class="pijama1">';
                    } else {
                        echo '<tr class="pijama2">';
                    }

                    // Imprimimos celda con los valores recuperados de cada objeto 
                    // fichero que hay en los registros recuperados
                    echo '<td title="'. $fichero->getNombre() .'">' . textoElipsis($fichero->getNombre(),50) . '</td>';
                    echo '<td title="'. $fichero->getTamanyo() .'">' . textoElipsis($fichero->getTamanyo(),30) . '</td>';
                    echo '<td title="'. $fichero->getTipo() .'">' . textoElipsis($fichero->getTipo(),30) . '</td>';
                    echo '<td title="'. $fichero->getDescripcion() .'">' . textoElipsis($fichero->getDescripcion(),50) . '</td>';


                    // Añadimos una última fila con un botón con imagen para 
                    // acceder a los detalles del fichero.
                    echo '<td>';
                    echo "<form action='fichero_detalle.php' method='post' >";
                    echo "<button name='button' value='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles' /></button>";
                    echo "<input class='oculto' name='id_fichero' type='hidden' value='" . $fichero->getId_fichero() . "' />";
                    echo "<input class='oculto' name='modo' type='hidden' value='V' />";
                    echo "</form>";
                    echo '</td>';
                    echo '</tr>';

                    // Incrementamos el contador
                    $i++;
                }
            }
            ?>
        </tbody>
    </table>
</div>
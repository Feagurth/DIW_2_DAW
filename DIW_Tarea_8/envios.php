<?php
require_once './objetos/ListaEnvio.php';

try {

    // Recuperamos los valores de los filtros
    $filtro = (isset($_POST['filtro']) ? $_POST['filtro'] : "");
    $tipoFiltro = (isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : "1");

    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    // Limpiamos el valor de id_envio de la sesión, dejando así la pantalla 
    // de detalles lista para volver a usar
    unset($_SESSION['id_envio']);

    // Obtenemos el listado de envios pasándole los valores de filtro
    $envios = $db->listarEnvios($filtro, $tipoFiltro);
} catch (Exception $ex) {
    $error = $ex->getMessage();
}
?>
<div class="listado">
    <div id="botonera">
        <h2>Listado de envíos</h2>
        <form id="nuevo" action='envio_detalle.php' method='post' >
            <input type='submit' tabindex="8" value='Nuevo Envio' title="Pulse el botón para crear un nuevo envio" />
            <input class='oculto' name='id_envio' type='hidden' value='0' />
            <input class='oculto' name='modo' type='hidden' value='A' />
        </form>
        <form id="informe" action='informes.php' method='post' target="_blank">
            <input type='submit' tabindex="8" value='Generar Informe' title="Pulse el botón para generar un informa de usuarios" />
            <input class='oculto' name='tipoInforme' type='hidden' value='envio' />
        </form>               
        <form id="filtro" action='index.php' method='post' >
            <input type='submit' tabindex="11" value='Filtrar resultados' title="Pulse el botón para filtrar los resultados"/>            
            <select name="tipoFiltro" tabindex="10" title="Seleccione el tipo de filtro">
                <option <?php if ($tipoFiltro === "1") echo "selected=\"selected\" " ?> value="1">Fecha</option>
                <option <?php if ($tipoFiltro === "2") echo "selected=\"selected\" " ?> value="2">Cuenta de E-Mail</option>
                <option <?php if ($tipoFiltro === "3") echo "selected=\"selected\" " ?> value="3">Nombre de Empleado</option>
                <option <?php if ($tipoFiltro === "4") echo "selected=\"selected\" " ?> value="4">Apellido de Empleado</option>
                <option <?php if ($tipoFiltro === "5") echo "selected=\"selected\" " ?> value="5">E-Mail de Empleado</option>
                <option <?php if ($tipoFiltro === "6") echo "selected=\"selected\" " ?> value="6">Nombre de Fichero</option>
                <option <?php if ($tipoFiltro === "7") echo "selected=\"selected\" " ?> value="7">Descripción de Fichero</option>
            </select>
            <input id="textoFiltro" tabindex="9" type="text" maxlength="30" title="Introduzca la cadena por la que filtrar los resultados" name="filtro"  value="<?php echo $filtro ?>" />
            <input class='oculto' name='indice' type='hidden' value='4' />
        </form>
    </div>
    <div class="error">
        <p><?php echo $error ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <td class="listadoCabecera">Fecha</td>    
                <td class="listadoCabecera">Cuenta de E-Mail</td>    
                <td class="listadoCabecera">Nombre de Empleado</td>    
                <td class="listadoCabecera">Apellido de Empleado</td>    
                <td class="listadoCabecera">E-Mail de Empleado</td>    
                <td class="listadoCabecera">Cargo de Empleado</td>    
                <td class="listadoCabecera">Descripción de Fichero</td>                    
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
                foreach ($envios as $envio) {

                    // Si el contador es un número par, le daremos un estilo y si 
                    // es impar le daremos otro
                    if ($i % 2 === 0) {
                        echo '<tr class="pijama1">';
                    } else {
                        echo '<tr class="pijama2">';
                    }

                    // Imprimimos celda con los valores recuperados de cada objeto 
                    // envio que hay en los registros recuperados
                    echo '<td title="' . $envio->getFecha_envio() . '">' . textoElipsis($envio->getFecha_envio(), 10) . '</td>';
                    echo '<td title="' . $envio->getEmail_envio() . '">' . textoElipsis($envio->getEmail_envio(), 30) . '</td>';
                    echo '<td title="' . $envio->getNombre_empleado() . '">' . textoElipsis($envio->getNombre_empleado(), 30) . '</td>';
                    echo '<td title="' . $envio->getApellido_empleado() . '">' . textoElipsis($envio->getApellido_empleado(), 30) . '</td>';
                    echo '<td title="' . $envio->getEmail_empleado() . '">' . textoElipsis($envio->getEmail_empleado(), 30) . '</td>';
                    echo '<td title="' . $envio->getCargo_empleado() . '">' . textoElipsis($envio->getCargo_empleado(), 15) . '</td>';
                    echo '<td title="' . $envio->getDescripcion_fichero() . '">' . textoElipsis($envio->getDescripcion_fichero(), 50) . '</td>';

                    // Añadimos una última fila con un botón con imagen para 
                    // acceder a los detalles del envio.
                    echo '<td>';
                    echo "<form action='envio_detalle.php' method='post' >";
                    echo "<button name='button' value='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles' /></button>";
                    echo "<input class='oculto' name='id_envio' type='hidden' value='" . $envio->getId_envio() . "' />";
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
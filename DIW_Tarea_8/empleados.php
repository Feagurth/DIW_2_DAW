<?php
require_once './objetos/Empleado.php';

try {

    // Recuperamos los valores de los filtros
    $filtro = (isset($_POST['filtro']) ? $_POST['filtro'] : "");
    $tipoFiltro = (isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : "1");
    
    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();
    
    // Obtenemos el listado de empleados pasándole los valores de filtro
    $empleados = $db->listarEmpleados($filtro, $tipoFiltro);
} catch (Exception $ex) {
    $error = $ex->getMessage();
}
?>
<div class="listado">
    <div id="botonera">
        <h3>Listado de empleados</h3>
        <form id="nuevo" action='empleado_detalle.php' method='post' >
            <input type='submit' tabindex="8" value='Nuevo Empleado' alt='Nuevo Empleado' title="Pulse el botón para crear un nuevo empleado" />
            <input class='oculto' name='id_empleado' type='text' value='0' />
            <input class='oculto' name='modo' type='text' value='A' />
        </form>
        <form id="filtro" action='index.php' method='post' >
            <input type='submit' tabindex="11" value='Filtrar resultados' alt='Filtrar resultados' title="Pulse el botón para filtrar los resultados"/>            
            <select name="tipoFiltro" tabindex="10" title="Seleccione el tipo de filtro">
                <option <?php if ($tipoFiltro === "1") echo "selected=\"selected\" " ?> value="1">Nombre</option>
                <option <?php if ($tipoFiltro === "2") echo "selected=\"selected\" " ?> value="2">Apellido</option>
                <option <?php if ($tipoFiltro === "3") echo "selected=\"selected\" " ?> value="3">Telefono</option>
                <option <?php if ($tipoFiltro === "4") echo "selected=\"selected\" " ?> value="4">Especialidad</option>
                <option <?php if ($tipoFiltro === "5") echo "selected=\"selected\" " ?> value="5">Cargo</option>
                <option <?php if ($tipoFiltro === "6") echo "selected=\"selected\" " ?> value="6">Dirección</option>
                <option <?php if ($tipoFiltro === "7") echo "selected=\"selected\" " ?> value="7">E-Mail</option>
            </select>
            <input id="textoFiltro" tabindex="9" type="text" alt="Introduzca un texto para filtrar los resultados" maxlength="30" title="Introduzca la cadena por la que filtrar los resultados" name="filtro"  value="<?php echo $filtro ?>" />
            <input class='oculto' name='indice' type='text' value='1' />
        </form>
    </div>
    <div class="error">
        <p><?php echo $error ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <td>Nombre</td>    
                <td>Apellido</td>    
                <td>Telefono</td>    
                <td>Especialidad</td>    
                <td>Cargo</td>    
                <td>Dirección</td>    
                <td>E-Mail</td>
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
                    echo '<td title="'. $empleado->getNombre() .'">' . textoElipsis($empleado->getNombre(),15) . '</td>';
                    echo '<td title="'. $empleado->getApellido() .'">' . textoElipsis($empleado->getApellido(),15) . '</td>';
                    echo '<td title="'. $empleado->getTelefono() .'">' . textoElipsis($empleado->getTelefono(),15) . '</td>';
                    echo '<td title="'. $empleado->getEspecialidad() .'">' . textoElipsis($empleado->getEspecialidad(),15) . '</td>';
                    echo '<td title="'. $empleado->getCargo() .'">' . textoElipsis($empleado->getCargo(),15) . '</td>';
                    echo '<td title="'. $empleado->getDireccion() .'">' . textoElipsis($empleado->getDireccion(),15) . '</td>';
                    echo '<td title="'. $empleado->getEmail() .'">' . textoElipsis($empleado->getEmail(),15) . '</td>';

                    // Añadimos una última fila con un botón con imagen para 
                    // acceder a los detalles del empleado.
                    echo '<td>';
                    echo "<form action='empleado_detalle.php' method='post' >";
                    echo "<button name='button' value='Detalles' alt='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles' /></button>";
                    echo "<input class='oculto' name='id_empleado' type='text' value='" . $empleado->getId_empleado() . "' />";
                    echo "<input class='oculto' name='modo' type='text' value='V' />";
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
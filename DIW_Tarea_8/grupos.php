<?php
require_once './objetos/Grupo.php';

try {

    // Recuperamos los valores de los filtros
    $filtro = (isset($_POST['filtro']) ? $_POST['filtro'] : "");
    $tipoFiltro = (isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : "1");
    
    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();
    
    // Obtenemos el listado de usuarios pasándole los valores de filtro
    $grupos = $db->listarGrupos($filtro, $tipoFiltro);
} catch (Exception $ex) {
    $error = $ex->getMessage();
}
?>
<div class="listado">
    <div id="botonera">
        <h3>Listado de grupos</h3>
        <form id="nuevo" action='grupo_detalle.php' method='post' >
            <input type='submit' tabindex="8" value='Nuevo Grupo' alt='Nuevo Grupo' title="Pulse el botón para crear un nuevo grupo" />
            <input class='oculto' name='id_grupo' type='text' value='0' />
            <input class='oculto' name='modo' type='text' value='A' />
        </form>
        <form id="filtro" action='index.php' method='post' >
            <input type='submit' tabindex="11" value='Filtrar resultados' alt='Filtrar resultados' title="Pulse el botón para filtrar los resultados"/>            
            <select name="tipoFiltro" tabindex="10" title="Seleccione el tipo de filtro">
                <option <?php if ($tipoFiltro === "1") echo "selected=\"selected\" " ?> value="1">Nombre</option>
                <option <?php if ($tipoFiltro === "2") echo "selected=\"selected\" " ?> value="2">Descripción</option>
            </select>
            <input id="textoFiltro" tabindex="9" type="text" alt="Introduzca un texto para filtrar los resultados" maxlength="30" title="Introduzca la cadena por la que filtrar los resultados" name="filtro"  value="<?php echo $filtro ?>" />
            <input class='oculto' name='indice' type='text' value='2' />
        </form>
    </div>
    <div class="error">
        <p><?php echo $error ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <td>Nombre</td>    
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
                foreach ($grupos as $grupo) {

                    // Si el contador es un número par, le daremos un estilo y si 
                    // es impar le daremos otro
                    if ($i % 2 === 0) {
                        echo '<tr class="pijama1">';
                    } else {
                        echo '<tr class="pijama2">';
                    }

                    // Imprimimos celda con los valores recuperados de cada objeto 
                    // usuario que hay en los registros recuperados
                    echo '<td title="'. $grupo->getNombre() .'">' . textoElipsis($grupo->getNombre(),30) . '</td>';
                    echo '<td title="'. $grupo->getDescripcion() .'">' . textoElipsis($grupo->getDescripcion(),50) . '</td>';

                    // Añadimos una última fila con un botón con imagen para 
                    // acceder a los detalles del usuario.
                    echo '<td>';
                    echo "<form action='grupo_detalle.php' method='post' >";
                    echo "<button name='button' value='Detalles' alt='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles' /></button>";
                    echo "<input class='oculto' name='id_grupo' type='text' value='" . $grupo->getId_grupo() . "' />";
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
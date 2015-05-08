<?php
require_once './objetos/Email.php';

try {

    // Recuperamos los valores de los filtros
    $filtro = (isset($_POST['filtro']) ? $_POST['filtro'] : "");
    $tipoFiltro = (isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : "1");
    
    // Limpiamos el valor de id_email de la sesión, dejando así la pantalla 
    // de detalles lista para volver a usar
    unset($_SESSION['id_email']);    
    
    // Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();
    
    // Obtenemos el listado de emails pasándole los valores de filtro
    $emails = $db->listarEmails($filtro, $tipoFiltro);
} catch (Exception $ex) {
    $error = $ex->getMessage();
}
?>
<div class="listado">
    <div id="botonera">
        <h3>Listado de cuentas de E-Mail</h3>
        <form id="nuevo" action='email_detalle.php' method='post' >
            <input type='submit' tabindex="8" value='Nuevo E-Mail' alt='Nuevo E-Mail' title="Pulse el botón para crear un nuevo E-Mail" />
            <input class='oculto' name='id_email' type='text' value='0' />
            <input class='oculto' name='modo' type='text' value='A' />
        </form>
        <form id="filtro" action='index.php' method='post' >
            <input type='submit' tabindex="11" value='Filtrar resultados' alt='Filtrar resultados' title="Pulse el botón para filtrar los resultados"/>            
            <select name="tipoFiltro" tabindex="10" title="Seleccione el tipo de filtro">
                <option <?php if ($tipoFiltro === "1") echo "selected=\"selected\" " ?> value="1">Usuario</option>
                <option <?php if ($tipoFiltro === "2") echo "selected=\"selected\" " ?> value="2">Servidor</option>
                <option <?php if ($tipoFiltro === "3") echo "selected=\"selected\" " ?> value="3">Puerto</option>
                <option <?php if ($tipoFiltro === "4") echo "selected=\"selected\" " ?> value="4">Seguridad</option>
                <option <?php if ($tipoFiltro === "5") echo "selected=\"selected\" " ?> value="5">Descripción</option>
            </select>
            <input id="textoFiltro" tabindex="9" type="text" alt="Introduzca un texto para filtrar los resultados" maxlength="30" title="Introduzca la cadena por la que filtrar los resultados" name="filtro"  value="<?php echo $filtro ?>" />
            <input class='oculto' name='indice' type='text' value='5' />
        </form>
    </div>
    <div class="error">
        <p><?php echo $error ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <td>Usuario</td>    
                <td>Servidor</td>    
                <td>Puerto</td>    
                <td>Seguridad</td>    
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
                foreach ($emails as $email) {

                    // Si el contador es un número par, le daremos un estilo y si 
                    // es impar le daremos otro
                    if ($i % 2 === 0) {
                        echo '<tr class="pijama1">';
                    } else {
                        echo '<tr class="pijama2">';
                    }

                    // Imprimimos celda con los valores recuperados de cada objeto 
                    // email que hay en los registros recuperados
                    echo '<td title="'. $email->getUsuario() .'">' . textoElipsis($email->getUsuario(),32) . '</td>';
                    echo '<td title="'. $email->getServidor() .'">' . textoElipsis($email->getServidor(),30) . '</td>';
                    echo '<td title="'. $email->getPuerto() .'">' . textoElipsis($email->getPuerto(),11) . '</td>';
                    echo '<td title="'. $email->getSeguridad() .'">' . textoElipsis($email->getSeguridad(),10) . '</td>';
                    echo '<td title="'. $email->getDescripcion() .'">' . textoElipsis($email->getDescripcion(),50) . '</td>';

                    // Añadimos una última fila con un botón con imagen para 
                    // acceder a los detalles del email.
                    echo '<td>';
                    echo "<form action='email_detalle.php' method='post' >";
                    echo "<button name='button' value='Detalles' alt='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' title='Pulse para ver los detalles' /></button>";
                    echo "<input class='oculto' name='id_email' type='text' value='" . $email->getId_email() . "' />";
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
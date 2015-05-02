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
require_once './objetos/objEmpleado.php';

try {
// Creamos un nuevo objeto de acceso a base de datos
    $db = new DB();

    
    $filtro = (isset($_POST['filtro']) ? $_POST['filtro'] : NULL);
    $tipoFiltro = (isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : NULL);
    
    
// Obtenemos el listado de todas las personas
    $empleados = $db->listarEmpleado($filtro, $tipoFiltro);
} catch (Exception $ex) {
    
}
?>
<div class="listado">
    <div id="botonera">
        <h3>Listado de empleados</h3>
        <form id="nuevo" action='detalle_usuario.php' method='post' >
            <input type='submit' value='Nuevo Empleado' alt='Nuevo Empleado' />
            <input class='oculto' name='indice' type='text' value='0' />
        </form>

        <form id="filtro" action='index.php' method='post' >
            <input type='submit' value='Filtrar resultados' alt='Filtrar resultados' />            
            <select name="tipoFiltro">
                <option <?php if($tipoFiltro === "1")echo "selected=\"selected\" " ?> value="1">Nombre</option>
                <option <?php if($tipoFiltro === "2")echo "selected=\"selected\" " ?> value="2">Apellido</option>
                <option <?php if($tipoFiltro === "3")echo "selected=\"selected\" " ?> value="3">Telefono</option>
                <option <?php if($tipoFiltro === "4")echo "selected=\"selected\" " ?> value="4">Especialidad</option>
                <option <?php if($tipoFiltro === "5")echo "selected=\"selected\" " ?> value="5">Cargo</option>
                <option <?php if($tipoFiltro === "6")echo "selected=\"selected\" " ?> value="6">Dirección</option>
                <option <?php if($tipoFiltro === "7")echo "selected=\"selected\" " ?> value="7">E-Mail</option>
            </select>
            <input id="textoFiltro" type="text" alt="Introduzca un texto para filtrar los resultados" maxlength="30" title="Filtro" name="filtro"  value="<?php echo $filtro ?>" />
            <input class='oculto' name='indice' type='text' value='1' />
        </form>

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
            $i = 0;
            foreach ($empleados as $empleado) {
                if ($i % 2 === 0) {
                    echo '<tr class="pijama1">';
                } else {
                    echo '<tr class="pijama2">';
                }

                echo '<td>' . $empleado->getNombre() . '</td>';
                echo '<td>' . $empleado->getApellido() . '</td>';
                echo '<td>' . $empleado->getTelefono() . '</td>';
                echo '<td>' . $empleado->getEspecialidad() . '</td>';
                echo '<td>' . $empleado->getCargo() . '</td>';
                echo '<td>' . $empleado->getDireccion() . '</td>';
                echo '<td>' . $empleado->getEmail() . '</td>';


                echo '<td>';
                echo "<form action='detalle_usuario.php' method='post' >";
                echo "<button name='button' value='Detalles' alt='Detalles'><img src='imagenes/details.png' alt='Ver Detalles' /></button>";
                echo "<input class='oculto' name='indice' type='text' value='" . $empleado->getId_empleado() . "' />";
                echo "</form>";
                echo '</td>';
                echo '</tr>';

                $i++;
            }
            ?>
        </tbody>




    </table>
</div>
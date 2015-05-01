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

// Obtenemos el listado de todas las personas
    $empleados = $db->listarEmpleado("");
} catch (Exception $ex) {
    
}
?>
<div class="listado">
    <h3>Listado de empleados</h3>
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
                <td></td>
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
                echo "<input type='submit' value='Detalles' alt='Detalles' />";
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
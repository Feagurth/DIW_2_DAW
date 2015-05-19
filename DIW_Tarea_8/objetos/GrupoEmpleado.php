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

/**
 * Clase para almacenar las relaciones entre grupos y empleados
 *
 * @package Objetos
 * @author Luis Cabrerizo Gómez
 */
class GrupoEmpleado {
    
    /**
     * Identificador de la relación entre el grupo y el empleado
     * @var int
     */
    private $id_grupo_empleado;
    
    /**
     * Identificador del grupo
     * @var int
     */
    private $id_grupo;
    
    /**
     * Identificador del empleado
     * @var int
     */
    private $id_empleado;
    
    
    /**
     * Constructor de la clase GrupoEmpleado
     * @param array $row Array con los datos de grupoempleado
     */
    public function __construct($row) {
        $this->id_grupo_empleado = $row['id_grupo_empleado'];
        $this->id_grupo = $row['id_grupo'];
        $this->id_empleado = $row['id_empleado'];        
    }        
    
    
    /**
     * Functión que nos permite recuperar el identificador de grupoempleado
     * @return int El identificador de grupo_empleado
     */
    public function getId_grupo_empleado() {
        return $this->id_grupo_empleado;
    }

    /**
     * Función que nos permite recuperar el identificador del grupo
     * @return int El identificador del grupo
     */
    public function getId_grupo() {
        return $this->id_grupo;
    }

    /**
     * Función que nos permite recuperar el identificador del empleado
     * @return int El identificador del empleado
     */
    public function getId_empleado() {
        return $this->id_empleado;
    }

    /**
     * Función que nos permite asginar el identificador del grupoempleado
     * @param int $id_grupo_empleado El identificador del grupoempleado
     */
    public function setId_grupo_empleado($id_grupo_empleado) {
        $this->id_grupo_empleado = $id_grupo_empleado;
    }

    /**
     * Función que nos permite asginar el identificador del grupo
     * @param int $id_grupo El identificador del grupo
     */
    public function setId_grupo($id_grupo) {
        $this->id_grupo = $id_grupo;
    }

    /**
     * Función que nos permite asignar el identificador del empleado
     * @param int $id_empleado El identificador del empleado
     */
    public function setId_empleado($id_empleado) {
        $this->id_empleado = $id_empleado;
    }


    
}

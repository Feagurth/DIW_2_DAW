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
 * Objeto para trabajar con grupos
 *
 * @author Luis Cabrerizo Gómez
 */
class Grupo {

    /**
     * Identificador del grupo
     * @var int
     */
    private $id_grupo;

    /**
     * Nombre del grupo
     * @var string
     */
    private $nombre;

    /**
     * Descripción del grupo
     * @var string
     */
    private $descripcion;

    /**
     * Constructor de la clase Grupo
     * @param array $row Array con los datos de grupo
     */
    public function __construct($row) {
        $this->id_grupo = $row['id_grupo'];
        $this->nombre = $row['nombre'];
        $this->descripcion = $row['descripcion'];
    }

    /**
     * Función que nos permite recuperar el Id del grupo
     * @return int El id del grupo
     */
    public function getId_grupo() {
        return $this->id_grupo;
    }

    /**
     * Función que nos permite recuperar el nombre del grupo
     * @return string El nombre del grupo
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Función que nos permite recuperar la descripcion del grupo
     * @return string La descripción del grupo
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Función que nos permite asignar el Id del grupo
     * @param string $id_grupo La ide del grupo
     */
    public function setId_grupo($id_grupo) {
        $this->id_grupo = $id_grupo;
    }

    /**
     * Función que nos permite asignar el nombre del grupo
     * @param string $nombre El nombre del grupo
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    /**
     * Función que nos permite asignar la descripción del grupo
     * @param string $descripcion La descripción del grupo
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

}

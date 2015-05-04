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
 * Objeto para trabajar con usuarios
 *
 * @author Luis Cabrerizo Gómez
 */
class Usuario {

    /**
     * Identificador del usuario
     * @var int
     */
    private $id_usuario;

    /**
     * Usuario
     * @var string
     */
    private $user;

    /**
     * Contraseña
     * @var string
     */
    private $pass;

    /**
     * Nombre descriptivo del usuario
     * @var string
     */
    private $nombre;

    /**
     * Constructor de la clase Usuario
     * @param array $row Array con los datos de usuario
     */
    public function __construct($row) {
        $this->id_usuario = $row['id_usuario'];
        $this->user = $row['user'];
        $this->pass = $row['pass'];
        $this->nombre = $row['nombre'];
    }    
    
    
    /**
     * Función que nos permite recuperar el Id del usuario
     * @return int
     */
    public function getId_usuario() {
        return $this->id_usuario;
    }

    /**
     * Función que nos permite recuperar el usuario
     * @return string
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Función que nos permite recuperar la contraseña del usuario
     * @return string
     */
    public function getPass() {
        return $this->pass;
    }

    /**
     * Función que nos permite recuperar el nombre del usuario
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }
    
    /**
     * Función que nos permite asignar el Id del usuario
     * @param string $id_usuario
     */
    public function setId_usuario($id_usuario) {
        $this->id_usuario = $id_usuario;
    }

    /**
     * Función que nos permite asignar el usuario
     * @param string $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * Función que nos permite asignar la contraseña del usuario
     * @param string $pass
     */
    public function setPass($pass) {
        $this->pass = $pass;
    }

    /**
     * Función que nos permite asignar el nombre descriptivo del usuario
     * @param string $nombre
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

}

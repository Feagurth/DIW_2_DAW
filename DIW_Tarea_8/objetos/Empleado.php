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
 * Clase para contener la información de los empleados
 * 
 * @package Objetos
 * @author Luis Cabrerizo Gómez
 */
class Empleado implements JsonSerializable {

    /**
     * Variable para almacenar el identificador del empleado
     * @var int
     */
    private $id_empleado;

    /**
     * Variable para almacenar el nombre del empleado
     * @var string
     */
    private $nombre;

    /**
     * Variable para almacenar el apellido del empleado
     * @var string
     */
    private $apellido;

    /**
     * Variable para almacenar el telefono del empleado
     * @var string
     */
    private $telefono;

    /**
     * Variable para almacenar el especialidad del empleado
     * @var string
     */
    private $especialidad;

    /**
     * Variable para almacenar el cargo del empleado
     * @var string
     */
    private $cargo;

    /**
     * Variable para almacenar la dirección del empleado
     * @var string
     */
    private $direccion;

    /**
     * Variable para almacenar el email del empleado
     * @var string
     */
    private $email;

    /**
     * Constructor de la clase empleado
     * @param array $row Array con los datos de empleado
     */
    public function __construct($row) {
        $this->id_empleado = $row['id_empleado'];
        $this->nombre = $row['nombre'];
        $this->apellido = $row['apellido'];
        $this->telefono = $row['telefono'];
        $this->especialidad = $row['especialidad'];
        $this->cargo = $row['cargo'];
        $this->direccion = $row['direccion'];
        $this->email = $row['email'];
    }

    /**
     * Función que nos permite recuperar el id del empleado
     * @return int
     */
    public function getId_empleado() {
        return $this->id_empleado;
    }

    /**
     * Función que nos permite recuperar el nombre del empleado
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Función que nos permite recuperar el apellido del empleado
     * @return string
     */
    public function getApellido() {
        return $this->apellido;
    }

    /**
     * Función que nos permite recuperar el telefono del empleado
     * @return string
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * Función que nos permite recuperar la especialidad del empleado
     * @return string
     */
    public function getEspecialidad() {
        return $this->especialidad;
    }

    /**
     * Función que nos permite recuperar el cargo del empleado
     * @return string
     */
    public function getCargo() {
        return $this->cargo;
    }

    /**
     * Función que nos permite recuperar la dirección del empleado
     * @return string
     */
    public function getDireccion() {
        return $this->direccion;
    }

    /**
     * Función que nos permite recuperar el email del empleado
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Función que nos permite asignar el id del empleado
     * @param int $id_empleado El id del empleado
     */
    public function setId_empleado($id_empleado) {
        $this->id_empleado = $id_empleado;
    }

    /**
     * Función que nos permite asignar el nombre del empleado
     * @param string $nombre El nombre del empleado
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    /**
     * Función que nos permite asignar el apellido del empleado
     * @param string $apellido El apellido del empleado
     */
    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    /**
     * Función que nos permite asignar el telefono del empleado
     * @param string $telefono El telefono del empleado
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    /**
     * Función que nos permite asignar la especialidad del empleado
     * @param string $especialidad La especialidad del empleado
     */
    public function setEspecialidad($especialidad) {
        $this->especialidad = $especialidad;
    }

    /**
     * Función que nos permite asignar el cargo del empleado
     * @param string $cargo El cargo del empleado
     */
    public function setCargo($cargo) {
        $this->cargo = $cargo;
    }

    /**
     * Función que nos permite asignar la dirección del empleado
     * @param string $direccion La dirección del empleado
     */
    public function setDireccion($direccion) {
        $this->direccion = $direccion;
    }

    /**
     * Función que nos permite asignar el email del empleado
     * @param string $email El email del empleado
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Función para serializar los datos en formato Json
     * @return Json Los datos de la clase en formato Json
     */
    public function jsonSerialize() {
        // Creamos un array con la información que contiene el objeto
        $data = array(
            "id_empleado" => $this->id_empleado,
            "nombre" => $this->nombre,
            "apellido" => $this->apellido,
            "telefono" => $this->telefono,
            "especialidad" => $this->especialidad,
            "cargo" => $this->cargo,
            "direccion" => $this->direccion,
            "email" => $this->email
        );

        // Devolvemos el array como resultado de la serialización
        return $data;
    }

}

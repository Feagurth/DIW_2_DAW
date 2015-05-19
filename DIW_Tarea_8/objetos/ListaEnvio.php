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
 * Clase para trabajar con la lista de envíos realizados
 *
 * @package Objetos
 * @author Luis Cabrerizo Gómez
 */
class ListaEnvio {

    /**
     * Variable para identificar el envío
     * @var int
     */
    private $id_envio;

    /**
     * Fecha de realización del envío
     * @var string
     */
    private $fecha_envio;

    /**
     * Descripción de la cuenta de email que se ha usado para realizar el envío
     * @var string
     */
    private $email_envio;

    /**
     * Nombre del empleado al que se le ha enviado el fichero
     * @var string
     */
    private $nombre_empleado;

    /**
     * Apellido del empleado al que se le ha enviado el fichero
     * @var string
     */
    private $apellido_empleado;

    /**
     * E-Mail del empleado al que se le ha enviado el fichero
     * @var string
     */
    private $email_empleado;

    /**
     * Cargo del empleado al que se ha enviado el fichero
     * @var string
     */
    private $cargo_empleado;

    /**
     * Descripción del fichero que se le ha enviado al empleado
     * @var string
     */
    private $descripcion_fichero;

    /**
     * Constructor de la clase Envio
     * @param array $row Array con los datos del envío
     */
    public function __construct($row) {
        $this->id_envio = $row['id_envio'];
        $this->fecha_envio = $row['fecha_envio'];
        $this->email_envio = $row['email_envio'];
        $this->nombre_empleado = $row['nombre_empleado'];
        $this->apellido_empleado = $row['apellido_empleado'];
        $this->email_empleado = $row['email_empleado'];
        $this->cargo_empleado = $row['cargo_empleado'];
        $this->descripcion_fichero = $row['descripcion_fichero'];
    }

    /**
     * Función que nos permite recuperar el identificador del envío
     * @return int El identificador del envío
     */
    public function getId_envio() {
        return $this->id_envio;
    }

    /**
     * Función que nos permite recuperar la fecha de realización del envío
     * @return string Fecha de realización del envío
     */
    public function getFecha_envio() {
        return $this->fecha_envio;
    }

    /**
     * Función que nos permite la descripción de la cuenta de E-Mail desde la 
     * que se ha realizado el envío
     * @return string La descripción de la cuenta de E-Mail usada para realizar 
     * los envíos
     */
    public function getEmail_envio() {
        return $this->email_envio;
    }

    /**
     * Función que nos permite recuperar el nombr del empleado al que se ha realizado del envío
     * @return string El nombre del empleado al que se le ha realizado el envío
     */
    public function getNombre_empleado() {
        return $this->nombre_empleado;
    }

    /**
     * Función que nos permite recuperar el apellido del empleado al que se ha realizado del envío
     * @return string El apellido del empleado al que se le ha realizado el envío
     */
    public function getApellido_empleado() {
        return $this->apellido_empleado;
    }

    /**
     * Función que nos permite recuperar el e-mail del empleado al que se ha realizado del envío
     * @return string El e-mail del empleado al que se le ha realizado el envío
     */
    public function getEmail_empleado() {
        return $this->email_empleado;
    }

    /**
     * Función que nos permite recuperar el cargo del empleado al que se ha realizado del envío
     * @return string El cargo del empleado al que se le ha realizado el envío
     */
    public function getCargo_empleado() {
        return $this->cargo_empleado;
    }

    /**
     * Función que nos permite recuperar la descripción del fichero enviado
     * @return string La descripción del fichero enviado
     */
    public function getDescripcion_fichero() {
        return $this->descripcion_fichero;
    }

    /**
     * Función que nos permite asignar un identificador de envío
     * @param int $id_envio El identificador de envío
     */
    public function setId_envio($id_envio) {
        $this->id_envio = $id_envio;
    }

    /**
     * Función que nos permite asignar la fecha de envío
     * @param string $fecha_envio La fecha de envío
     */
    public function setFecha_envio($fecha_envio) {
        $this->fecha_envio = $fecha_envio;
    }

    /**
     * Función que nos permite asignar la descripción de la cuenta de e-mail 
     * desde la que se realiza el envío
     * @param string $email_envio la descripción de la cuenta de e-mail desde 
     * la que se realiza el envío
     */
    public function setEmail_envio($email_envio) {
        $this->email_envio = $email_envio;
    }

    /**
     * Función que nos permite asignar el nombre del empleado al que se le 
     * ha realizado el envío
     * @param string $nombre_empleado Nombre del empleado al que se le ha 
     * realizado un envío
     */
    public function setNombre_empleado($nombre_empleado) {
        $this->nombre_empleado = $nombre_empleado;
    }

    /**
     * Función que nos permite asignar el apellido del empleado al que se le 
     * ha realizado el envío
     * @param string $apellido_empleado Apellido del empleado al que se le ha 
     * realizado un envío
     */
    public function setApellido_empleado($apellido_empleado) {
        $this->apellido_empleado = $apellido_empleado;
    }

    /**
     * Función que nos permite asignar el e-mail del empleado al que se le 
     * ha realizado el envío
     * @param string $email_empleado E-mail del empleado al que se le ha 
     * realizado un envío
     */
    public function setEmail_empleado($email_empleado) {
        $this->email_empleado = $email_empleado;
    }

    /**
     * Función que nos permite asignar el cargo del empleado
     * @param string $cargo_empleado Cargo del empleado
     */
    public function setCargo_empleado($cargo_empleado) {
        $this->cargo_empleado = $cargo_empleado;
    }

    /**
     * Función que nos permite asignar la descripción del fichero que se ha enviado
     * @param string $descripcion_fichero La descripción del fichero que se ha enviado
     */
    public function setDescripcion_fichero($descripcion_fichero) {
        $this->descripcion_fichero = $descripcion_fichero;
    }

}

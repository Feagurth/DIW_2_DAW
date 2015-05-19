<?php

/*
 * Copyright (C) 2015 Luis Cabrerizo Gómez
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
 * Clase para trabjar con los datos de email
 * 
 * @package Objetos
 * @author Luis Cabrerizo Gómez
 */
class Email implements JsonSerializable {

    /**
     * Identificador de la cuenta correo
     * @var int
     */
    private $id_email;
    
    /**
     * Descripción de la cuenta correo
     * @var type 
     */
    private $descripcion;    

    /**
     * Nombre de usuario de la cuenta de correo
     * @var string
     */
    private $usuario;

    /**
     * Contraseña de la cuenta de correo
     * @var string
     */
    private $pass;

    /**
     * Dirección smtp de la cuenta de correo
     * @var string
     */
    private $servidor;

    /**
     * Puerto de la cuenta de correo
     * @var int
     */
    private $puerto;

    /**
     * Tipo de seguridad de la cuenta de correo
     * @var string
     */
    private $seguridad;

    /**
     * Especifica si la cuenta requiere autentificacion
     * @var type 
     */
    private $autentificacion;

    /**
     * Constructor de la clase Email
     * @param array $row Array con los datos de email
     */
    public function __construct($row) {
        $this->id_email = $row['id_email'];
        $this->descripcion = $row['descripcion'];
        $this->usuario = $row['usuario'];
        $this->pass = $row['pass'];
        $this->servidor = $row['servidor'];
        $this->puerto = $row['puerto'];
        $this->seguridad = $row['seguridad'];
        $this->autentificacion = $row['autentificacion'];
        
    }

    /**
     * Función para recuperar el identificador de la cuenta de correo
     * @return int El identificador de la cuenta de correo
     */
    public function getId_email() {
        return $this->id_email;
    }

    /**
     * Función para recuperar la descripción de la cuenta de correo
     * @return string La descripción de la cuenta de correo
     */
    public function getDescripcion() {
        return $this->descripcion;
    }        
    
    /**
     * Función para recuperar el usuario de la cuenta de correo
     * @return string El usuario de la cuenta de correo
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Función para recuperar la contraseña de la cuenta de correo
     * @return string La contraseña de la cuenta de correo
     */
    public function getPass() {
        return $this->pass;
    }

    /**
     * Función para recuperar la dirección del servidor SMTP de la cuenta de correo
     * @return string El servidor de la cuenta de correo
     */
    public function getServidor() {
        return $this->servidor;
    }

    /**
     * Función para recuperar el puerto de la cuenta de correo
     * @return int El puerto de la cuenta de correo
     */
    public function getPuerto() {
        return $this->puerto;
    }

    /**
     * Función para recuperar el tipo de seguridad de la cuenta de correo
     * @return string El tipo de seguridad de la cuenta de correo
     */
    public function getSeguridad() {
        return $this->seguridad;
    }

    /**
     * Función para recuperar la necesidad de autentificaión de la cuenta de correo
     * @return string La necesidad de autentificaión de la cuenta de correo
     */
    public function getAutentificacion() {
        return $this->autentificacion;
    }

    /**
     * Función para asignar el identificador de la cuenta de correo
     * @param int $id_email El identificador del correo
     */
    public function setId_email($id_email) {
        $this->id_email = $id_email;
    }

    /**
     * Función para asignar la descripción de la cuenta de correo
     * @param string $descripcion La descripción de la cuenta de correo
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }    
    
    /**
     * Función para asignar el usuario de la cuenta de correo
     * @param string $usuario El usuario de la cuenta de correo
     */
    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    /**
     * Función para asignar la contraseña de la cuenta de correo
     * @param string $pass La contraseña de la cuenta de correo
     */
    public function setPass($pass) {
        $this->pass = $pass;
    }

    /**
     * Función para asignar la dirección del servidor SMTP de la cuenta de correo
     * @param string $servidor La dirección del servidor SMTP 
     */
    public function setServidor($servidor) {
        $this->servidor = $servidor;
    }

    /**
     * Función para asignar el puerto de la cuenta de correo
     * @param int $puerto El puerto de la cuenta de correo
     */
    public function setPuerto($puerto) {
        $this->puerto = $puerto;
    }

    /**
     * Función para asignar la seguridad de la cuenta de correo
     * @param string $seguridad El tipo de seguridad de la cuenta de correo
     */
    public function setSeguridad($seguridad) {
        $this->seguridad = $seguridad;
    }

    /**
     * Función para asignar la necesidad de autentificación de la cuenta de correo
     * @param string $autentificacion La necesidad de autentificación de la cuenta de correo
     */
    public function setAutentificacion($autentificacion) {
        $this->autentificacion = $autentificacion;
    }

    /**
     * Función para serializar los datos en formato Json
     * @return Json Los datos de la clase en formato Json
     */
    public function jsonSerialize() {

        // Creamos un array con la información que contiene el objeto
        $data = array(
            "id_email" => $this->id_email,
            "descripcion" => $this->descripcion,
            "usuario" => $this->usuario,
            "pass" => $this->pass,
            "servidor" => $this->servidor,
            "puerto" => $this->puerto,
            "seguridad" => $this->seguridad,
            "autentificacion" => $this->autentificacion            
        );

        // Devolvemos el array como resultado de la serialización
        return $data;
    }

}

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
 * Clase para trabajar con los envios
 *
 * @author Luis Cabrerizo Gómez
 */
class Envio {

    /**
     * Variable que contiene el identificador del envío
     * @var int El id del envío
     */
    private $id_envio;

    /**
     * Variable que contiene un objeto Email con los datos del E-Mail del envío 
     * @var \Email Contiene un objeto Email con los datos del E-Mail del envío
     */
    private $email;

    /**
     * Variable que contiene un objeto Fichero con los datos del Fichero enviado
     * @var \Fichero Objeto Fichero con los datos del Fichero enviado
     */
    private $fichero;

    /**
     * Variable que contiene un objeto Grupo con los datos del grupo al que se ha enviado el fichero
     * @var \Grupo Objeto Grupo con los datos del grupo al que se ha enviado el fichero
     */
    private $grupo;

    /**
     * Variable que contiene un array de objetos Empleado con los datos de los empleados a los que se ha enviaod el envío
     * @var \Empleado Array de objetos Empleado con los datos de los empleados a los que se ha enviaod el envío
     */
    private $empleados;

    /**
     * Función que nos permite recuperar el valor del identificador del envío
     * @return int El identificador dle envío
     */
    public function getId_envio() {
        return $this->id_envio;
    }

    /**
     * Función que nos permite recuperar la información de E-Mail relacionada con el envío
     * @return \Email La información de E-Mail relacionada con el envío
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Función que nos permite recuperar la información de fichero relacionada con el envío
     * @return \Fichero La información de Fichero relacionada con el envío
     */
    public function getFichero() {
        return $this->fichero;
    }

    /**
     * Función que nos permite recuperar la información de grupo relacionada con el envío
     * @return \Grupo La información de grupo relacionada con el envío
     */
    public function getGrupo() {
        return $this->grupo;
    }

    /**
     * Función que nos permite recuperar la información de empleados relacionada con el envío
     * @return \Empleado La información de empleados relacionada con el envío
     */
    public function getEmpleados() {
        return $this->empleados;
    }

    /**
     * Constructor de la clase envío
     * @param int $id_envio Identificador de envío
     * @throws Exception Si se produce una excepción, la lanzamos
     */
    public function __construct($id_envio) {
        try {
            // Asignamos el identificador de envío
            $this->id_envio = $id_envio;

            // Cremaos una nueva instancia de la base de datos
            $db = new DB();

            // Recuperamos los datos del envío usando el id
            $datos = $db->recuperarEnvio($id_envio);

            // Recuperamos los datos de E-Mail y los asignamos
            $this->email = $db->recuperarEmail($datos['id_email']);

            // Recuperamos los datos del fichero y los asignamos
            $this->fichero = $db->recuperarFichero($datos['id_fichero']);

            // Recuperamos los datos del grupo y los asignamos
            $this->grupo = $db->recuperarGrupo($datos['id_grupo']);
            
            // Recuperamos los datos de de los empleados  y los asignamos
            $this->empleados = $db->recuperarEmpleadosEnvio($id_envio);
        } catch (Exception $ex) {
            // Si tenemos una excepción, la lanzamos
            throw $ex;
        }
    }

}

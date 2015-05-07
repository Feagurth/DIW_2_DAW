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
 * Clase para almacenar los ficheros incluidos en la base de datos
 *
 * @author Luis Cabrerizo Gómez
 */
class Fichero {

    /**
     * Identificador del fichero
     * @var int
     */
    private $id_fichero;

    /**
     * Tamaño del fichero
     * @var int
     */
    private $tamanyo;

    /**
     * Tipo de fichero expresado en código MIME
     * @var string
     */
    private $tipo;

    /**
     * Nombre del fichero
     * @var string
     */
    private $nombre;

    /**
     * Descripción del fichero
     * @var string
     */
    private $descripcion;

    /**
     * Fichero en binario
     * @var string
     */
    private $fichero;

    /**
     * Constructor de la clase Fichero
     * @param array $row Array con los datos del fichero
     */
    public function __construct($row) {

        $this->id_fichero = $row['id_fichero'];
        $this->nombre = $row['nombre'];
        $this->tamanyo = $row['tamanyo'];
        $this->tipo = $row['tipo'];
        $this->descripcion = $row['descripcion'];
        $this->fichero = $row['fichero'];
    }

    /**
     * Método que nos permite recuperar el Id del documento
     * @return int
     */
    public function getId_fichero() {
        return $this->id_fichero;
    }

    /**
     * Método que nos permite recuperar el tamaño del documento
     * @return int
     */
    public function getTamanyo() {
        return $this->tamanyo;
    }

    /**
     * Método que nos permite recuperar el tipo del documento en formato MIME
     * @return string
     */
    public function getTipo() {
        return $this->tipo;
    }

    /**
     * Método que nos permite recuperar el nombre del documento
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Función que nos permite recuperar la descripciónd el fichero
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * Método que nos permite recuperar el documento
     * @return stream
     */
    public function getFichero() {
        return $this->fichero;
    }

    /**
     * Método que nos permite asignar el Id del documento
     * @param int $id_fichero El identificador del fichero
     */
    public function setId_fichero($id_fichero) {
        $this->id_fichero = $id_fichero;
    }

    /**
     * Método que nos permite asignar el tamaño del documento
     * @param int $tamanyo El tamaño del fichero
     */
    public function setTamanyo($tamanyo) {
        $this->tamanyo = $tamanyo;
    }

    /**
     * Método que nos permite asignar el tipo del documento
     * @param string $tipo El tipo del fichero en formaot MIME
     */
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    /**
     * Método que nos permite asignar el nombre del documento
     * @param string $nombre El nombre del fichero
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    /**
     * Función que nos permite asignar la descripción del fichero
     * @param string $descripcion La descripción del fichero
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    /**
     * Método que nos permite asignar el documento
     * @param Stream $fichero El contenido del fichero en formato stream
     */
    public function setFichero($fichero) {
        $this->fichero = $fichero;
    }

}

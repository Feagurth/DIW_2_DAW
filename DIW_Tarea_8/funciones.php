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

function validarCadenaConNumeros($dato) {
    // Inicializamos la variable de salida al valor que tendría si 
    // toda la validación fuese correcta
    $salida = TRUE;

    // Verificamos con expresiones regulares que los caracteres 
    // introducidos para el remitente son los permitidos
    if (!preg_match("/^[0-9a-zA-ZñÑáÁéÉíÍóÓúÚ ]+$/", $dato)) {
        // Si la validación no se cumple, asignamos el valor 
        // correspondiente a la variable de salida
        $salida = FALSE;
    }
    
    // Devolvemos la variable con el resultado de la validación
    return $salida;
}
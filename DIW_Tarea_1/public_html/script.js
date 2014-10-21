/*
 Copyright (C) 2014 Luis Cabrerizo Gómez
 
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
 */

/**
 * Función dummy
 * @returns {undefined}
 */
function navegacion1() {
    alert("Se cargará en la zona de contenidos los artículos de panadería.");
}

/**
 * Función dummy
 * @returns {undefined}
 */
function navegacion2() {
    alert("Se cargará en la zona de contenidos los artículos de pastelería y bollería.");
}
/**
 * Función dummy
 * @returns {undefined}
 */
function navegacion3() {
    alert("Se cargará en la zona de contenidos los artículos de la categoría de empanadas");
}

/**
 * Función para cambiar el idioma de la página
 * @returns {undefined}
 */
function cambioIdioma() {

    // Buscamos el objeto que contiene la caja desplegable
    var e = document.getElementById("idioma");

    // Almacenamos el valor del elemento seleccionado
    var valor = parseInt(e.options[e.selectedIndex].value);

    // Comprobamos su valor. Si es 1 es castellano, si es 2 es portugues
    switch (valor)
    {
        case 1:
        {
            // Modificamos los textos e incluimos los textos en castellano
            document.getElementById("cat1").innerHTML = "Panadería";
            document.getElementById("cat2").innerHTML = "Pastelería - Bollería";
            document.getElementById("cat3").innerHTML = "Empanadas";
            break;
        }
        case 2:
        {

            // Modificamos los textos e incluimos los textos en portugués
            document.getElementById("cat1").innerHTML = "Padaria";
            document.getElementById("cat2").innerHTML = "Pastelaria - Confeitaria";
            document.getElementById("cat3").innerHTML = "Empadas";
            break;
        }
    }
}
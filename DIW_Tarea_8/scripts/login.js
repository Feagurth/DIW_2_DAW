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

// Definimos un evento que se ejecuta cuando el documento está listo y que 
// ejecuta la función inicio
$('document').ready(inicio);


/**
 * Función cuya finalidad es definir los eventos del formulario e inicializar 
 * las variables que sean necesarias para el buen funcionamiento de los métodos 
 * que se encuentran en el archivo
 * @returns {undefined}
 */
function inicio()
{
    // Definimos un evento para el botón con id submit al hacer click sobre el.
    $('#submit').click(validarUsuario);

}


/**
 * Función que nos permite validar el usuario y la contraseña introducidos 
 * en el formulario
 * @returns {Boolean} True si los datos son correctos, False si no lo son
 */
function validarUsuario()
{
    // Definimos una expresión regular para validar el usuario y la contraseña
    // Esta expresión regular solo permitirá introducir letras y números
    var regex = /^[a-zA-Z0-9]+$/;

    // Deshabilitamos el botón de aceptar y le añadimos una clase especial para 
    // oscurecerlo y que parezca que está deshabilitado
    $('#submit').attr('disabled', 'disabled').addClass("deshabilitado");

    // Cambiams el texto del botón para que el usuario sepa que está pasando
    $('#submit').val('Validando...');

    // Validamos el usuario y la contraseña
    if (regex.test($('#user').val()) && regex.test($('#pass').val()))
    {
        // Si todo es correcto, volvemos a habilitar el botón y le quitamos 
        // la clase deshabilitado
        $('#submit').val('Enviar');
        $("#submit").removeAttr('disabled').removeClass("deshabilitado");

        // Devolvemos TRUE si todo es corrrecto, permitiendo así el envío del 
        // formulario
        return true;
    }
    else
    {
        // Si no es correcto, volvemos a habilitar el botón y le quitamos 
        // la clase deshabilitado
        $('#submit').val('Enviar');
        $("#submit").removeAttr('disabled').removeClass("deshabilitado");

        // Devolvemos FALSE si hay errores, impidiendo así el envío del formulario.
        return false;
    }
}

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
 * Función que nos permite validar una edad entre 0 y 105 años
 * @param {type} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarEdad(valor)
{
    // Creamos la expresión regular basándonos en 3 premisas básicas:
    // 1.- Que el usuario puede meter un número de 0 a 9. 
    //     Se define la expresión regular como [0-9]
    // 2.- Que el usuario puede meter un número de dos cifras que no empiece por cero. 
    //     Se define la expresión regular como [1-9][0-9]
    // 3.- Que el usuario puede meter un número de tres cifras, donde la primera 
    //     tiene que ser obligatoriamente un 1, la segunda un 0 y la tercera 
    //     puede variar entre 0 y 5 cubriendose así todas las posibilidades 
    //     entre 100 y 105. Se define la expresión regular como  [1][0][0-5]
    // Finalmente para fusionar todas las premisas se usa el elemento | y se 
    // encapsulan entre paréntesis para que confirmen un todo a la hora de 
    // establecer los anclajes que serán al principio y al final de la candena a 
    // validar    
    expresion = /^([0-9]|[1-9][0-9]|[1][0][0-5])$/;

    return expresion.exec(valor);
}

/**
 * Función que nos permite validar una cadena de texto para que solo contenga carácteres válidos
 * @param {string} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarCadena(valor)
{
    // Validamos que solo puedan introducirse letras, en mayuscula o minúscula, 
    // con y sin acentos, espacios en blanco y números
    expresion = /^[a-zA-Z0-9ñÑ áéíóúÁÉÍÓÚ]+$/;
                

    return expresion.exec(valor);
}

/**
 * Función que nos permite validar un usuario
 * @param {string} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarUsuario(valor)
{
     // Validamos que solo puedan introducirse letras, en mayuscula o minúscula, 
    // y números
    expresion = /^[a-zA-Z0-9]+$/;
    
    return expresion.exec(valor);
}

/**
 * Función que nos permite validar una contraseña
 * @param {string} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarPass(valor)
{
     // Validamos que solo puedan introducirse letras, en mayuscula o minúscula, 
    // y números
    expresion = /^[a-zA-Z0-9]+$/;
    
    return expresion.exec(valor);
}



/**
 * Función que nos permite validar una fecha desde 1900 en adelante
 * @param {type} valor Fecha a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarFecha(valor)
{
    valor = valor.replace(/-/g, '/');

    // Expresión regular para validar fechas dd/mm/aaaa con años bisiestos. 
    // La primera parte de la cadena valida las fechas de los meses de 31 dias desde 1900 en adelante: ^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|
    // La segunda parte de la cadena valida las fechas de los meses de 30 dias desde 1900 en adelante: ((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|
    // La tercera parte de la cadena valida las fechas del mes de febrero validando un máximo de 28 dias: ((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|
    // La cuarta parte de la cadena valida las fechas de febrero de 29 dias para los años bisiestos que se detallan en las siguientes lineas: (29\/02\/
    // La quinta parte de la cadena especifica años bisiestos: ((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$
    // Finalmente todo se ancla para que las fechas formen parte del comienzo y final de la clase    
    expresion = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/;

    // Devolvemos el resultado de la validación de la fecha
    return expresion.exec(valor);
}

/**
 * Función que nos permite validar un DNI/NIE
 * @param {type} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarNIF(valor)
{
    // Expresión regular para validar DNI y NIE
    // La primera parte permite validar NIE con el formato X1234567A con guiones opcionales: /^(([X-Z]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|
    // La segunda parte permite validar NIE especiales con el formato K1234567A con guiones opcionales: (([K-M]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|
    // La tercera parte permite validar DNI con el formato 12345678A con guiones opcionales: ((\d{8})([-]?)([A-Z]{1}))$/
    // Finalmente se anclan la expresion regular al principio y al final de la candena
    expresion = /^(([X-Z]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|(([K-M]{1})([-]?)(\d{7})([-]?)([A-Z]{1}))|((\d{8})([-]?)([A-Z]{1}))$/;

    // Devolvemos el resultado de la validación del nif
    return expresion.exec(valor);
}

/**
 * Función que nos permite validar un email
 * @param {type} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarEmail(valor)
{
    // Expresión para validar el email
    // La primera parte permite introducir letras y números así como _, - y .: ^([a-zA-Z0-9_\-\.]+)
    // La segunda parte incluye la arroba: @
    // La tercera parte permite especificar la inclusión de abertura de corchetes para englobar el dominio: ((\[
    // La cuarta parte permite especificar direcciones IP como dominio: [0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|
    // La quinta parte permite especificar un nombre de dominio seguido de un punto: (([a-zA-Z0-9\-]+\.)+))
    // La sexta parte permite especificar un nombre de subdominio de un entre 3 y 7 caracteres: ([a-zA-Z]{2,4}|[0-9]{1,3})
    // La septima parte permite especificar la inclusion de cierre de corchetes para englobar el dominio: (\]?)$
    // Finalmente se anclan la expresion regular al principio y al final de la candena
    expresion = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;

    // Devolvemos el resultado de la validación del email
    return expresion.exec(valor);
}

/**
 * Función que nos permite validar si se ha seleccionado una provincia
 * @returns {Boolean} True si la validación es correcta, False en caso contrario
 */
function validarProvincia()
{
    // Comprobamos si el valor de la provincia vale 0, si es así no se ha 
    // seleccionado provincia alguna y devolvemos false, en caso contrario 
    // devolvemos true
    return (document.getElementById('provincia').value === "0" ? false : true);
}


/**
 * Función que nos permite validar un número de teléfono fijo o movil en España
 * @param {type} valor Cadena a validar
 * @returns {undefined} True si la validación es correcta, False en caso contrario
 */
function validarTelefono(valor)
{
    // Expresión que nos permite validar números de telefono fijos y móviles en España
    // La primera parte nos permite definir el primer dígito del número especificando que debe empezar por 9 o por 6: ^[9|6]{1}
    // La segunda parte nos permite especificar los siguientes seis dígitos del número de telfono agrupados de dos en dos, pudiendo estos grupos estar seguidos de un guión: ([\d]{2}[-]?){3}
    // La tercera parte nos permite especificar los últimos dos números del teléfono: [\d]{2}$
    // Finalmente se anclan la expresion regular al principio y al final de la candena
    expresion = /^[9|6]{1}([\d]{2}[-]?){3}[\d]{2}$/;

    // Devolvemos el resultado de la validación del telefono
    return expresion.exec(valor);
}

/**
 * Función que nos permite validar una hora en formato 12 y 24 horas
 * @param {type} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarHora(valor)
{
    // Expresión que nos permite validar horas
    // La primera parte nos permite validar horas que vayan de 00 a 19: ^([0-1][0-9]
    // La segunda parte nos permite validar horas que vayan de 20 a 23: |2[0-3])
    // La tercera parte añade como separador de horas y minutos los dos puntos: :
    // La cuarta parte nos permite especificar los minutos de 00 a 59: [0-5][0-9]$
    // Finalmente se anclan la expresion regular al principio y al final de la candena
    expresion = /^([0-1][0-9]|2[0-3]):[0-5][0-9]$/;

    // Devolvemos el resultado de la validación de la jpra
    return expresion.exec(valor);
}

/**
 * Método que nos permite realizar un envio de información al servidor
 * @param {type} path Ruta a donde vamos a enviar la información
 * @param {type} params Parametros a enviar
 * @param {type} method Método de envio: POST (default) o GET
 * @returns {undefined}
 */
function post(path, params, method) {
    // Definimos el método post como por defecto
    method = method || "post";

    // Creamos un elemento form en el documento y lo almacenamos en una variable
    var form = document.createElement("form");

    // Le ponemos como atributo el método y la ruta
    form.setAttribute("method", method);
    form.setAttribute("action", path);

    // Iteramos por todos los valores del array de parámetros
    for (var key in params) {

        // Comprobamos que tenga una propiedad propia
        if (params.hasOwnProperty(key)) {

            // Si es así, creamos un campo oculto y le añadimos como nombre la 
            // clave del array y como valor el contenido del mismo
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", key);
            hiddenField.setAttribute("value", params[key]);

            // Finalmente lo añadimos al formulario
            form.appendChild(hiddenField);
        }
    }

    // Finalmente añadimos el formulario al cuerpo de la página web desde 
    // donde se esté llamando
    document.body.appendChild(form);

    // Y se envía
    form.submit();
}

/**
 * Método para navegar entre los distintos menús de la página
 * @param {type} valorNavegacion Valor de navegación
 * @returns {undefined}
 */
function navegar(valorNavegacion)
{
    // Hacemos uso de la funcion post y enviamos los datos 
    // necesarios para navegar
    post('index.php', {indice: valorNavegacion});
}
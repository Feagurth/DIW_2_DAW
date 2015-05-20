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
//<editor-fold defaultstate="collapsed" desc=" Validaciones ">

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
    // y números, arroba y punto
    expresion = /^[a-zA-Z0-9@.]+$/;

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

/*
 * Functión que nos permite validar un servidor de correo SMTP
 * @param {string} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario

 */
function validarSMTP(valor)
{
    // Validamos que solo puedan introducirse direcciones smpt con el formato 
    // smtp.azAz.abc o smtp.azAz.ab    
    expresion = /^(smtp)\.([\w\-]+)\.[\w\-]{2,3}$/;

    return expresion.exec(valor);
}

/**
 * Functión que nos permite validar un número
 * @param {string} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarNumero(valor)
{
    // Validamos que solo puedan introducirse números
    expresion = /^[0-9]+$/;

    return expresion.exec(valor);
}

/**
 * Functión que nos permite validar una dirección
 * @param {string} valor Cadena a validar
 * @returns {Array} True si la validación es correcta, False en caso contrario
 */
function validarDireccion(valor)
{
    // Valida letras y números con espacios en blanco, vocales con acento, 
    // la ñ, puntos y comas y los símbolos ºª
    expresion = /^[a-zA-Z0-9ñÑ áéíóú.,ºª]+$/;
        
    return expresion.exec(valor);
}


/**
 * Función que nos permite validar un email
 * @param {string} valor Cadena a validar
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
 * Función que nos permite validar un número de teléfono fijo o movil en España
 * @param {string} valor Cadena a validar
 * @returns {undefined} True si la validación es correcta, False en caso contrario
 */
function validarTelefono(valor)
{
    // Expresión que nos permite validar números de telefono fijos y móviles en España
    // Valida los patrones  +34 [9|6|7]XX XX XX XX [9|6|7]XX XX XX XX [9|6|7]XX XX XX XX [9|6|7]XX-XX-XX-XX [9|6|7]XXXXXXXX
    expresion = /^((\+?34([ \t|\-])?)?[9|6|7]((\d{1}([ \t|\-])?[0-9]{3})|(\d{2}([ \t|\-])?[0-9]{2}))([ \t|\-])?[0-9]{2}([ \t|\-])?[0-9]{2})$/;
    
    // Devolvemos el resultado de la validación del telefono
    return expresion.exec(valor);
}


//</editor-fold>


/**
 * Método que nos permite realizar un envio de información al servidor
 * @param {string} path Ruta a donde vamos a enviar la información
 * @param {string[]} params Parametros a enviar
 * @param {string} method Método de envio: POST (default) o GET
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
 * @param {string} valorNavegacion Valor de navegación
 * @returns {undefined}
 */
function navegar(valorNavegacion)
{
    // Hacemos uso de la funcion post y enviamos los datos 
    // necesarios para navegar
    post('index.php', {indice: valorNavegacion});
}

/**
 * Función que nos permite ordenar una tabla por sus columnas
 * @param {object} table Tabla a ordenar
 * @param {string} order Orden: asc - Ascendente desc - Descendent
 * @param {string} column El número de la columna por la que se ordenará la tabla
 * @returns {undefined} 
 * */
function sortTable(table, order, column) {

    // Comprobamos si la ordenación va a ser ascendente
    var asc = order === 'asc';

    // Recuperamos el cuerpo de la tabla
    var tbody = table.find('tbody');

    // Buscamos todas las filas de la tabla y las ordenamos de acuerdo a la 
    // siguiente función
    tbody.find('tr').sort(function (a, b) {
        // Primero comprobamos el tipo de ordenación y despues comparamos las 
        // filas entre si usando localCompare para saber que fila va antes o 
        // despues que otra
        if (asc) {
            return $('td:nth-child(' + column + ')', a).text().localeCompare($('td:nth-child(' + column + ')', b).text());
        } else {
            return $('td:nth-child(' + column + ')', b).text().localeCompare($('td:nth-child(' + column + ')', a).text());
        }
        // Finalmente añadimos el nuevo orden de las filas al cuerpo de la tabla
    }).appendTo(tbody);
}
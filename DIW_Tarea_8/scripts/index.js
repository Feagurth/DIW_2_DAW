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


$('documento').ready(inicio);

/**
 * Función cuya finalidad es definir los eventos del formulario e inicializar 
 * las variables que sean necesarias para el buen funcionamiento de los métodos 
 * que se encuentran en el archivo
 * @returns {undefined}
 */
function inicio()
{
    // Definimos un evento para los encabezados de las columnas de las tablas 
    // al hacer click en ellos
    $(".listado table tr td.listadoCabecera").click(ordenarLista);
}

/**
 * Función que nos permite ordenar las listas pulsando en la cabecera de sus columnas
 * @returns {undefined}
 */
function ordenarLista()
{

    // Recuperamos el texto del encabezado para saber en que lista estamos 
    // actualmente y verificar las columnas
    tabla = $(".listado #botonera h2").text();

    var columnaPulsada;

    switch (tabla)
    {
        // En el caos de empleados
        case "Listado de empleados":
        {
            // Comprobamos el texto de la cabecera que hemos pulsado
            switch (this.innerText)
            {
                case "Nombre":
                {
                    // La primera columna pulsada
                    columnaPulsada = 1;
                    break;
                }
                case "Apellido":
                {
                    // La segunda columna pulsada
                    columnaPulsada = 2;
                    break;
                }
                case "Teléfono":
                {
                    // La tercera columna pulsada
                    columnaPulsada = 3;
                    break;
                }
                case "Especialidad":
                {
                    // La cuarta columna pulsada
                    columnaPulsada = 4;
                    break;
                }
                case "Cargo":
                {
                    // La quinta columna pulsada
                    columnaPulsada = 5;
                    break;
                }
                case "Dirección":
                {
                    // La sexta columna pulsada
                    columnaPulsada = 6;
                    break;
                }
                case "E-Mail":
                {
                    // La septima columna pulsada
                    columnaPulsada = 7;
                    break;
                }
            }


            break;
        }

        // En el caso de los grupos
        case "Listado de grupos":
        {
            // Comprobamos el texto de la cabecera que hemos pulsado
            switch (this.innerText)
            {
                case "Nombre":
                {
                    // La primera columna pulsada
                    columnaPulsada = 1;
                    break;
                }
                case "Descripción":
                {
                    // La segunda columna pulsada
                    columnaPulsada = 2;
                    break;
                }
            }
            break;
        }

        // En el caso de los ficheros
        case "Listado de ficheros":
        {
            // Comprobamos el texto de la cabecera que hemos pulsado
            switch (this.innerText)
            {
                case "Nombre":
                {
                    // La primera columna pulsada
                    columnaPulsada = 1;
                    break;
                }
                case "Tamaño":
                {
                    // La segunda columna pulsada
                    columnaPulsada = 2;
                    break;
                }
                case "Tipo":
                {
                    // La tercera columna pulsada
                    columnaPulsada = 3;
                    break;
                }
                case "Descripción":
                {
                    // La cuarta columna pulsada
                    columnaPulsada = 4;
                    break;
                }
            }

            break;
        }

        case "Listado de envíos":
        {
            // Comprobamos el texto de la cabecera que hemos pulsado
            switch (this.innerText)
            {
                case "Fecha":
                {
                    // La primera columna pulsada
                    columnaPulsada = 1;
                    break;
                }
                case "Cuenta de E-Mail":
                {
                    // La segunda columna pulsada
                    columnaPulsada = 2;
                    break;
                }
                case "Nombre de Empleado":
                {
                    // La tercera columna pulsada
                    columnaPulsada = 3;
                    break;
                }
                case "Apellido de Empleado":
                {
                    // La cuarta columna pulsada
                    columnaPulsada = 4;
                    break;
                }
                case "E-Mail de Empleado":
                {
                    // La quinta columna pulsada
                    columnaPulsada = 5;
                    break;
                }
                case "Cargo de Empleado":
                {
                    // La sexta columna pulsada
                    columnaPulsada = 6;
                    break;
                }
                case "Descripción de Fichero":
                {
                    // La septima columna pulsada
                    columnaPulsada = 7;
                    break;
                }
            }
            break;
        }

        // En el caso de los email
        case "Listado de cuentas de E-Mail":
        {
            switch (this.innerText)
            {
                case "Usuario":
                {
                    // La primera columna pulsada
                    columnaPulsada = 1;
                    break;
                }
                case "Servidor":
                {
                    // La segunda columna pulsada
                    columnaPulsada = 2;
                    break;
                }
                case "Puerto":
                {
                    // La tercera columna pulsada
                    columnaPulsada = 3;
                    break;
                }
                case "Seguridad":
                {
                    // La cuarta columna pulsada
                    columnaPulsada = 4;
                    break;
                }
                case "Descripción":
                {
                    // La quinta columna pulsada 
                    columnaPulsada = 5;
                    break;
                }

            }
            break;
        }

        // En el caso de la lista de usuario
        case "Listado de usuarios":
        {
            // Comprobamos el texto de la cabecera que hemos pulsado
            switch (this.innerText)
            {
                case "Usuario":
                {
                    // La primera columna pulsada
                    columnaPulsada = 1;
                    break;
                }
                case "Nombre":
                {
                    // La segunda columna pulsada
                    columnaPulsada = 2;
                    break;
                }
            }

            break;
        }

    }
    // Llamamos a la función de ordenación de tablas pasándole los parámetros 
    // necesarios
    sortTable($(".listado table"), 'asc', columnaPulsada);

}


/**
 * Función que nos permite ordenar una tabla por sus columnas
 * @param {type} table Tabla a ordenar
 * @param {type} order Orden: asc - Ascendente desc - Descendent
 * @param {type} column El número de la columna por la que se ordenará la tabla
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

    // Para finalizar buscamos todas las filas que contiene el cuerpo de la 
    // tabla y para cada una de ellas ejecutamos la siguiente función
    $("tr", ".listado table tbody").each(function (i) {
        // i, contiene el indice de la fila dentro de la tabla

        // Quitamos las clases para pintar el pijama que pudiese tener con anterioridad
        $(this).removeClass("pijama1");
        $(this).removeClass("pijama2");

        // Verificamos si la fila es par o impar
        if (i % 2 === 0) {
            // Si es par, le añadimos la clase pijama1 a la fila
            $(this).addClass("pijama1");
        } else {
            // Si es impar, le añadimos la clase pijama2 a la fila
            $(this).addClass("pijama2");
        }
    });
}
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


$('document').ready(inicio);

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

    // Iteramos por todas las cabeceras de la lista
    $(".listado table tr td.listadoCabecera").each(function ()
    {
        // Al texto de cada una le quitamos los triángulos que pudiesen tener
        $(this).text($(this).text().replace("▲", "").replace("▼", ""));

        // Asignamos a cada una de las cabeceras un atributo title con información del uso de las mismas
        $(this).attr("title", "Pulse en esta cabecera para ordenar la lista por " + $(this).text());
    });
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

    // Iniciamos la variable
    var columnaPulsada;

    // Comprobamos el orden anterior que tenía la columna
    if ((this.innerText.indexOf("▲") === -1))
    {
        // Si no tiene simbolo o el que tenia era el descendente, el orden 
        // esta vez será ascendente
        order = "asc";
    }
    else
    {
        // Si se encuentra el símbolo ascendente, la siguiente ordenación 
        // será descenciente
        order = "desc";
    }

    switch (tabla)
    {
        // En el caos de empleados
        case "Listado de empleados":
        {
            // Comprobamos el texto de la cabecera que hemos pulsado
            switch (this.innerText.replace("▲", "").replace("▼", ""))
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
            switch (this.innerText.replace("▲", "").replace("▼", ""))
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
            switch (this.innerText.replace("▲", "").replace("▼", ""))
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
            switch (this.innerText.replace("▲", "").replace("▼", ""))
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
            switch (this.innerText.replace("▲", "").replace("▼", ""))
            {
                case "Descripción":
                {
                    // La primera columna pulsada 
                    columnaPulsada = 1;
                    break;
                }                
                case "Usuario":
                {
                    // La segunda columna pulsada
                    columnaPulsada = 2;
                    break;
                }
                case "Servidor":
                {
                    // La tercera columna pulsada
                    columnaPulsada = 3;
                    break;
                }
                case "Puerto":
                {
                    // La cuarta columna pulsada
                    columnaPulsada = 4;
                    break;
                }
                case "Seguridad":
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
            switch (this.innerText.replace("▲", "").replace("▼", ""))
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

    // Iteramos por todas las cabeceras de la lista
    $(".listado table tr td.listadoCabecera").each(function ()
    {
        // Al texto de cada una le quitamos los triángulos que pudiesen tener
        $(this).text($(this).text().replace("▲", "").replace("▼", ""));
    });

    // Comprobamos el tipo de ordenación
    if (order === "asc")
    {
        // Añadimos el triángulo correspondiente
        this.innerText = this.innerText + "▲";
    }
    else
    {
        // Añadimos el triángulo correspondiente
        this.innerText = this.innerText + "▼";
    }

    // Llamamos a la función de ordenación de tablas pasándole los parámetros 
    // necesarios
    sortTable($(".listado table"), order, columnaPulsada);

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
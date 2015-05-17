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
    // Definimos un evento los botones de añadir y modificar
    $("div#botonera form#añadir > input[type='submit']").click(habilitarAñadirModificar);

    // Recuperamos datos que tengamos en los controles
    id_envio = $("div#botonera form#añadir > input[name='id_envio']").val();
    id_email = $("div#detalle form select#email option:selected").val();


    // Comprobamos si ya está creado el botón de cancelar. De ser así, la 
    // pantalla se ha cargado desde la ventana principal pulsando el botón de 
    // nuevo registro
    if ($("#detalle form #cancelar").length)
    {
        modo = "A";

        // Asignamos una función al evento click del botón de cancelar
        $("#detalle form#formEnvio").off("click", "#cancelar").on("click", "#cancelar", cancelarOperacion);

        // Asignamos una función al evento click del botón de aceptar
        $("#detalle form#formEnvio").off("click", "#aceptar").on("click", "#aceptar", aceptarOperacion);

    }

    $(document).on({
        ajaxStart: function () {
            $("body").addClass("loading");
        },
        ajaxStop: function () {
            $("body").removeClass("loading");
        }
    });

}

/**
 * Función que nos permite crear los controles necesarios para añadir o modificar 
 * registros, mientras que deshabilita los botones de acciones
 * @returns {Boolean} False para evitar eventos submit
 */
function habilitarAñadirModificar()
{
    id_envio;

    // Comprobamos si estamos haciendo una insercción o una modificación
    if (this.value.indexOf("Añadir") >= 0)
    {
        // Si es una insercción asignamos los valores del modo
        modo = "A";

        // Eliminamos los valores de los campos imput       
        $("div#detalle form select#email").val($("div#detalle form select#email option:first").val());
    }

    // Quitamos las tablas
    $('#detalle .tablaanidada').remove();

    // Asignamos atributos y clases para deshabilitar los botones superiores
    $("div#botonera form#añadir > input[type='submit']").attr("disabled", "disabled");
    $("div#botonera form#añadir > input[type='submit']").addClass("deshabilitado");

    // Habilitamos los campos para datos
    $("div#detalle form select#email").removeAttr("disabled");


    // Hacemos una petición AJAX a la página de mensajes de grupo detalle para 
    // recuperar la tabla de asignaciones
    $.ajax({
        // La hacemos por post
        type: "POST",
        // sin cache
        cache: false,
        // Especificamos la url donde se dirigirá la petición
        url: "envio_detalle_msg.php",
        // Especificamos los datos que pasaremos como parámetros en el post
        data: " peticion=GT"
                + "&modo=" + modo
                + "&id_envio=" + id_envio,
        // Definimos el tipo de datos que se nos va a devolver
        dataType: "html",
        // Definimos que hacer en caso de petición exitosa
        success: function (data) {

            // Asignamos la estructura html que devuelve la consulta y la 
            // anexamos al cuerpo, creando así la lista de relaciones de empleados
            $("#detalle #formEnvio").append(data);

            // Asignamos una función al evento click del botón de cancelar
            $("#detalle form#formEnvio").off("click", "#cancelar").on("click", "#cancelar", cancelarOperacion);

            // Asignamos una función al evento click del botón de aceptar
            $("#detalle form#formEnvio").off("click", "#aceptar").on("click", "#aceptar", aceptarOperacion);
        },
        // Definimos que hacer en caso de error
        error: function (jqXHR, textStatus, errorThrown) {

            // Creamos una cadena con el mensaje de respuesta
            var cadena = "<p>" + jqXHR.responseText + "</p>";

            // Lo ponemos en el div para mensajes de error
            $(".error p").replaceWith(cadena);
        }
    });

    // Devolvemos falso para que no se envíe el formulario
    return false;
}


/**
 * Función que nos permite deshabilitar los cambios hechos durante una modifición o una addición
 * @returns {Boolean} False para impedir que se lance el evento submit
 */
function cancelarOperacion()
{
    // Eliminamos los mensaje de error que pudiese haber de antemano
    $(".error p").replaceWith("<p></p>");

    // Comprobamos si estamos en modo modificación o si lo estamos en alta y 
    // el id_envio es distinto de 0. Esto implica que se ha cancelado la 
    // operación tras pulsar los botones de acción de la pantalla de detalle y 
    // no se está dando un alta desde la pantalla de listado
    if (modo !== "A" || (modo === "A" && id_envio !== "0"))
    {

        // Hacemos una petición AJAX a la página de mensajes de grupo detalle para 
        // recuperar la tabla de asignaciones
        $.ajax({
            // La hacemos por post
            type: "POST",
            // sin cache
            cache: false,
            // Especificamos la url donde se dirigirá la petición
            url: "envio_detalle_msg.php",
            // Especificamos los datos que pasaremos como parámetros en el post
            data: " peticion=GT"
                    + "&modo=V"
                    + "&id_envio=" + id_envio,
            // Definimos el tipo de datos que se nos va a devolver
            dataType: "html",
            // Definimos que hacer en caso de petición exitosa
            success: function (data) {

                // Quitamos las tablas
                $('#detalle .tablaanidada').remove();

                // Recuperamos los valores memoria
                $("div#detalle form select#seguridad").val($('div#detalle form select#seguridad option[value="' + id_email + '"]').val());

                // Habilitamos los botones
                $("div#botonera form#añadir > input[type='submit']").removeAttr("disabled");
                $("div#botonera form#añadir > input[type='submit']").removeClass("deshabilitado");

                // Deshabilitamos los campos para datos
                $("div#detalle form select#email").attr("disabled", "disabled");

                // Eliminamos los botones de aceptar, cancelar y los inputs ocultos que 
                // habíamos creado anteriormente
                $("div#detalle form input#aceptar").remove();
                $("div#detalle form input#cancelar").remove();
                $("div#detalle form input[name='id_email']").remove();
                $("div#detalle form input[name='modo']").remove();

                // Asignamos la estructura html que devuelve la consulta y la 
                // anexamos al cuerpo, creando así la lista de relaciones de empleados
                $("#detalle #formEnvio").append(data);

            },
            // Definimos que hacer en caso de error
            error: function (jqXHR, textStatus, errorThrown) {

                // Creamos una cadena con el mensaje de respuesta
                var cadena = "<p>" + jqXHR.responseText + "</p>";

                // Lo ponemos en el div para mensajes de error
                $(".error p").replaceWith(cadena);
            }
        });

    }
    else
    {
        // Si es un alta iniciada desde el listado, usamos la función navegar y 
        // volvemos al index pasándole como parámetro el índice asignado a 
        // esta página
        navegar(4);
    }

    // Devolvemos false para anular eventos submit 
    return false;
}


/**
 * Función que nos permite validar los datos del formulario
 * @returns {String|salida} Cadena vacia si no hay errores, un mensaje de 
 * error en caso contrario
 */
function validarDatos()
{
    // Inicializamos la variable de salida
    salida = "";

    // Devolvemos la salida
    return salida;
}




/**
 * Función que nos permite hacer una petición AJAX para insertar o modificar 
 * un registro
 * @returns {Boolean} False para evitar eventos submit
 */
function aceptarOperacion()
{
    // Realizamos la validación del formulario y volcamos el resultado en 
    // una variable
    resultado = validarDatos();

    // Comprobamos si la validación es correcta
    if (resultado === "")
    {

        // Creamos un array para almacenar los grupos seleccionados y otro para 
        // los ficheros seleccionados
        var gruposel = new Array();
        var ficherosel = new Array();

        // Itermaos por todos los checkboxes del listadado de grupos, y para cada uno de 
        // ellos, recuperamos su valor y lo agregamos al array
        $.each($("div#detalle div.tablaanidada.left table tbody tr.tablaCabecera td input[name='gruposel[]']:checked"), function () {
            gruposel.push($(this).val());
        });

        // Itermaos por todos los checkboxes del listadado de fichero, y para cada uno de 
        // ellos, recuperamos su valor y lo agregamos al array
        $.each($("div#detalle div.tablaanidada.right table tbody tr.tablaCabecera td input[name='ficherosel[]']:checked"), function () {
            ficherosel.push($(this).val());
        });


        // Si lo es, hacemos una petición AJAX a la página de mensajes de email detalle
        $.ajax({
            // La hacemos por post
            type: "POST",
            // sin cache
            cache: false,
            // Especificamos la url donde se dirigirá la petición
            url: "envio_detalle_msg.php",
            // Especificamos los datos que pasaremos como parámetros en el post
            data: " id_email=" + id_email
                    + "&gruposel=" + JSON.stringify(gruposel)
                    + "&ficherosel=" + JSON.stringify(ficherosel)
                    + "&peticion=A" 
                    + "&modo=" + modo 
                    + "&id_envio=" + id_envio,
            // Definimos el tipo de datos que se nos va a devolver
            dataType: "json",
            // Definimos que hacer en caso de petición exitosa
            success: function (data) {

                // Asignamos los valores recuperados del email y los asignamos 
                // las variables                 
                id_email = data.email[0].id_email;
                id_envio = data.id_envio;
                modo = "V";

                // Llamamos a la función cancelarOperación para que deshabilite el 
                // formulario, limpie mensajes de error, y asigne el valor de las 
                // variables a sus campos input correspondientes
                cancelarOperacion();
            },
            // Definimos que hacer en caso de error
            error: function (jqXHR, textStatus, errorThrown) {

                // Creamos una cadena con el mensaje de respuesta
                var cadena = "<p>" + jqXHR.responseText + "</p>";

                // Lo ponemos en el div para mensajes de error
                $(".error p").replaceWith(cadena);
            }
        });
    }
    else
    {
        // Si no, creamos una cadena con el resultado de la validación
        var cadena = "<p>" + resultado + "</p>";

        // Lo ponemos en el div para mensajes de error
        $(".error p").replaceWith(cadena);
    }


    // Devolvemos false para anular eventos submit 
    return false;
}
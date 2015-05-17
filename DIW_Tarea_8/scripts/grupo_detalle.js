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
    $("div#botonera form#modificar > input[type='submit']").click(habilitarAñadirModificar);

    // Recuperamos datos que tengamos en los controles
    id_grupo = $("div#botonera form#eliminar > input[name='id_grupo']").val();
    nombre = $("div#detalle form input#nombre").val();
    descripcion = $("div#detalle form input#descripcion").val();


    // Comprobamos si ya está creado el botón de cancelar. De ser así, la 
    // pantalla se ha cargado desde la ventana principal pulsando el botón de 
    // nuevo registro
    if ($("#detalle form #cancelar").length)
    {
        modo = "A";

        // Asignamos una función al evento click del botón de cancelar
        $("#detalle form").off("click", "#cancelar").on("click", "#cancelar", cancelarOperacion);

        // Asignamos una función al evento click del botón de aceptar
        $("#detalle form").off("click", "#aceptar").on("click", "#aceptar", aceptarOperacion);

    }
    else
    {
        $("div#cuerpo div.listadoSel form").off("click", "input#actualizar_empleados").on("click", "input#actualizar_empleados", actualizarRelacionesEmpleados);
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
    id_grupo;

    // Comprobamos si estamos haciendo una insercción o una modificación
    if (this.value.indexOf("Añadir") >= 0)
    {
        // Si es una insercción asignamos los valores del modo
        modo = "A";

        // Eliminamos los valores de los campos imput
        $("div#detalle form input#nombre").val("");
        $("div#detalle form input#descripcion").val("");

    }
    else
    {
        // Si es una modificación asignamos el modo y dejamos el valor de 
        // id_usuario que hemos recogido al cargar la página
        modo = "M";
    }

    // Asignamos atributos y clases para deshabilitar los botones superiores
    $("div#botonera form#añadir > input[type='submit']").attr("disabled", "disabled");
    $("div#botonera form#añadir > input[type='submit']").addClass("deshabilitado");
    $("div#botonera form#modificar > input[type='submit']").attr("disabled", "disabled");
    $("div#botonera form#modificar > input[type='submit']").addClass("deshabilitado");
    $("div#botonera form#eliminar > input[type='submit']").attr("disabled", "disabled");
    $("div#botonera form#eliminar > input[type='submit']").addClass("deshabilitado");

    // Habilitamos los campos para datos
    $("div#detalle form input#nombre").removeAttr("disabled");
    $("div#detalle form input#descripcion").removeAttr("disabled");

    // Creamos el botón de aceptar
    cadena = "<input tabindex='13' name='boton' id='aceptar' type='submit' value='Aceptar' title='Pulse para confirmar las modificaciones' />";

    // Creamos el botón de cancelar
    cadena += "<input tabindex='14' name='boton' id='cancelar' type='submit' value='Cancelar' title='Pulse para cancelar las modificaciones' />";

    // Creamos dos objetos ocultos para reenviar la información del modo de la página y del identificador del usuario
    cadena += "<input class='oculto' name='id_grupo' type='hidden' value='" + id_grupo + "' />";
    cadena += "<input class='oculto' name='modo' type='hidden' value='" + modo + "' />";

    // Añadimos los botones de aceptar y cancelar al formulario
    $("div#detalle form").append(cadena);

    // Asignamos una función al evento click del botón de cancelar
    $("#detalle form").off("click", "#cancelar").on("click", "#cancelar", cancelarOperacion);

    // Asignamos una función al evento click del botón de aceptar
    $("#detalle form").off("click", "#aceptar").on("click", "#aceptar", aceptarOperacion);

    $("div.listadoSel").remove();

    // Devolvemos falso para que no se envíe el formulario
    return false;
}


/**
 * Función que nos permite deshabilitar los cambios hechos durante una modifición o una addición
 * @returns {Boolean} False para impedir que se lance el evento submit
 */
function cancelarOperacion()
{

    // Comprobamos si estamos en modo modificación o si lo estamos en alta y 
    // el id_grupo es distinto de 0. Esto implica que se ha cancelado la 
    // operación tras pulsar los botones de acción de la pantalla de detalle y 
    // no se está dando un alta desde la pantalla de listado
    if (modo !== "A" || (modo === "A" && id_grupo !== "0"))
    {
        // Recuperamos los valores memoria
        $("div#detalle form input#nombre").val(nombre);
        $("div#detalle form input#descripcion").val(descripcion);


        // Habilitamos los botones
        $("div#botonera form#añadir > input[type='submit']").removeAttr("disabled");
        $("div#botonera form#añadir > input[type='submit']").removeClass("deshabilitado");
        $("div#botonera form#modificar > input[type='submit']").removeAttr("disabled");
        $("div#botonera form#modificar > input[type='submit']").removeClass("deshabilitado");
        $("div#botonera form#eliminar > input[type='submit']").removeAttr("disabled");
        $("div#botonera form#eliminar > input[type='submit']").removeClass("deshabilitado");

        // Deshabilitamos los campos para datos
        $("div#detalle form input#nombre").attr("disabled", "disabled");
        $("div#detalle form input#descripcion").attr("disabled", "disabled");

        // Eliminamos los botones de aceptar, cancelar y los inputs ocultos que 
        // habíamos creado anteriormente
        $("div#detalle form input#aceptar").remove();
        $("div#detalle form input#cancelar").remove();
        $("div#detalle form input[name='id_usuario']").remove();
        $("div#detalle form input[name='modo']").remove();

        // Eliminamos el listado de selección de empleados para volver a 
        // recrearlo despues
        $("div#cuerpo div.listadoSel").remove();

        // Limpiamos los mensajes de error
        $(".error p").replaceWith("<p></p>");
    }
    else
    {
        // Si es un alta iniciada desde el listado, usamos la función navegar y 
        // volvemos al index pasándole como parámetro el índice asignado a 
        // esta página
        navegar(2);
    }

    // Hacemos una petición AJAX a la página de mensajes de grupo detalle
    $.ajax({
        // La hacemos por post
        type: "POST",
        // sin cache
        cache: false,
        // Especificamos la url donde se dirigirá la petición
        url: "grupo_detalle_msg.php",
        // Especificamos los datos que pasaremos como parámetros en el post
        data: " modo=GT"
                + "&id_grupo=" + id_grupo,
        // Definimos el tipo de datos que se nos va a devolver
        dataType: "html",
        // Definimos que hacer en caso de petición exitosa
        success: function (data) {

            // Asignamos la estructura html que devuelve la consulta y la 
            // anexamos al cuerpo, creando así la lista de relaciones de empleados
            $("div#cuerpo").append(data);

            // Asignamos un evento para el botón de actualizar relaciones de empleados
            $("div#cuerpo div.listadoSel form").off("click", "input#actualizar_empleados").on("click", "input#actualizar_empleados", actualizarRelacionesEmpleados);

        },
        // Definimos que hacer en caso de error
        error: function (jqXHR, textStatus, errorThrown) {

            // Creamos una cadena con el mensaje de respuesta
            var cadena = "<p>" + jqXHR.responseText + "</p>";

            // Lo ponemos en el div para mensajes de error
            $(".error p").replaceWith(cadena);
        }
    });

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

    // Validamos el nombre
    if (!validarCadena($("div#detalle form input#nombre").val()))
    {
        // Si no es válido, modificamos la variable de salida con un mensaje de error
        salida = "Debe introducir un nombre válido";
    }

    // Validamos el usuario
    if (!validarCadena($("div#detalle form input#descripcion").val()))
    {
        // Si no es válido, modificamos la variable de salida con un mensaje de error
        salida = "Debe introducir una descripción válida";
    }

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

        // Si lo es, hacemos una petición AJAX a la página de mensajes de usuario detalle
        $.ajax({
            // La hacemos por post
            type: "POST",
            // sin cache
            cache: false,
            // Especificamos la url donde se dirigirá la petición
            url: "grupo_detalle_msg.php",
            // Especificamos los datos que pasaremos como parámetros en el post
            data: " nombre=" + $('div#detalle form input#nombre').val()
                    + "&descripcion=" + $('div#detalle form input#descripcion').val()
                    + "&modo=" + modo
                    + "&id_grupo=" + id_grupo,
            // Definimos el tipo de datos que se nos va a devolver
            dataType: "json",
            // Definimos que hacer en caso de petición exitosa
            success: function (data) {

                // Asignamos los valores recuperados del usuario y los asignamos 
                // las variables 
                id_grupo = data.id_grupo;
                nombre = data.nombre;
                descripcion = data.descripcion;


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


function actualizarRelacionesEmpleados()
{

    // Creamos un array 
    var datos = new Array();
    
    // Itermaos por todos los checkboxes del listadado, y para cada uno de 
    // ellos, recuperamos su valor y lo agregamos al array
    $.each($("div#cuerpo div.listadoSel form table tbody input[name='seleccionadoEmpleado[]']:checked"), function () {
        datos.push($(this).val());
    });


    // Si lo es, hacemos una petición AJAX a la página de mensajes de usuario detalle
    $.ajax({
        // La hacemos por post
        type: "POST",
        // sin cache
        cache: false,
        // Especificamos la url donde se dirigirá la petición
        url: "grupo_detalle_msg.php",
        // Especificamos los datos que pasaremos como parámetros en el post
        data: " seleccionadoEmpleado=" + JSON.stringify(datos)
                + "&modo=AE"
                + "&id_grupo=" + id_grupo,
        // Definimos el tipo de datos que se nos va a devolver
        dataType: "html",
        // Definimos que hacer en caso de error
        error: function (jqXHR, textStatus, errorThrown) {

            // Creamos una cadena con el mensaje de respuesta
            var cadena = "<p>" + jqXHR.responseText + "</p>";

            // Lo ponemos en el div para mensajes de error
            $(".error p").replaceWith(cadena);
        }
    });

    // Devolvemos false para que no se ejecuten eventos submit
    return false;

}
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
    id_fichero = $("div#botonera form#eliminar > input[name='id_fichero']").val();
    nombre = $("div#detalle form#formFichero input#nombre").val();
    tamanyo = $("div#detalle form#formFichero input#tamaño").val();
    tipo = $("div#detalle form#formFichero input#tipo").val();
    descripcion = $("div#detalle form#formFichero input#descripcion").val();
    fichero = $("div#detalle form#formFichero input#addfile").val();

    maxFileSize = $("div#detalle form#formFichero input#maxFileSize").val();

    // Comprobamos si ya está creado el botón de cancelar. De ser así, la 
    // pantalla se ha cargado desde la ventana principal pulsando el botón de 
    // nuevo registro
    if ($("#detalle form#formFichero #cancelar").length)
    {
        modo = "A";

        // Asignamos una función al evento click del botón de cancelar
        $("#detalle form#formFichero").off("click", "#cancelar").on("click", "#cancelar", cancelarOperacion);

        // Asignamos una función al evento click del botón de aceptar
        $("#detalle form#formFichero").off("click", "#aceptar").on("click", "#aceptar", aceptarOperacion);

        // Como se inicial la pantalla directamente en alta, no se crea 
        // mediante php el botón para visualizar fichero, por tanto creamos 
        // manualmente una cadena con los elementos que conforman el botón de 
        // visualizar ficheros
        cadena = "<form id='btnVisor' action='visor.php' method='post' target='_blank'>";
        cadena += "<button name='button' value='Ver Fichero' title='Pulse para ver el fichero'><img src='imagenes/download.png' alt='Ver fichero' title='Pulse para ver el fichero' /></button>";
        cadena += "<input class='oculto' name='id_fichero' type='hidden' value='" + id_fichero + "' />";
        cadena += "</form>";

        // Asignamos la cadena al comiento del div de detalle, para tener los 
        // elementos creados y poder hacer uso de ellos tras el alta del 
        // fichero si fuese menester
        $("div#cuerpo #detalle").prepend(cadena);

        // Ocultamos el botón del visor de documentos para que no se vea en el 
        // alta, pero se pueda mostrar al finalizarla
        $("#btnVisor").css('visibility', 'hidden');


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
    id_fichero;

    // Comprobamos si estamos haciendo una insercción o una modificación
    if (this.value.indexOf("Añadir") >= 0)
    {
        // Si es una insercción asignamos los valores del modo
        modo = "A";

        // Eliminamos los valores de los campos imput
        $("div#detalle form#formFichero input#nombre").val("");
        $("div#detalle form#formFichero input#tamaño").val("");
        $("div#detalle form#formFichero input#tipo").val("");
        $("div#detalle form#formFichero input#descripcion").val("");

        cadena = '<input type="hidden" id="maxFileSize" name="MAX_FILE_SIZE" value="' + maxFileSize + '" />';
        cadena += '<input title="Haga click para seleccionar el fichero a insertar en la base de datos" tabindex ="11" type="file" id="addfile" name="addfile[]" readonly="readonly" value="" accept=".bmp,.jpg,.gif,.png,.pdf,.doc,.odt,.rtf"/>';

        $("div#detalle form#formFichero input#descripcion").after(cadena);

        $("div#detalle form#formFichero input#addfile").val("");



    }
    else
    {
        // Si es una modificación asignamos el modo y dejamos el valor de 
        // id_fichero que hemos recogido al cargar la página
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
    $("div#detalle form#formFichero input#descripcion").removeAttr("disabled");
    $("div#detalle form#formFichero input#addfile").removeAttr("disabled");


    // Creamos el botón de aceptar
    cadena = "<input tabindex='17' name='boton' id='aceptar' type='submit' value='Aceptar' title='Pulse para confirmar las modificaciones' />";

    // Creamos el botón de cancelar
    cadena += "<input tabindex='18' name='boton' id='cancelar' type='submit' value='Cancelar' title='Pulse para cancelar las modificaciones' />";

    // Creamos dos objetos ocultos para reenviar la información del modo de la página y del identificador del fichero
    cadena += "<input class='oculto' name='id_fichero' type='hidden' value='" + id_fichero + "' />";
    cadena += "<input class='oculto' name='modo' type='hidden' value='" + modo + "' />";

    // Añadimos los botones de aceptar y cancelar al formulario
    $("div#detalle form#formFichero").append(cadena);

    // Asignamos una función al evento click del botón de cancelar
    $("#detalle form#formFichero").off("click", "#cancelar").on("click", "#cancelar", cancelarOperacion);

    // Asignamos una función al evento click del botón de aceptar
    $("#detalle form#formFichero").off("click", "#aceptar").on("click", "#aceptar", aceptarOperacion);

    // Ocultamos el botón del visor de documentos
    $("#btnVisor").css('visibility', 'hidden');

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
    // el id_fichero es distinto de 0. Esto implica que se ha cancelado la 
    // operación tras pulsar los botones de acción de la pantalla de detalle y 
    // no se está dando un alta desde la pantalla de listado
    if (modo !== "A" || (modo === "A" && id_fichero !== "0"))
    {
        // Recuperamos los valores memoria
        $("div#detalle form#formFichero input#nombre").val(nombre);
        $("div#detalle form#formFichero input#tamaño").val(tamanyo);
        $("div#detalle form#formFichero input#tipo").val(tipo);
        $("div#detalle form#formFichero input#descripcion").val(descripcion);
        $("div#detalle form#formFichero input#addfile").val(fichero);


        // Habilitamos los botones
        $("div#botonera form#añadir > input[type='submit']").removeAttr("disabled");
        $("div#botonera form#añadir > input[type='submit']").removeClass("deshabilitado");
        $("div#botonera form#modificar > input[type='submit']").removeAttr("disabled");
        $("div#botonera form#modificar > input[type='submit']").removeClass("deshabilitado");
        $("div#botonera form#eliminar > input[type='submit']").removeAttr("disabled");
        $("div#botonera form#eliminar > input[type='submit']").removeClass("deshabilitado");

        // Deshabilitamos los campos para datos
        $("div#detalle form#formFichero input#descripcion").attr("disabled", "disabled");
        $("div#detalle form#formFichero input#addfile").attr("disabled", "disabled");


        // Eliminamos los botones de aceptar, cancelar y los inputs ocultos que 
        // habíamos creado anteriormente
        $("div#detalle form#formFichero input#aceptar").remove();
        $("div#detalle form#formFichero input#cancelar").remove();
        $("div#detalle form#formFichero input[name='id_fichero']").remove();
        $("div#detalle form#formFichero input[name='modo']").remove();

        $(".error p").replaceWith("<p></p>");

        // Mostramos el botón del visor de documentos
        $("#btnVisor").css('visibility', 'visible');

        $("div#detalle form#formFichero input#addfile").remove();
    }
    else
    {
        // Si es un alta iniciada desde el listado, usamos la función navegar y 
        // volvemos al index pasándole como parámetro el índice asignado a 
        // esta página
        navegar(3);
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

    // Validamos la descripción
    if (!validarCadena($("div#detalle form#formFichero input#descripcion").val()))
    {
        // Si no es válido, modificamos la variable de salida con un mensaje de error
        salida = "Debe introducir una descripción válida";
    }

    // Comprobamos si el modo es de insercción
    if (modo === "A")
    {
        // Comprobamos si se ha seleccionado un fichero para la insercción
        if ($("div#detalle form#formFichero input#addfile").val() === "")
        {
            // Si no se ha seleccionado, modificamos la variable de salida con un mensaje de error
            salida = "Debe seleccionar un fichero para poder realizar una insercción";
        }
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

        // Si lo es, hacemos una petición AJAX a la página de mensajes de fichero detalle
        $.ajax({
            // La hacemos por post
            type: "POST",
            // sin cache
            cache: false,
            // Especificamos la url donde se dirigirá la petición
            url: "fichero_detalle_msg.php",
            // Especificamos los datos que pasaremos como parámetros en el post
            data: new FormData($("div#detalle form#formFichero")[0]),
            processData: false,
            contentType: false,
            // Definimos el tipo de datos que se nos va a devolver
            dataType: "json",
            // Definimos que hacer en caso de petición exitosa
            success: function (data) {

                // Asignamos los valores recuperados del fichero y los asignamos 
                // las variables 
                id_fichero = data.id_fichero;
                nombre = data.nombre;
                tamanyo = data.tamanyo;
                tipo = data.tipo;
                descripcion = data.descripcion;
                fichero = data.fichero;

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
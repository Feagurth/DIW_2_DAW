<?php

require_once './configuracion.inc.php';
require_once './objetos/Empleado.php';
require_once './objetos/Usuario.php';
require_once './objetos/Email.php';
require_once './objetos/Fichero.php';

class DB {

    /**
     * Objeto que almacenará la base de datos PDO
     * @var type PDO Object
     */
    private $diw;

// <editor-fold defaultstate="collapsed" desc=" Constructor ">

    /**
     * Constructor de la base de datos
     * @global type $serv Servidor donde está alojada el servidor de base de datos
     * @global type $base Nombre de la base de datos
     * @global type $usu Usuario de acceso a la base de datos
     * @global type $pas Contraseña para acceder a la base de datos
     * @throws Exception Se lanza una excepción si se produce algún error
     */
    public function __construct() {
        try {
            // Recuperamos las variables globales que contienen la configuración 
            // de conxión a la base de datos
            global $serv;
            global $base;
            global $usu;
            global $pas;

            // Creamos un array de configuración para la conexion PDO a la base de 
            // datos
            $opc = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");

            // Creamos la cadena de conexión con la base de datos
            $dsn = "mysql:host=$serv;dbname=$base";

            // Finalmente creamos el objeto PDO para la base de datos
            $this->diw = new PDO($dsn, $usu, $pas, $opc);

            $this->diw->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" Funciones Generales ">
    /**
     * Método que nos permite realizar consultas a la base de datos
     * @param type $sql Sentencia sql a ejecutar
     * @return type Resultado de la consulta
     * @throws Exception Lanzamos una excepción si se produce un error
     */
    private function ejecutaConsulta($sql) {

        try {
            // Comprobamos si el objeto se ha creado correctamente
            if (isset($this->diw)) {

                // De ser así, realizamos la consulta
                $resultado = $this->diw->query($sql);

                // Devolvemos el resultado
                return $resultado;
            }
        } catch (Exception $ex) {
            // Si se produce un error, lanzamos una excepción
            throw $ex;
        }
    }

    /**
     * Función que nos permite realizar consultas a la base de datos en forma consulta preparada
     * @param type $sql Sentencia sql a ejecutar
     * @param array $datos Datos a almacenar en forma de array
     * @return type El resultado de la operación
     * @throws Exception Lanza una excepción si se produce un error
     */
    private function ejecutaConsultaPreparada($sql, array $datos) {

        try {
            // Preaparamos una sentencia para la insercción del 
            // fichero en la tabla documentos            
            $stmt = $this->diw->prepare($sql);

            // Creamos un contador para ir asignando valores a la sentencia
            $cont = 1;

            // Iteramos por el array
            foreach ($datos as $key) {

                // Verificamos si el valor es un recurso y si este recurso 
                // es de tipo stream, el cual habra que pasarlo como un campo 
                // BLOB. Despues vamos asignando los valores del array a cada 
                // posición de la sentencia. 
                if (gettype($key) === "resource" && get_resource_type($key) === "stream") {

                    // Asignamos el valor del fichero, especificando 
                    // que se trata de un fichero tipo BLOB, para que 
                    // modifique la información guardada en formato 
                    // stream en la base de datos adaptandolo en el 
                    // proceso
                    $stmt->bindValue($cont, $key, PDO::PARAM_LOB);
                } else {

                    // Si no es un recurso el valor, lo asignamos sin parámetros
                    $stmt->bindValue($cont, $key);
                }

                // Aumentamos el contador
                $cont++;
            }

            // Devolvemos el resultado
            return $stmt->execute();
        } catch (Exception $ex) {
            // Si se produce una excepción la lanzamos para que se ocupe de ella 
            // la función que haya invocado a esta
            throw $ex;
        }
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" Funciones para Usuarios ">

    /**
     * Función que nos permite validar un usuario contra la base de datos
     * @param type $usuario Usuario a validar
     * @param type $password Contraseña a validar
     * @return type True si es un usuario correcto, False si no lo es
     * @throws Exception Se lanza una excepción si se produce un error
     */
    public function validarUsuario($usuario, $password) {

        // Especificamos la consulta que vamos a realizar sobre la base de datos        
        $sql = "select * from usuario where user='$usuario' and pass='$password'";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            global $nombreUsuario;

            $valores = $resultado->fetch();

            $nombreUsuario = $valores['nombre'];

            // Devolvemos el resultado pasandolo a booleano
            return $valores ? TRUE : FALSE;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite recuperar los usuarios de la base de datos usando un filtro
     * @param string $cadena Cadena por la que se va a filtrar
     * @param int $tipoFiltro Campo por el que se va a filtrar respecto al orden de la colunas en la base de datos
     * @return \Usuario Array de usuarios con la información de los mismos
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function listarUsuarios($cadena, $tipoFiltro) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM usuario";
        $orden = " ORDER BY nombre ASC";

        // Comprobamos que tenemos datos de filtro. De ser así, concatenamos 
        // una condición a la sentencia sql original
        if (($cadena !== NULL && $cadena !== "") && $tipoFiltro !== NULL) {
            // Dependiendo del tipo de filtro, agregaremos a la cadena sql una 
            // condición u otra
            switch ($tipoFiltro) {
                case 1: {
                        // Si se filtra por usuario
                        $sql .= " WHERE user LIKE '" . $cadena . "%'";
                        break;
                    }
                case 2: {
                        // Si se filtra por nombre
                        $sql .= " WHERE nombre LIKE '" . $cadena . "%'";
                        break;
                    }
                default:
                    break;
            }
        }

        // Concatenamos el orden a la cadena sql
        $sql .= $orden;

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Usuario($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite recuperar un usuario a partir de su identificador
     * @param int $id_usuario Identificador del usuario a recuperar
     * @return \Usuario Datos del empleado en un objeto Usuario
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function recuperarUsuario($id_usuario) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM usuario WHERE id_usuario= '" . $id_usuario . "'";


        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Usuario($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite eliminar un usuario
     * @param int $id_usuario Identificador del usuario a eliminar
     * @return int 0 Si es todo correcto, cualquier otro número si hay un error
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function eliminarUsuario($id_usuario) {
        // Creamos la consulta de borrado usando el identificador del usuario
        $sql = "DELETE FROM usuario where id_usuario = " . $id_usuario . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite insertar los datos de un empleado en la base de datos
     * @param Usuario $usuario Objeto Usuario que contiene los datos a almacenar
     * @return int El id del usuario insertado
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function insertarUsuario(Usuario $usuario) {

        // Creamos la consulta de insercción usando los valores del objeto 
        // Persona
        $sql = "INSERT INTO USUARIO VALUES (0, "
                . "'" . $usuario->getNombre() . "' , "
                . "'" . $usuario->getPass() . "', "
                . "'" . $usuario->getUser() . "');";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos el id del usuario creado
            return $this->diw->lastInsertId('USUARIO');
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite modificar los datos de un usuario en la base de datos
     * @param Usuario $usuario Objeto Usuario que contiene los datos a almacenar
     * @return int 0 si es correcto
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function modificarUsuario(Usuario $usuario) {

        // Creamos la consulta de actualiazción usando los valores del objeto 
        // Usuario
        $sql = "UPDATE usuario SET "
                . "user='" . $usuario->getUser() . "' , "
                . "pass='" . $usuario->getPass() . "' , "
                . "nombre='" . $usuario->getNombre() . "' WHERE id_usuario=" .
                $usuario->getId_usuario() . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" Funciones para Empleados">

    /**
     * Función que nos permite recuperar los Empleados de la base de datos usando un filtro
     * @param string $cadena Cadena por la que se va a filtrar
     * @param int $tipoFiltro Campo por el que se va a filtrar respecto al orden de la colunas en la base de datos
     * @return \Empleado Array de Empleados con la información de los mismos
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function listarEmpleados($cadena, $tipoFiltro) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM empleado";
        $orden = " ORDER BY nombre, apellido ASC";


        // Comprobamos que tenemos datos de filtro. De ser así, concatenamos 
        // una condición a la sentencia sql original
        if (($cadena !== NULL && $cadena !== "") && $tipoFiltro !== NULL) {
            // Dependiendo del tipo de filtro, agregaremos a la cadena sql una 
            // condición u otra
            switch ($tipoFiltro) {
                case 1: {
                        // Si se filtra por nombre
                        $sql .= " WHERE nombre LIKE '" . $cadena . "%'";
                        break;
                    }
                case 2: {
                        // Si se filtra por apellido
                        $sql .= " WHERE apellido LIKE '" . $cadena . "%'";
                        break;
                    }
                case 3: {
                        // Si se filtra por telefono
                        $sql .= " WHERE telefono LIKE '" . $cadena . "%'";
                        break;
                    }
                case 4: {
                        // Si se filtra por especialidad
                        $sql .= " WHERE especialidad LIKE '" . $cadena . "%'";
                        break;
                    }
                case 5: {
                        // Si se filtra por cargo
                        $sql .= " WHERE cargo LIKE '" . $cadena . "%'";
                        break;
                    }
                case 6: {
                        // Si se filtra por dirección
                        $sql .= " WHERE direccion LIKE '" . $cadena . "%'";
                        break;
                    }
                case 7: {
                        // Si se filtra por email
                        $sql .= " WHERE email LIKE '" . $cadena . "%'";
                        break;
                    }
                default:
                    break;
            }
        }

        // Concatenamos el orden a la cadena sql
        $sql .= $orden;

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Empleado($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite recuperar un empleado a partir de su identificador
     * @param int $id_empleado Identificador del empleado a recuperar
     * @return \Empleado Datos del empleado en un objeto Empleado
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function recuperarEmpleado($id_empleado) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM empleado WHERE id_empleado= '" . $id_empleado . "'";


        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Empleado($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite eliminar un empleado
     * @param int $id_empleado Identificador del empleado a eliminar
     * @return int 0 Si es todo correcto, cualquier otro número si hay un error
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function eliminarEmpleado($id_empleado) {
        // Creamos la consulta de borrado usando el identificador del empleado
        $sql = "DELETE FROM empleado where id_empleado = " . $id_empleado . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite insertar los datos de un empleado en la base de datos
     * @param Empleado $empleado Objeto Empleado que contiene los datos a almacenar
     * @return int 0 si es correcto
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function insertarEmpleado(Empleado $empleado) {

        // Creamos la consulta de insercción usando los valores del objeto 
        // Persona
        $sql = "INSERT INTO EMPLEADO VALUES (0, "
                . "'" . $empleado->getNombre() . "' , "
                . "'" . $empleado->getApellido() . "', "
                . "'" . $empleado->getTelefono() . "', "
                . "'" . $empleado->getEspecialidad() . "', "
                . "'" . $empleado->getCargo() . "', "
                . "'" . $empleado->getDireccion() . "', "
                . "'" . $empleado->getEmail() . "');";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos el id del empleado creado
            return $this->diw->lastInsertId('EMPLEADO');
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite modificar los datos de un empleado en la base de datos
     * @param Empleado $empleado Objeto Empleado que contiene los datos a almacenar
     * @return int 0 si es correcto
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function modificarEmpleado(Empleado $empleado) {

        // Creamos la consulta de actualiazción usando los valores del objeto 
        // Persona
        $sql = "UPDATE EMPLEADO SET "
                . "nombre='" . $empleado->getNombre() . "' , "
                . "apellido='" . $empleado->getApellido() . "' , "
                . "telefono='" . $empleado->getTelefono() . "' , "
                . "especialidad='" . $empleado->getEspecialidad() . "' , "
                . "cargo='" . $empleado->getCargo() . "' , "
                . "direccion='" . $empleado->getDireccion() . "' , "
                . "email='" . $empleado->getEmail() . "' WHERE id_empleado=" .
                $empleado->getId_empleado() . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" Funciones para E-Mail ">
    /**
     * Función que nos permite recuperar los usuarios de la base de datos usando un filtro
     * @param string $cadena Cadena por la que se va a filtrar
     * @param int $tipoFiltro Campo por el que se va a filtrar respecto al orden de la colunas en la base de datos
     * @return \Usuario Array de usuarios con la información de los mismos
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function listarEmails($cadena, $tipoFiltro) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM email";
        $orden = " ORDER BY descripcion ASC";

        // Comprobamos que tenemos datos de filtro. De ser así, concatenamos 
        // una condición a la sentencia sql original
        if (($cadena !== NULL && $cadena !== "") && $tipoFiltro !== NULL) {
            // Dependiendo del tipo de filtro, agregaremos a la cadena sql una 
            // condición u otra
            switch ($tipoFiltro) {
                case 1: {
                        // Si se filtra por usuario
                        $sql .= " WHERE usuario LIKE '" . $cadena . "%'";
                        break;
                    }
                case 2: {
                        // Si se filtra por servidor
                        $sql .= " WHERE servidor LIKE '" . $cadena . "%'";
                        break;
                    }
                case 3: {
                        // Si se filtra por puerto
                        $sql .= " WHERE puerto LIKE '" . $cadena . "%'";
                        break;
                    }
                case 4: {
                        // Si se filtra por seguridad
                        $sql .= " WHERE seguridad LIKE '" . $cadena . "%'";
                        break;
                    }
                case 5: {
                        // Si se filtra por descripción
                        $sql .= " WHERE descripcion LIKE '" . $cadena . "%'";
                        break;
                    }
                default:
                    break;
            }
        }

        // Concatenamos el orden a la cadena sql
        $sql .= $orden;

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Email($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite recuperar un email a partir de su identificador
     * @param int $id_email Identificador del email a recuperar
     * @return \Email Datos del email en un objeto Email
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function recuperarEmail($id_email) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM email WHERE id_email= '" . $id_email . "'";


        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Email($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite eliminar un email
     * @param int $id_email Identificador del email a eliminar
     * @return int 0 Si es todo correcto, cualquier otro número si hay un error
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function eliminarEmail($id_email) {
        // Creamos la consulta de borrado usando el identificador del email
        $sql = "DELETE FROM email where id_email = " . $id_email . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite insertar los datos de un email en la base de datos
     * @param Email $email Objeto Email que contiene los datos a almacenar
     * @return int El id del email insertado
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function insertarEmail(Email $email) {

        // Creamos la consulta de insercción usando los valores del objeto 
        // Persona
        $sql = "INSERT INTO EMAIL VALUES (0, "
                . "'" . $email->getUsuario() . "' , "
                . "'" . $email->getPass() . "', "
                . "'" . $email->getServidor() . "', "
                . "'" . $email->getPuerto() . "', "
                . "'" . $email->getSeguridad() . "', "
                . "'" . $email->getDescripcion() . "');";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos el id del email creado
            return $this->diw->lastInsertId('EMAIL');
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite modificar los datos de un email en la base de datos
     * @param Email $email Objeto Email que contiene los datos a almacenar
     * @return int 0 si es correcto
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function modificarEmail(Email $email) {

        // Creamos la consulta de actualiazción usando los valores del objeto 
        // Persona
        $sql = "UPDATE EMAIL SET "
                . "usuario='" . $email->getUsuario() . "' , "
                . "pass='" . $email->getPass() . "' , "
                . "servidor='" . $email->getServidor() . "' , "
                . "puerto='" . $email->getPuerto() . "' , "
                . "seguridad='" . $email->getSeguridad() . "' , "
                . "descripcion='" . $email->getDescripcion() . "' WHERE id_email=" .
                $email->getId_email() . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" Funciones de Ficheros ">
    /**
     * Función que nos permite recuperar los fichero de la base de datos usando un filtro
     * @param string $cadena Cadena por la que se va a filtrar
     * @param int $tipoFiltro Campo por el que se va a filtrar respecto al orden de la colunas en la base de datos
     * @return \Fichero Array de fichero con la información de los mismos
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function listarFicheros($cadena, $tipoFiltro) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM fichero";
        $orden = " ORDER BY nombre ASC";

        // Comprobamos que tenemos datos de filtro. De ser así, concatenamos 
        // una condición a la sentencia sql original
        if (($cadena !== NULL && $cadena !== "") && $tipoFiltro !== NULL) {
            // Dependiendo del tipo de filtro, agregaremos a la cadena sql una 
            // condición u otra
            switch ($tipoFiltro) {
                case 1: {
                        // Si se filtra por nombre
                        $sql .= " WHERE nombre LIKE '" . $cadena . "%'";
                        break;
                    }
                case 2: {
                        // Si se filtra por tamaño
                        $sql .= " WHERE tamanyo LIKE '" . $cadena . "%'";
                        break;
                    }
                case 3: {
                        // Si se filtra por tipo
                        $sql .= " WHERE tipo LIKE '" . $cadena . "%'";
                        break;
                    }
                case 4: {
                        // Si se filtra por descripcion
                        $sql .= " WHERE descripcion LIKE '" . $cadena . "%'";
                        break;
                    }
                default:
                    break;
            }
        }

        // Concatenamos el orden a la cadena sql
        $sql .= $orden;

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Fichero($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite insertar un fichero en la base de datos
     * @param Fichero $fichero Objeto Fichero que contiene la información a almacenar
     * @return type Resultado de la operación
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function insertarFichero(Fichero $fichero) {

        try {
            // Especificamos la sentencia SQL que insertará los valores del 
            // fichero en la base de datos
            $sql = "INSERT INTO FICHERO VALUES(?, ?, ?, ?, ?, ?)";

            // Pasamos el objeto a un array
            $datos = (array) $fichero;

            // Realizamos la consulta haciendo uso de la función privada diseñada 
            // para tal fin y almacenamos el resultado de la misma
            $resultado = $this->ejecutaConsultaPreparada($sql, $datos);

            // Verificamos el resultado de la operación
            if (!$resultado) {
                // Si el resultado no es correcto, hacemos un rollback
                $this->diw->rollBack();

                // Y lanzamos una excepción
                throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
            } else {
                // Si es correcto, devolvemos el id del fichero creado
                return $this->diw->lastInsertId('FICHERO');
            }
        } catch (Exception $ex) {

            // Si se produce una excepción, hacemos un rollback
            $this->diw->rollBack();

            // Y lanzamos la excepción
            throw $ex;
        }
    }

    /**
     * Función que nos permite recuperar un fichero a partir de su identificador
     * @param int $id_fichero Identificador del fichero a recuperar
     * @return \Fichero Datos del fichero en un objeto Fichero
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function recuperarFichero($id_fichero) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM fichero WHERE id_fichero= '" . $id_fichero . "'";


        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Fichero($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite modificar los datos de un fichero en la base de datos
     * @param Fichero $fichero Objeto Fichero que contiene los datos a almacenar
     * @return int 0 si es correcto
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function modificarFichero(Fichero $fichero) {

        // Creamos la consulta de actualiazción usando los valores del objeto 
        // Persona
        $sql = "UPDATE FICHERO SET "
                . "descripcion='" . $fichero->getDescripcion() . "' WHERE id_fichero=" .
                $fichero->getId_fichero() . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite eliminar un fichero
     * @param int $id_fichero Identificador del fichero a eliminar
     * @return int 0 Si es todo correcto, cualquier otro número si hay un error
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function eliminarFichero($id_fichero) {
        // Creamos la consulta de borrado usando el identificador del fichero
        $sql = "DELETE FROM FICHERO where id_fichero = " . $id_fichero . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

// </editor-fold>
// <editor-fold defaultstate="collapsed" desc=" Funciones de Grupos ">
    /**
     * Función que nos permite recuperar los grupos de la base de datos usando un filtro
     * @param string $cadena Cadena por la que se va a filtrar
     * @param int $tipoFiltro Campo por el que se va a filtrar respecto al orden de la colunas en la base de datos
     * @return \Grupo Array de grupos con la información de los mismos
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function listarGrupos($cadena, $tipoFiltro) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM grupo";
        $orden = " ORDER BY nombre ASC";

        // Comprobamos que tenemos datos de filtro. De ser así, concatenamos 
        // una condición a la sentencia sql original
        if (($cadena !== NULL && $cadena !== "") && $tipoFiltro !== NULL) {
            // Dependiendo del tipo de filtro, agregaremos a la cadena sql una 
            // condición u otra
            switch ($tipoFiltro) {
                case 1: {
                        // Si se filtra por nombre
                        $sql .= " WHERE nombre LIKE '" . $cadena . "%'";
                        break;
                    }
                case 2: {
                        // Si se filtra por descripcion
                        $sql .= " WHERE descripcion LIKE '" . $cadena . "%'";
                        break;
                    }
                default:
                    break;
            }
        }

        // Concatenamos el orden a la cadena sql
        $sql .= $orden;

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Grupo($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite recuperar un grupo a partir de su identificador
     * @param int $id_grupo Identificador del grupo a recuperar
     * @return \Grupo Datos del grupo en un objeto Grupo
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function recuperarGrupo($id_grupo) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM grupo WHERE id_grupo= '" . $id_grupo . "'";


        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new Grupo($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite eliminar un grupo
     * @param int $id_grupo Identificador del grupo a eliminar
     * @return int 0 Si es todo correcto, cualquier otro número si hay un error
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function eliminarGrupo($id_grupo) {
        // Creamos la consulta de borrado usando el identificador del grupo
        $sql = "DELETE FROM grupo where id_grupo = " . $id_grupo . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite insertar los datos de un empleado en la base de datos
     * @param Grupo $grupo Objeto Grupo que contiene los datos a almacenar
     * @return int El id del grupo insertado
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function insertarGrupo(Grupo $grupo) {

        // Creamos la consulta de insercción usando los valores del objeto 
        // Persona
        $sql = "INSERT INTO GRUPO VALUES (0, "
                . "'" . $grupo->getNombre() . "' , "
                . "'" . $grupo->getDescripcion() . "');";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos el id del grupo creado
            return $this->diw->lastInsertId('GRUPO');
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite modificar los datos de un grupo en la base de datos
     * @param Grupo $grupo Objeto Usuario que contiene los datos a almacenar
     * @return int 0 si es correcto
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function modificarGrupo(Grupo $grupo) {

        // Creamos la consulta de actualiazción usando los valores del objeto 
        // Grupo
        $sql = "UPDATE grupo SET "
                . "nombre='" . $grupo->getNombre() . "' , "
                . "descripcion='" . $grupo->getDescripcion() . "' WHERE id_grupo=" .
                $grupo->getId_grupo() . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

// </editor-fold>    
// <editor-fold defaultstate="collapsed" desc=" Funciones de Relaciones de Grupos Y Empleados ">

    /**
     * Función que nos permite recuperar los empleados pertenecientes a un grupo
     * @param int $id_grupo Identificador del grupo al que pertenecen los empleados     
     * @return \GrupoEmpleado Array de gruposempleado con la información de los mismos
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function listarRelacionesGrupoEmpleados($id_grupo) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM grupo_empleado WHERE id_grupo=" . $id_grupo;

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = $this->ejecutaConsulta($sql);

        // Comprobamos si hemos obtenido algún resultado
        if ($resultado) {

            // Definimos un nuevo array para almacenar el resultado
            $datos = array();

            // Añadimos un elemento por cada registro de entrada obtenido
            $row = $resultado->fetch();

            // Iteramos por los resultados obtenidos
            while ($row != null) {

                // Asignamos el resultado al array de resultados                
                $datos[] = new GrupoEmpleado($row);

                // Recuperamos una nueva fila
                $row = $resultado->fetch();
            }

            // Devolvemos el resultado
            return $datos;
        } else {
            // Si no tenemos resultados lanzamos una excepción
            throw new Exception();
        }
    }

    /**
     * Función que nos permite eliminar las relaciones de un grupo con los empleados
     * @param int $id_grupo Identificador del grupo del que se eliminan las relaciones
     * @return int 0 Si es todo correcto, cualquier otro número si hay un error
     * @throws Exception Lanza una excepción si se produce un error
     */
    public function eliminarRelacionesGrupoEmpleado($id_grupo) {
        // Creamos la consulta de borrado usando el identificador del grupo
        $sql = "DELETE FROM grupo_empleado where id_grupo = " . $id_grupo . ";";

        // Llamamos la a la función protegida de la clase para realizar la consulta
        $resultado = self::ejecutaConsulta($sql);

        // Comprobamos el resultado
        if ($resultado) {
            // Si es correcto, devolvemos 0
            return 0;
        } else {
            // En caso contrario, lanzamos una excepción
            throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
        }
    }

    /**
     * Función que nos permite almacenar en la base de datos las relaciones entre grupos y empleados
     * @param int $id_grupo Id del grupo
     * @param int $ids_empleados Array de id de empleados
     * @return int 0 si todo ha salido correcto
     * @throws Exception Se lanza una excepción si se produce un error
     */
    public function insertarRelacionesGrupoEmpleado($id_grupo, $ids_empleados) {

        try {

            // Iniciamos una transacción
            $this->diw->beginTransaction();

            // Especificamos la sentencia SQL que eliminará todas las relaciones 
            // del grupo con los empleados en la base de datos
            $sql = "DELETE FROM grupo_empleado where id_grupo = " . $id_grupo;

            // Ejecutamos la consulta
            $resultado = $this->diw->exec($sql);

            // Iteramos por todos los ids de los empleados
            foreach ($ids_empleados as $id_empleado) {

                // Creamos la sentencia slq
                $sql = "INSERT INTO GRUPO_EMPLEADO VALUES(0, " . $id_grupo . ", " . $id_empleado . ")";

                // La ejecutamos
                $resultado = $this->diw->exec($sql);

                // Verificamos el resultado de la operación
                if (!$resultado) {
                    // Si hay algún error, lanzamos una excepción
                    throw new Exception($this->diw->errorInfo()[2], $this->diw->errorInfo()[1]);
                }
            }

            // Finalizamos la transacción
            $this->diw->commit();

            // Si es correcto, devolvemos el id del fichero creado
            return 0;
        } catch (Exception $ex) {

            // Si se produce una excepción, hacemos un rollback
            $this->diw->rollBack();

            // Y lanzamos la excepción
            throw $ex;
        }
    }

// </editor-fold>
}

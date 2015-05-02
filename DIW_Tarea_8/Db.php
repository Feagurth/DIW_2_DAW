<?php

require_once './configuracion.inc.php';
require_once './objetos/objEmpleado.php';

class DB {

    /**
     * Objeto que almacenará la base de datos PDO
     * @var type PDO Object
     */
    private $diw;

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
     * Función que nos permite realizar consultas a la base de datos en forma de transacciones
     * @param type $sql Sentencia sql a ejecutar
     * @param array $datos Datos a almacenar en forma de array
     * @return type El resultado de la operación
     * @throws Exception Lanza una excepción si se produce un error
     */
    private function ejecutaConsultaTransaccion($sql, array $datos) {

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

    
    public function listarEmpleado($cadena, $tipoFiltro) {
        // Especificamos la consulta que vamos a realizar sobre la base de datos
        $sql = "SELECT * FROM empleado";
        $orden = " ORDER BY nombre, apellido ASC";


        // Comprobamos que tenemos datos de filtro. De ser así, concatenamos 
        // una condición a la sentencia sql original
        if(($cadena !== NULL && $cadena !== "") && $tipoFiltro !== NULL)
        {
            // Dependiendo del tipo de filtro, agregaremos a la cadena sql una 
            // condición u otra
            switch ($tipoFiltro) {
                case 1:
                {
                    // Si se filtra por nombre
                    $sql .= " WHERE nombre='" . $cadena . "'";
                    break;
                }
                case 2:
                {
                    // Si se filtra por apellido
                    $sql .= " WHERE apellido='" . $cadena . "'";
                    break;
                }
                case 3:
                {
                    // Si se filtra por telefono
                    $sql .= " WHERE telefono='" . $cadena . "'";
                    break;
                }
                case 4:
                {
                    // Si se filtra por especialidad
                    $sql .= " WHERE especialidad='" . $cadena . "'";
                    break;
                }
                case 5:
                {
                    // Si se filtra por cargo
                    $sql .= " WHERE cargo='" . $cadena . "'";
                    break;
                }
                case 6:
                {
                    // Si se filtra por email
                    $sql .= " WHERE email='" . $cadena . "'";
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
    
}

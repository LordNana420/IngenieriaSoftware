<?php
require_once("Conexion.php");
require_once("Cliente.php");

class ClienteDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    /**
     * 🔹 Consultar todos los clientes
     */
    public function consultarTodos() {
        $sql = "SELECT idCliente, Nombre, Apellido, Telefono 
                FROM Cliente 
                ORDER BY Nombre ASC";

        $resultado = $this->conexion->getConexion()->query($sql);
        $clientes = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $clientes[] = new Cliente(
                    $fila['idCliente'],
                    $fila['Nombre'],
                    $fila['Apellido'],
                    $fila['Telefono']
                );
            }
        }

        return $clientes;
    }



    /**
     * 🔹 Cerrar conexión
     */
    public function cerrarConexion() {
        $this->conexion->cerrar();
    }
}
?>

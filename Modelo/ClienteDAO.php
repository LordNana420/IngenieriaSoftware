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
     * 🔹 Consultar cliente por ID
     */
    public function consultarPorId($idCliente) {
        $idCliente = intval($idCliente);
        $sql = "SELECT idCliente, Nombre, Apellido, Telefono 
                FROM Cliente 
                WHERE idCliente = $idCliente";

        $resultado = $this->conexion->getConexion()->query($sql);

        if ($resultado && $fila = $resultado->fetch_assoc()) {
            return new Cliente(
                $fila['idCliente'],
                $fila['Nombre'],
                $fila['Apellido'],
                $fila['Telefono']
            );
        }

        return null; // Si no existe el cliente
    }

    /**
     * 🔹 Insertar un nuevo cliente
     */
    public function insertar(Cliente $cliente) {
        $nombre = $this->conexion->getConexion()->real_escape_string($cliente->getNombre());
        $apellido = $this->conexion->getConexion()->real_escape_string($cliente->getApellido());
        $telefono = $this->conexion->getConexion()->real_escape_string($cliente->getTelefono());

        $sql = "INSERT INTO Cliente (Nombre, Apellido, Telefono) 
                VALUES ('$nombre', '$apellido', '$telefono')";

        return $this->conexion->getConexion()->query($sql);
    }

    /**
     * 🔹 Actualizar cliente
     */
    public function actualizar(Cliente $cliente) {
        $id = intval($cliente->getId());
        $nombre = $this->conexion->getConexion()->real_escape_string($cliente->getNombre());
        $apellido = $this->conexion->getConexion()->real_escape_string($cliente->getApellido());
        $telefono = $this->conexion->getConexion()->real_escape_string($cliente->getTelefono());

        $sql = "UPDATE Cliente 
                SET Nombre='$nombre', Apellido='$apellido', Telefono='$telefono' 
                WHERE idCliente=$id";

        return $this->conexion->getConexion()->query($sql);
    }

    /**
     * 🔹 Eliminar cliente
     */
    public function eliminar($idCliente) {
        $idCliente = intval($idCliente);
        $sql = "DELETE FROM Cliente WHERE idCliente=$idCliente";
        return $this->conexion->getConexion()->query($sql);
    }

    /**
     * 🔹 Cerrar conexión
     */
    public function cerrarConexion() {
        $this->conexion->cerrar();
    }
}
?>

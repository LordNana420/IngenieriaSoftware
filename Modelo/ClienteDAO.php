<?php
require_once("Conexion.php");
require_once("Cliente.php");

class ClienteDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    /**
     * 🔹 Consultar todos los clientes
     */
    public function consultarTodos()
    {
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
    public function consultarPorId($idCliente)
    {
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
    public function insertar(Cliente $cliente)
    {
        $conexion = $this->conexion->getConexion();

        $id = intval($cliente->getId());
        $nombre = $conexion->real_escape_string($cliente->getNombre());
        $apellido = $conexion->real_escape_string($cliente->getApellido());
        $telefono = $conexion->real_escape_string($cliente->getTelefono());

        $sqlVerificar = "SELECT idCliente FROM Cliente WHERE idCliente = $id";
        $resultado = $conexion->query($sqlVerificar);

        if ($resultado && $resultado->num_rows > 0) {
            // Ya existe un cliente (existe para no registrar)
            return [
                "exito" => false,
                "mensaje" => "La cedula registrada ya existe. No se puede registrar un cliente duplicado."
            ];
        } else {

            // Insertar el nuevo cliente (solo si no existe)
            $sqlInsertar = "INSERT INTO Cliente (idCliente, Nombre, Apellido, Telefono) 
                    VALUES ($id, '$nombre', '$apellido', '$telefono')";

            if ($conexion->query($sqlInsertar)) {
                return [
                    "exito" => true,
                    "mensaje" => "Cliente registrado correctamente"
                ];
            } else {
                return [
                    "exito" => false,
                    "mensaje" => "Error al registrar el cliente: " . $conexion->error
                ];
            }
        }
    }
    /**
     * Actualizar cliente
     */
    public function actualizar(Cliente $cliente)
    {
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
     * Eliminar cliente
     */
    public function eliminar($idCliente)
    {
        $idCliente = intval($idCliente);
        $sql = "DELETE FROM Cliente WHERE idCliente=$idCliente";
        return $this->conexion->getConexion()->query($sql);
    }

    /**
     * Cerrar conexión
     */
    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}
?>
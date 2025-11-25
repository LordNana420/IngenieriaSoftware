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
        $sql = "SELECT idCliente, Nombre, Apellido, Telefono, estado
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
                    $fila['Telefono'],
                    $fila["estado"]
                );
            }
        }

        return $clientes;
    }

    public function consultarPorId($idCliente)
    {
        $sql = "SELECT idCliente, Nombre, Apellido, Telefono , estado
                FROM Cliente 
                WHERE idCliente = ?";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        if ($stmt === false) {
            die("Error al preparar la consulta: " . $this->conexion->getConexion()->error);
        }

        $stmt->bind_param("i", $idCliente);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return new Cliente(
                $fila['idCliente'],
                $fila['Nombre'],
                $fila['Apellido'],
                $fila['Telefono'],
                    $fila["estado"]
            );
        }
        return null;
    }

    public function consultarPorParametros($nom, $ape, $doc, $tel)
    {
        $sql = "SELECT idCliente, Nombre, Apellido, Telefono, estado
                FROM Cliente
                WHERE ";
        if (!empty($nom) && $nom !== "0") {
            if (!empty($ape) && $ape !== "0") {
                $sql .= "Nombre LIKE '%" . $nom . "%' AND Apellido LIKE '" . $ape . "%'";
            }else{
                $sql .= "Nombre LIKE '%" . $nom . "%'";
            }
            
        }

        if (!empty($doc) && $doc !== "0") {
            $sql .= "idCliente LIKE '%" . $doc . "%'";
        }
        if (!empty($tel) && $tel !== "0") {
            $sql .= "Telefono LIKE '%" . $tel . "%' ";
        }
        $sql .= " and estado = 1 ";

        $resultado = $this->conexion->getConexion()->query($sql);
        $clientes = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $clientes[] = new Cliente(
                    $fila['idCliente'],
                    $fila['Nombre'],
                    $fila['Apellido'],
                    $fila['Telefono'],
                    $fila["estado"]
                );
            }
        }

        return $clientes;
    }

    public function insertar($cliente)
    {
        $conexion = $this->conexion->getConexion();

        $id = $cliente->getId();
        $nombre = $cliente->getNombre();
        $apellido = $cliente->getApellido();
        $telefono = $cliente->getTelefono();

        $sql = "INSERT INTO cliente (idCliente, Nombre, Apellido, Telefono, Estado) 
            VALUES ('$id', '$nombre', '$apellido', '$telefono', 1)";

        if ($conexion->query($sql)) {
            return [
                'exito' => true,
                'mensaje' => 'Cliente registrado correctamente.'
            ];
        } else {
            return [
                'exito' => false,
                'mensaje' => 'Error al registrar cliente: ' . $conexion->error
            ];
        }
    }
    /**
     * 🔹 Cerrar conexión
     */
    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }

    /**
 * 🔹 Editar cliente existente
 */
public function editar($cliente)
{
    $conexion = $this->conexion->getConexion();

    $id = $cliente->getId();
    $nombre = $cliente->getNombre();
    $apellido = $cliente->getApellido();
    $telefono = $cliente->getTelefono();
    $estado = $cliente->getEstado();

    $sql = "UPDATE Cliente 
            SET Nombre = ?, Apellido = ?, Telefono = ?, Estado = ?
            WHERE idCliente = ?";

    $stmt = $conexion->prepare($sql);
    if ($stmt === false) {
        return [
            'exito' => false,
            'mensaje' => 'Error al preparar la consulta: ' . $conexion->error
        ];
    }

    $stmt->bind_param("sssii", $nombre, $apellido, $telefono, $estado, $id);

    if ($stmt->execute()) {
        return [
            'exito' => true,
            'mensaje' => 'Cliente actualizado correctamente.'
        ];
    } else {
        return [
            'exito' => false,
            'mensaje' => 'Error al actualizar cliente: ' . $stmt->error
        ];
    }
}

public function listarClientes() {
    $clientes = array();
    $sql = "SELECT idCliente, Nombre, Apellido, Telefono, estado FROM cliente";

    $resultado = $this->conexion->getConexion()->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            $cliente = new Cliente(
                $fila["idCliente"],
                $fila["Nombre"],
                $fila["Apellido"],
                $fila["Telefono"],
                $fila["estado"]
            );

            $clientes[] = $cliente;
        }
    }

    return $clientes;
}

public function cambiarEstado($idCliente, $nuevoEstado) {
    $conn = $this->conexion->getConexion();

    if (!$conn) {
        return false;
    }

    $sql = "UPDATE cliente SET estado = ? WHERE idCliente = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        return false;
    }

    $stmt->bind_param("ii", $nuevoEstado, $idCliente);
    $resultado = $stmt->execute();

    $stmt->close();
    return $resultado;
}



}
?>
<?php
require_once __DIR__ . "/Conexion.php";
require_once __DIR__ . "/Movimiento.php";

class MovimientoDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    public function consultarTodos()
    {
        $sql = "SELECT idMovimiento, idProducto, tipo, cantidad, fecha, responsable
                FROM movimientos_inventario
                ORDER BY fecha DESC";

        $resultado = $this->conexion->getConexion()->query($sql);

        $movimientos = [];

        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $movimientos[] = new Movimiento(
                    $fila['idMovimiento'],
                    $fila['idProducto'],
                    $fila['tipo'],
                    $fila['cantidad'],
                    $fila['fecha'],
                    $fila['responsable']
                );
            }
        }

        return $movimientos;
    }

    public function insertar(Movimiento $m)
    {
        $sql = "INSERT INTO movimientos_inventario (idProducto, tipo, cantidad, fecha, responsable)
                VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param(
            "isiss",
            $m->getIdProducto(),
            $m->getTipo(),
            $m->getCantidad(),
            $m->getFecha(),
            $m->getResponsable()
        );

        return $stmt->execute();
    }

    public function consultarPorProducto($idProducto)
    {
        $sql = "SELECT idMovimiento, idProducto, tipo, cantidad, fecha, responsable
                FROM movimientos_inventario
                WHERE idProducto = ?
                ORDER BY fecha DESC";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("i", $idProducto);
        $stmt->execute();

        $resultado = $stmt->get_result();
        $movimientos = [];

        while ($fila = $resultado->fetch_assoc()) {
            $movimientos[] = new Movimiento(
                $fila['idMovimiento'],
                $fila['idProducto'],
                $fila['tipo'],
                $fila['cantidad'],
                $fila['fecha'],
                $fila['responsable']
            );
        }

        return $movimientos;
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM movimientos_inventario WHERE idMovimiento = ?";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}
?>

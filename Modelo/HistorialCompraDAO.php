<?php
require_once 'Conexion.php';
require_once 'HistorialCompraDTO.php';

class HistorialCompraDAO {

    private $conexion;

    public function __construct() {
        $conexionObj = new Conexion();
        $this->conexion = $conexionObj->getConexion();
    }

    public function obtenerHistorialPorCliente($idCliente) {
        $sql = "
            SELECT 
                v.idventa AS idVenta,
                c.Nombre AS clienteNombre,
                c.Apellido AS clienteApellido,
                e.Nombre AS empleadoNombre,
                e.Apellido AS empleadoApellido,
                m.Nombre AS productoNombre,
                m.Cantidad_Mercancia AS cantidad,
                m.Precio_Unitario AS precioUnitario,
                m.Fecha_Ingreso AS fechaIngreso
            FROM venta v
            INNER JOIN Cliente c ON v.Cliente_idCliente = c.idCliente
            INNER JOIN Empleado e ON v.Empleado_idEmpleado = e.idEmpleado
            INNER JOIN Inventario i ON i.venta_idventa = v.idventa
            INNER JOIN Mercancia m ON m.Inventario_idInventario = i.idInventario
            WHERE c.idCliente = :idCliente
            ORDER BY m.Fecha_Ingreso DESC
        ";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bindParam(':idCliente', $idCliente, PDO::PARAM_INT);
        $stmt->execute();

        $historial = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $dto = new HistorialCompras();
            $dto->setIdVenta($row['idVenta']);
            $dto->setClienteNombre($row['clienteNombre']);
            $dto->setClienteApellido($row['clienteApellido']);
            $dto->setEmpleadoNombre($row['empleadoNombre']);
            $dto->setEmpleadoApellido($row['empleadoApellido']);
            $dto->setProductoNombre($row['productoNombre']);
            $dto->setCantidad($row['cantidad']);
            $dto->setPrecioUnitario($row['precioUnitario']);
            $dto->setFechaIngreso($row['fechaIngreso']);
            $historial[] = $dto;
        }

        return $historial;
    }
}
?>

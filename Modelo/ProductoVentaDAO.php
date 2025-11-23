<?php
require_once("Conexion.php");

class ProductoVentaDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    public function existeDetalle($idVenta, $idProducto)
    {
        $sql = "SELECT 1 
                FROM Producto_has_venta
                WHERE Venta_idVenta = $idVenta
                AND Producto_idProducto = $idProducto
                LIMIT 1";

        $res = $this->conexion->getConexion()->query($sql);
        return ($res && $res->num_rows > 0);
    }

    public function sumarDetalle($idVenta, $idProducto, $cantidadNueva, $precioTotalNuevo)
    {
        $sql = "
            UPDATE Producto_has_venta
            SET cantidad = cantidad + $cantidadNueva,
                precio = precio + $precioTotalNuevo
            WHERE Venta_idVenta = $idVenta
            AND Producto_idProducto = $idProducto
        ";

        return $this->conexion->getConexion()->query($sql);
    }

    public function registrarDetalle($idProducto, $idVenta, $cantidad, $precioUnitario, $precioTotal)
    {
        $sql = "INSERT INTO Producto_has_venta
                (Producto_idProducto, Venta_idVenta, cantidad, precio_unitario, precio)
                VALUES ('$idProducto', '$idVenta', '$cantidad', '$precioUnitario', '$precioTotal')";

        return $this->conexion->getConexion()->query($sql);
    }

    public function consultarPorVenta($idVenta)
    {
        $sql = "SELECT 
                    Producto_idProducto, 
                    cantidad, 
                    precio_unitario,
                    precio
                FROM Producto_has_venta 
                WHERE Venta_idVenta = $idVenta";

        return $this->conexion->getConexion()->query($sql);
    }

        public function obtenerDetallesPorVenta($idVenta) {
        $sql = "SELECT pv.Producto_idProducto, pv.cantidad, pv.precio_unitario, pv.precio_total
                FROM producto_venta pv
                WHERE pv.Venta_idventa = '" . $idVenta . "'";

        $resultado = $this->conexion->getConexion()->query($sql);

        $detalles = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $detalles[] = [
                    'Producto_idProducto' => $fila['Producto_idProducto'],
                    'cantidad' => $fila['cantidad'],
                    'precio_unitario' => $fila['precio_unitario'],
                    'precio_total' => $fila['precio_total']
                ];
            }
        }

        return $detalles;
    }
    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}
?>

<?php
require_once("Conexion.php");
require_once("Venta.php");

class VentaDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    public function registrarVenta(Venta $venta)
    {
        $sql = "INSERT INTO venta (Cliente_idCliente, Empleado_idEmpleado, fecha, total)
                VALUES ('" . $venta->getIdCliente() . "',
                        '" . $venta->getIdEmpleado() . "',
                        '" . $venta->getFecha() . "',
                        '" . $venta->getTotal() . "')";

        $this->conexion->getConexion()->query($sql);

        return $this->conexion->getConexion()->insert_id;
    }

    public function consultarTodas()
    {
        $sql = "SELECT idventa, Cliente_idCliente, Empleado_idEmpleado, fecha, total 
                FROM venta
                ORDER BY fecha DESC";

        $resultado = $this->conexion->getConexion()->query($sql);

        $ventas = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $ventas[] = new Venta(
                    $fila['Cliente_idCliente'],
                    $fila['Empleado_idEmpleado'],
                    $fila['fecha'],
                    $fila['total']
                );
            }
        }

        return $ventas;
    }

    public function reporteVentas($tipo, $fechaInicio, $fechaFin)
    {
        $sql = "SELECT idventa, Cliente_idCliente, Empleado_idEmpleado, fecha, total 
                FROM venta
                WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin'
                ORDER BY fecha DESC";

        $resultado = $this->conexion->getConexion()->query($sql);

        $ventas = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $ventas[] = new Venta(
                    $fila['Cliente_idCliente'],
                    $fila['Empleado_idEmpleado'],
                    $fila['fecha'],
                    $fila['total']
                );
            }
        }

        return $ventas;
    }
    
    public function consultarVenta($idVenta) {
        $sql = "SELECT idventa, Cliente_idCliente, Empleado_idEmpleado, fecha, total 
                FROM venta
                WHERE idventa = '" . $idVenta . "'";

        $resultado = $this->conexion->getConexion()->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return new Venta(
                $fila['Cliente_idCliente'],
                $fila['Empleado_idEmpleado'],
                $fila['fecha'],
                $fila['total']
            );
        }

        return null;
    }


    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}
?>

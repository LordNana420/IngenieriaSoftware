<?php
require_once("Conexion.php");
require_once("Producto.php");

class ProductoDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    public function consultarTodos()
    {
        $sql = "
        SELECT 
            p.idProducto,
            m.Nombre AS nombre,
            m.Cantidad_Mercancia AS stock,
            m.Precio_Unitario AS precio
        FROM producto p
        INNER JOIN inventario i 
            ON p.Inventario_idInventario = i.idInventario
        INNER JOIN mercancia m 
            ON m.Inventario_idInventario = i.idInventario
        ORDER BY m.Nombre ASC
    ";

        $resultado = $this->conexion->getConexion()->query($sql);

        $productos = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($row = $resultado->fetch_assoc()) {
                $productos[] = $row;
            }
        }
        return $productos;
    }


    public function actualizarStock($idProducto, $cantidadVendida)
{
    // 1. Obtener el idInventario del producto
    $sql1 = "SELECT Inventario_idInventario FROM producto WHERE idProducto = $idProducto";
    $res = $this->conexion->getConexion()->query($sql1);

    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $idInventario = $row["Inventario_idInventario"];

        // 2. Restar stock en mercancia
        $sql2 = "UPDATE mercancia 
                 SET Cantidad_Mercancia = Cantidad_Mercancia - $cantidadVendida 
                 WHERE Inventario_idInventario = $idInventario";

        return $this->conexion->getConexion()->query($sql2);
    }

    return false;
}


    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}
?>
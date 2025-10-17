<?php
require_once("Conexion.php");
require_once("Mercancia.php");

class MercanciaDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    // Consultar todos los productos
    public function consultarTodos() {
        $sql = "SELECT idMercancia, Nombre, Cantidad_Mercancia, Stock_Minimo 
                FROM Mercancia 
                ORDER BY Nombre ASC";
        $resultado = $this->conexion->getConexion()->query($sql);

        $mercancias = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $mercancias[] = new Mercancia(
                    $fila['idMercancia'],
                    $fila['Nombre'],
                    $fila['Cantidad_Mercancia'],
                    $fila['Stock_Minimo']
                );
            }
        }
        return $mercancias;
    }

    // Consultar insumos con stock bajo
    public function consultarStockBajo() {
        $sql = "SELECT idMercancia, Nombre, Cantidad_Mercancia, Stock_Minimo 
                FROM Mercancia 
                WHERE Cantidad_Mercancia <= Stock_Minimo
                ORDER BY Cantidad_Mercancia ASC";
        $resultado = $this->conexion->getConexion()->query($sql);

        $alertas = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $alertas[] = new Mercancia(
                    $fila['idMercancia'],
                    $fila['Nombre'],
                    $fila['Cantidad_Mercancia'],
                    $fila['Stock_Minimo']
                );
            }
        }
        return $alertas;
    }

    public function cerrarConexion() {
        $this->conexion->cerrar();
    }
}
?>

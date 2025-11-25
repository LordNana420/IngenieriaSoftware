<?php
require_once("Conexion.php");
require_once("Mercancia.php");
require_once("Movimiento.php");

class MercanciaDAO
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    // Consultar todos los productos
    public function consultarTodos()
    {
        $sql = "SELECT idMercancia, Nombre, Cantidad_Mercancia, Stock_Minimo, Stock_Maximo, Precio_Unitario, Fecha_Ingreso, Fecha_vencimiento 
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
                    $fila['Stock_Minimo'],
                    $fila['Stock_Maximo'],
                    '', // causa
                    $fila['Fecha_Ingreso'],
                    $fila['Fecha_vencimiento'],
                    $fila['Precio_Unitario']
                );
            }
        }
        return $mercancias;
    }

    // Consultar insumos con stock bajo o alto
    public function consultarStock()
    {
        $alertas = [];

        // Stock bajo
        $sql = "SELECT idMercancia, Nombre, Cantidad_Mercancia, Stock_Minimo 
                FROM Mercancia 
                WHERE Cantidad_Mercancia <= Stock_Minimo
                ORDER BY Cantidad_Mercancia ASC";
        $resultado = $this->conexion->getConexion()->query($sql);
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $alertas[] = new Mercancia(
                    $fila['idMercancia'],
                    $fila['Nombre'],
                    $fila['Cantidad_Mercancia'],
                    $fila['Stock_Minimo'],
                    'Stock muy bajo'
                );
            }
        }

        // Stock alto
        $sql = "SELECT idMercancia, Nombre, Cantidad_Mercancia, Stock_Maximo
                FROM Mercancia 
                WHERE Cantidad_Mercancia > Stock_Maximo
                ORDER BY Cantidad_Mercancia ASC";
        $resultado = $this->conexion->getConexion()->query($sql);
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $alertas[] = new Mercancia(
                    $fila['idMercancia'],
                    $fila['Nombre'],
                    $fila['Cantidad_Mercancia'],
                    $fila['Stock_Maximo'],
                    'Stock límite superado'
                );
            }
        }

        return $alertas;
    }

    // Registrar nuevo insumo y movimiento de entrada
    public function registrarInsumo(Mercancia $m, $responsable)
    {
        // Preparar datos en variables
        $nombre       = $m->getNombre();
        $fechaVenc    = $m->getFechaVencimiento();
        $fechaIngreso = $m->getFechaIngreso();
        $cantidad     = $m->getCantidad();
        $precioUnit   = $m->getPrecioUnitario();
        $estadoId     = $m->getEstadoId();
        $tipoId       = $m->getTipoId();
        $inventarioId = $m->getInventarioId();
        $stockMin     = $m->getStockMinimo();
        $stockMax     = $m->getStockMaximo();

        // Insertar mercancia
        $sql = "INSERT INTO mercancia
            (Nombre, Fecha_vencimiento, Fecha_Ingreso, Cantidad_Mercancia, Precio_Unitario, Estado_Mercancia_idEstado_Mercancia, Tipo_idEstado_Tipo, Inventario_idInventario, Stock_Minimo, Stock_Maximo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param(
            "sssiiiiiii",
            $nombre,
            $fechaVenc,
            $fechaIngreso,
            $cantidad,
            $precioUnit,
            $estadoId,
            $tipoId,
            $inventarioId,
            $stockMin,
            $stockMax
        );

        $resultado = $stmt->execute();

        if ($resultado) {
            $idProducto = $this->conexion->getConexion()->insert_id;

            // Insertar movimiento de entrada
            $tipoMovimiento   = 'entrada';
            $fechaMovimiento  = date('Y-m-d H:i:s');
            $stmt2 = $this->conexion->getConexion()->prepare(
                "INSERT INTO movimientos_inventario (idProducto, tipo, cantidad, fecha, responsable)
                 VALUES (?, ?, ?, ?, ?)"
            );
            $stmt2->bind_param("isiss", $idProducto, $tipoMovimiento, $cantidad, $fechaMovimiento, $responsable);
            $stmt2->execute();
        }

        return $resultado;
    }

    public function deshabilitarMercancia($id)
    {
        $sql = "UPDATE mercancia 
                SET Estado_Mercancia_idEstado_Mercancia = 
                    (SELECT idEstado_Mercancia FROM estado_mercancia WHERE Valor = 'Deshabilitado')
                WHERE idMercancia = ?";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function habilitarMercancia($id)
    {
        $sql = "UPDATE mercancia 
                SET Estado_Mercancia_idEstado_Mercancia = 
                    (SELECT idEstado_Mercancia FROM estado_mercancia WHERE Valor = 'Activo')
                WHERE idMercancia = ?";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }

    public function obtenerMercanciasActivas()
    {
        $sql = "SELECT m.*, 
                   em.Valor AS Estado, 
                   t.Valor AS Tipo, 
                   i.Inventariocol AS Ubicacion
            FROM mercancia m
            INNER JOIN estado_mercancia em 
                ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
            INNER JOIN tipo t 
                ON m.Tipo_idEstado_Tipo = t.idTipo_Mercancia
            INNER JOIN inventario i 
                ON m.Inventario_idInventario = i.idInventario
            WHERE em.Valor != 'Deshabilitado'
            ORDER BY m.Nombre ASC";

        $this->conexion->ejecutar($sql);

        $datos = [];
        while ($fila = $this->conexion->registro()) {
            $datos[] = $fila;
        }

        return $datos;
    }

    public function obtenerMercanciasDeshabilitadas()
    {
        $sql = "SELECT m.*, 
                   em.Valor AS Estado, 
                   t.Valor AS Tipo, 
                   i.Inventariocol AS Ubicacion
            FROM mercancia m
            INNER JOIN estado_mercancia em 
                ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
            INNER JOIN tipo t 
                ON m.Tipo_idEstado_Tipo = t.idTipo_Mercancia
            INNER JOIN inventario i 
                ON m.Inventario_idInventario = i.idInventario
            WHERE em.Valor = 'Deshabilitado'
            ORDER BY m.Nombre ASC";

        $this->conexion->ejecutar($sql);

        $datos = [];
        while ($fila = $this->conexion->registro()) {
            $datos[] = $fila;
        }

        return $datos;
    }   

    public function obtenerDetallesMercancia($id)
    {
        $sql = "SELECT m.*, 
                   em.Valor AS Estado, 
                   t.Valor AS Tipo, 
                   i.Inventariocol AS Ubicacion
            FROM mercancia m
            INNER JOIN estado_mercancia em 
                ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
            INNER JOIN tipo t 
                ON m.Tipo_idEstado_Tipo = t.idTipo_Mercancia
            INNER JOIN inventario i 
                ON m.Inventario_idInventario = i.idInventario
            WHERE m.idMercancia = ?";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }


    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}
?>
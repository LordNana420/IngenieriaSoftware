<?php
require_once("Conexion.php");
require_once("Mercancia.php");

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
    public function consultarStock()
    {
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
                    $fila['Stock_Minimo'],
                    'Stock muy bajo'


                );
            }
        }
        $sql = "SELECT idMercancia, Nombre, Cantidad_Mercancia, Stock_maximo
                FROM Mercancia 
                WHERE Cantidad_Mercancia > stock_maximo
                ORDER BY Cantidad_Mercancia ASC";
        $resultado = $this->conexion->getConexion()->query($sql);
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $alertas[] = new Mercancia(
                    $fila['idMercancia'],
                    $fila['Nombre'],
                    $fila['Cantidad_Mercancia'],
                    $fila['Stock_maximo'],
                    'Stock limite superado'
                );
            }
        }

        return $alertas;
    }

    public function registrarInsumo(Mercancia $mercancia, $responsable)
    {
        // Capturar campos del objeto Mercancia
        $nombre      = $mercancia->getNombre();
        $cantidad    = $mercancia->getCantidad();
        $stockMinimo = $mercancia->getStockMinimo();
        $causa       = $mercancia->getCausa();

        // Sentencia SQL
        $sql = "
            INSERT INTO mercancia (nombre, cantidad, stock_minimo, causa, responsable)
            VALUES ('$nombre', $cantidad, $stockMinimo, '$causa', '$responsable')
        ";

        // Ejecutar consulta
        $this->conexion->ejecutar($sql);

        // Verificar si se insertó
        if ($this->conexion->getConexion()->affected_rows > 0) {
            return true;
        }

        return false;
    }

   

    private function obtenerEstadoDeshabilitadoId()
    {
        $sql = "SELECT idEstado_Mercancia 
            FROM Estado_Mercancia 
            WHERE Valor = 'Deshabilitado' 
            LIMIT 1";

        $resultado = $this->conexion->getConexion()->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return $fila['idEstado_Mercancia'];
        }

        // Si no existe, lo crea
        $sqlInsert = "INSERT INTO Estado_Mercancia (Valor) VALUES ('Deshabilitado')";
        if ($this->conexion->getConexion()->query($sqlInsert)) {
            return $this->conexion->getConexion()->insert_id;
        }

        return null;
    }

    private function mercanciaEstaActiva($idMercancia)
    {
        $sql = "
        SELECT m.idMercancia
        FROM Mercancia m
        INNER JOIN Estado_Mercancia em 
            ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
        WHERE m.idMercancia = $idMercancia
          AND em.Valor != 'Deshabilitado'
        LIMIT 1
    ";

        $resultado = $this->conexion->getConexion()->query($sql);

        return ($resultado && $resultado->num_rows > 0);
    }

    public function deshabilitarMercancia($idMercancia, $motivo = "")
    {

        // Validar si está activa
        if (!$this->mercanciaEstaActiva($idMercancia)) {
            return false;
        }

        // Obtener estado "Deshabilitado"
        $estadoId = $this->obtenerEstadoDeshabilitadoId();
        if ($estadoId === null) {
            return false;
        }

        $sql = "
        UPDATE Mercancia
        SET Estado_Mercancia_idEstado_Mercancia = $estadoId
        WHERE idMercancia = $idMercancia
    ";

        return $this->conexion->getConexion()->query($sql);
    }

    private function mercanciaEstaDeshabilitada($idMercancia)
    {
        $sql = "
        SELECT m.idMercancia
        FROM Mercancia m
        INNER JOIN Estado_Mercancia em 
            ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
        WHERE m.idMercancia = $idMercancia
          AND em.Valor = 'Deshabilitado'
        LIMIT 1
    ";

        $resultado = $this->conexion->getConexion()->query($sql);

        return ($resultado && $resultado->num_rows > 0);
    }

    public function habilitarMercancia($idMercancia)
    {

        if (!$this->mercanciaEstaDeshabilitada($idMercancia)) {
            return false;
        }

        // Obtener estado "Disponible"
        $sql = "
        SELECT idEstado_Mercancia 
        FROM Estado_Mercancia 
        WHERE Valor = 'Disponible' 
        LIMIT 1
    ";
        $resultado = $this->conexion->getConexion()->query($sql);
        $fila = $resultado->fetch_assoc();
        $idEstadoDisponible = $fila['idEstado_Mercancia'];

        // Actualizar
        $sqlUpdate = "
        UPDATE Mercancia
        SET Estado_Mercancia_idEstado_Mercancia = $idEstadoDisponible
        WHERE idMercancia = $idMercancia
    ";

        return $this->conexion->getConexion()->query($sqlUpdate);
    }

    public function obtenerMercanciasActivas()
    {
        $sql = "
        SELECT m.*, em.Valor AS Estado
        FROM Mercancia m
        INNER JOIN Estado_Mercancia em 
            ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
        WHERE em.Valor != 'Deshabilitado'
        ORDER BY m.Nombre ASC
    ";

        $resultado = $this->conexion->getConexion()->query($sql);

        $lista = [];
        while ($fila = $resultado->fetch_assoc()) {
            $lista[] = $fila;
        }
        return $lista;
    }

    public function obtenerMercanciasDeshabilitadas()
    {
        $sql = "
        SELECT m.*, em.Valor AS Estado
        FROM Mercancia m
        INNER JOIN Estado_Mercancia em 
            ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
        WHERE em.Valor = 'Deshabilitado'
        ORDER BY m.Nombre ASC
    ";

        $resultado = $this->conexion->getConexion()->query($sql);

        $lista = [];
        while ($fila = $resultado->fetch_assoc()) {
            $lista[] = $fila;
        }
        return $lista;
    }

    public function obtenerDetallesMercancia($id)
    {
        $id = $this->conexion->getConexion()->real_escape_string($id);

        $sql = "SELECT * FROM mercancia WHERE idMercancia = $id LIMIT 1";
        $this->conexion->ejecutar($sql);

        return $this->conexion->registro();
    }


    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}

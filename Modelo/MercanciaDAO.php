<?php
require_once __DIR__ . '/Conexion.php';
require_once __DIR__ . '/Mercancia.php';

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
                FROM `mercancia`
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
                FROM `mercancia`
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
                FROM `mercancia`
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

    public function registrarInsumo(Mercancia $m, $precio)
    {
        $this->conexion->abrir();
        $conn = $this->conexion->getConexion();

        // validar/normalizar inventario FK: usar el id pasado o intentar obtener uno existente
        $inventario_id = (int)($m->getInventarioId() ?? 0);
        if ($inventario_id <= 0) {
            $res = $conn->query("SELECT idInventario FROM inventario LIMIT 1");
            if ($res && $row = $res->fetch_assoc()) {
                $inventario_id = (int)$row['idInventario'];
            } else {
                // Intentar crear un inventario placeholder automáticamente
                $created = $conn->query("INSERT INTO inventario () VALUES ()");
                if ($created) {
                    $inventario_id = (int)$conn->insert_id;
                } else {
                    // No se pudo crear: dar error claro para que el desarrollador lo solucione
                    throw new Exception(
                        "No existe registro en tabla 'inventario' y no se pudo crear uno automáticamente. " .
                        "Por favor cree manualmente un inventario o pegue aquí la salida de: DESCRIBE inventario; " .
                        "Error SQL: " . $conn->error
                    );
                }
            }
        } else {
            // comprobar que el id proporcionado existe
            $stmtChk = $conn->prepare("SELECT 1 FROM inventario WHERE idInventario = ? LIMIT 1");
            $stmtChk->bind_param("i", $inventario_id);
            $stmtChk->execute();
            $resChk = $stmtChk->get_result();
            if (!$resChk || $resChk->num_rows === 0) {
                // intentar recuperar cualquier id existente
                $res = $conn->query("SELECT idInventario FROM inventario LIMIT 1");
                if ($res && $row = $res->fetch_assoc()) {
                    $inventario_id = (int)$row['idInventario'];
                } else {
                    throw new Exception("Inventario_idInventario proporcionado no existe y no hay inventarios en la BD.");
                }
            }
            $stmtChk->close();
        }

        // Ajustado a la estructura de tabla provista
        $sql = "INSERT INTO `mercancia`
             (Nombre, Cantidad_Mercancia, Stock_Minimo, Stock_Maximo, Fecha_Ingreso, Fecha_vencimiento, Precio_Unitario, Estado_Mercancia_idEstado_Mercancia, Tipo_idEstado_Tipo, Inventario_idInventario)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en prepare: " . $conn->error);
        }

        // Obtener valores desde el objeto Mercancia
        $nombre = $m->getNombre();
        $cantidad = (int)$m->getCantidad();
        $stock_minimo = (int)$m->getStockMinimo();
        $stock_maximo = (int)($m->getStockMaximo() ?? 0);
        $fecha_ingreso = $m->getFechaIngreso() ?? date('Y-m-d');
        $fecha_vencimiento = $m->getFechaVencimiento() ?? null;
        $estado_id = (int)($m->getEstadoId() ?? 1);
        $tipo_id = (int)($m->getTipoId() ?? 1);

        // types: s i i i s s d i i i
        $stmt->bind_param(
            "siiissdiii",
            $nombre,
            $cantidad,
            $stock_minimo,
            $stock_maximo,
            $fecha_ingreso,
            $fecha_vencimiento,
            $precio,
            $estado_id,
            $tipo_id,
            $inventario_id
        );

        if (!$stmt->execute()) {
            // registrar error y lanzar con mensaje claro
            $err = $stmt->error;
            $stmt->close();
            $this->conexion->cerrar();
            throw new Exception("Error al insertar mercancia: " . $err);
        }

        $stmt->close();
        $this->conexion->cerrar();

        return true;
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
        FROM `mercancia` m
        INNER JOIN Estado_Mercancia em 
            ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
        WHERE m.idMercancia = $idMercancia
          AND em.Valor != 'Deshabilitado'
        LIMIT 1
    ";

        $resultado = $this->conexion->getConexion()->query($sql);

        return ($resultado && $resultado->num_rows > 0);
    }

    public function deshabilitarMercancia($id)
    {
        $sql = "UPDATE mercancia SET Estado_Mercancia_idEstado_Mercancia = 2 WHERE idMercancia = ?";

        $stmt = $this->conexion->getConexion()->prepare($sql);
        if (!$stmt) {
            die("Error en prepare: " . $this->conexion->getConexion()->error);
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }



    private function mercanciaEstaDeshabilitada($idMercancia)
    {
        $sql = "
        SELECT m.idMercancia
        FROM `mercancia` m
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
        UPDATE `mercancia`
         SET Estado_Mercancia_idEstado_Mercancia = $idEstadoDisponible
         WHERE idMercancia = $idMercancia
     ";

        return $this->conexion->getConexion()->query($sqlUpdate);
    }

    public function obtenerMercanciasActivas()
    {
        $sql = "
        SELECT m.*, em.Valor AS Estado
        FROM `mercancia` m
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
        FROM `mercancia` m
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

        $sql = "SELECT * FROM `mercancia` WHERE idMercancia = $id LIMIT 1";
        $this->conexion->ejecutar($sql);

        return $this->conexion->registro();
    }


    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}

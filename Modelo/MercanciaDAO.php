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
        $sql = "SELECT idMercancia, Nombre, Precio_Unitario, Cantidad_Mercancia, Stock_Minimo, Stock_Maximo, Fecha_Ingreso, Fecha_vencimiento, Estado_Mercancia_idEstado_Mercancia, Tipo_idEstado_Tipo, Inventario_idInventario
                FROM `mercancia`
                ORDER BY Nombre ASC";

        $resultado = $this->conexion->getConexion()->query($sql);

        $mercancias = [];
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                // mapear columnas de la BD a claves que espera la vista
                $mercancias[] = [
                    'id' => $fila['idMercancia'],
                    'nombre' => $fila['Nombre'],
                    // No hay columna Descripcion en la tabla; dejar vacío
                    'descripcion' => '',
                    'precio' => $fila['Precio_Unitario'] ?? 0,
                    'cantidad' => $fila['Cantidad_Mercancia'] ?? 0,
                    'stock_minimo' => $fila['Stock_Minimo'] ?? 0,
                    'stock_maximo' => $fila['Stock_Maximo'] ?? 0,
                    'fecha_ingreso' => $fila['Fecha_Ingreso'] ?? null,
                    'fecha_vencimiento' => $fila['Fecha_vencimiento'] ?? null,
                    'estado_id' => $fila['Estado_Mercancia_idEstado_Mercancia'] ?? null,
                    'tipo_id' => $fila['Tipo_idEstado_Tipo'] ?? null,
                    'inventario_id' => $fila['Inventario_idInventario'] ?? null
                ];
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
                // Crear un inventario placeholder con valores por defecto (ajusta si la tabla requiere otros campos)
                // calcular un id explícito porque idInventario no es AUTO_INCREMENT
                $resMax = $conn->query("SELECT MAX(idInventario) AS mx FROM inventario");
                $mx = 0;
                if ($resMax && $rowMax = $resMax->fetch_assoc()) {
                    $mx = (int)$rowMax['mx'];
                }
                $newId = $mx + 1;
                $created = $conn->query("INSERT INTO inventario (idInventario, Cantidad_Total, precio_unitario) VALUES ($newId, 0, 0)");
                if ($created) {
                    $inventario_id = $newId;
                } else {
                    throw new Exception("No se pudo crear inventario placeholder. Error SQL: " . $conn->error);
                }
            }
        } else {
            // comprobar que el id proporcionado existe
            $stmtChk = $conn->prepare("SELECT 1 FROM inventario WHERE idInventario = ? LIMIT 1");
            $stmtChk->bind_param("i", $inventario_id);
            $stmtChk->execute();
            $resChk = $stmtChk->get_result();
            if (!$resChk || $resChk->num_rows === 0) {
                // Si el id no existe, intentar recuperar cualquier id existente
                $res = $conn->query("SELECT idInventario FROM inventario LIMIT 1");
                if ($res && $row = $res->fetch_assoc()) {
                    $inventario_id = (int)$row['idInventario'];
                } else {
                    // Si no hay inventarios, crear uno placeholder con id explícito
                    // calcular MAX(idInventario)+1 porque idInventario no es AUTO_INCREMENT
                    $resMax2 = $conn->query("SELECT MAX(idInventario) AS mx FROM inventario");
                    $mx2 = 0;
                    if ($resMax2 && $rowMax2 = $resMax2->fetch_assoc()) {
                        $mx2 = (int)$rowMax2['mx'];
                    }
                    $newId2 = $mx2 + 1;
                    $created = $conn->query("INSERT INTO inventario (idInventario, Cantidad_Total, precio_unitario) VALUES ($newId2, 0, 0)");
                    if ($created) {
                        $inventario_id = $newId2;
                    } else {
                        // No se pudo crear: devolver error claro con SQL error
                        $stmtChk->close();
                        throw new Exception("No existe inventario y no se pudo crear uno automático. Error SQL: " . $conn->error);
                    }
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
            FROM estado_mercancia 
            WHERE Valor = 'Deshabilitado' 
            LIMIT 1";

        $resultado = $this->conexion->getConexion()->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $fila = $resultado->fetch_assoc();
            return $fila['idEstado_Mercancia'];
        }

        // Si no existe, lo crea
        $sqlInsert = "INSERT INTO estado_mercancia (Valor) VALUES ('Deshabilitado')";
        if ($this->conexion->getConexion()->query($sqlInsert)) {
            return $this->conexion->getConexion()->insert_id;
        }

        return null;
    }


    public function deshabilitarMercancia($id)
    {
        $id = (int)$id;
        if ($id <= 0) return false;

        // obtener id del estado "Deshabilitado"
        $idEstado = $this->obtenerEstadoDeshabilitadoId();
        if (empty($idEstado)) {
            // si no existe el estado, no se puede deshabilitar
            return false;
        }

        $sql = "UPDATE `mercancia` SET Estado_Mercancia_idEstado_Mercancia = ? WHERE idMercancia = ?";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        if (!$stmt) {
            // para debugging temporal lanzar excepción
            throw new Exception("Error en prepare (deshabilitar): " . $this->conexion->getConexion()->error);
        }
        $stmt->bind_param("ii", $idEstado, $id);
        $ok = $stmt->execute();
        $stmt->close();
        return (bool)$ok;
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
        // La consulta SQL es correcta para filtrar por estado 'Activo'
        $sql = "
        SELECT m.*, em.Valor AS Estado
        FROM `mercancia` m
        INNER JOIN `estado_mercancia` em 
            ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
        WHERE em.Valor = 'Activo'
        ORDER BY m.Nombre ASC
    ";

        $resultado = $this->conexion->getConexion()->query($sql);

        $lista = []; // Inicializamos el array donde se guardarán los resultados mapeados

        // Verificamos que la consulta haya tenido éxito y haya filas
        if ($resultado && $resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                // Mapear columnas de la BD al formato esperado por la vista
                $lista[] = [
                    'id' => $fila['idMercancia'],
                    'nombre' => $fila['Nombre'],
                    // Añadimos el nuevo campo 'estado' con el valor de la tabla 'estado_mercancia'
                    'estado' => $fila['Estado'],
                    // No hay columna Descripcion en la tabla; dejar vacío
                    'descripcion' => '',
                    'precio' => $fila['Precio_Unitario'] ?? 0,
                    'cantidad' => $fila['Cantidad_Mercancia'] ?? 0,
                    'stock_minimo' => $fila['Stock_Minimo'] ?? 0,
                    'stock_maximo' => $fila['Stock_Maximo'] ?? 0,
                    'fecha_ingreso' => $fila['Fecha_Ingreso'] ?? null,
                    'fecha_vencimiento' => $fila['Fecha_vencimiento'] ?? null,
                    'estado_id' => $fila['Estado_Mercancia_idEstado_Mercancia'] ?? null,
                    'tipo_id' => $fila['Tipo_idEstado_Tipo'] ?? null,
                    'inventario_id' => $fila['Inventario_idInventario'] ?? null
                ];
            }
        }

        return $lista;
    }



    public function obtenerMercanciasDeshabilitadas()
    {
        $sql = "
        SELECT m.*, em.Valor AS Estado
        FROM `mercancia` m
        INNER JOIN `estado_mercancia` em 
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
        $id = (int)$id;

        $sql = "SELECT * FROM mercancia WHERE idMercancia = $id LIMIT 1";
        $this->conexion->ejecutar($sql);

        return $this->conexion->registro();
    }



    // Actualizar insumo (update)
    public function actualizarInsumo(Mercancia $m, $precio)
    {
        $this->conexion->abrir();
        $conn = $this->conexion->getConexion();

        // obtener id de mercancia de forma segura (soporta objeto Mercancia o array)
        $id = null;
        if (is_object($m)) {
            if (method_exists($m, 'getIdMercancia')) {
                $id = $m->getId();
            } elseif (method_exists($m, 'getId')) {
                $id = $m->getId();
            } else {
                // intento final: castear a array para leer propiedades públicas (stdClass u objetos dinámicos)
                $arr = (array)$m;
                $id = $arr['id'] ?? $arr['idMercancia'] ?? null;
            }
        } elseif (is_array($m)) {
            $id = $m['id'] ?? $m['idMercancia'] ?? null;
        }
        $id = (int)$id;
        if ($id <= 0) {
            throw new Exception("ID de mercancia inválido para actualizar.");
        }

        $sql = "UPDATE `mercancia` SET
                    Nombre = ?,
                    Cantidad_Mercancia = ?,
                    Stock_Minimo = ?,
                    Stock_Maximo = ?,
                    Fecha_Ingreso = ?,
                    Fecha_vencimiento = ?,
                    Precio_Unitario = ?,
                    Estado_Mercancia_idEstado_Mercancia = ?,
                    Tipo_idEstado_Tipo = ?,
                    Inventario_idInventario = ?
                WHERE idMercancia = ?";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en prepare (update): " . $conn->error);
        }

        // obtener valores desde el objeto Mercancia (usar getters si existen, o array keys)
        // helper inline para obtener valor seguro sin acceder a propiedades privadas
        $get = function ($objOrArr, $methodsOrKeys, $default = null) {
            // intentar métodos en objeto
            if (is_object($objOrArr)) {
                foreach ((array)$methodsOrKeys as $mth) {
                    if (method_exists($objOrArr, $mth)) return $objOrArr->{$mth}();
                }
            }
            // intentar claves en array
            if (is_array($objOrArr)) {
                foreach ((array)$methodsOrKeys as $k) {
                    if (array_key_exists($k, $objOrArr)) return $objOrArr[$k];
                }
            }
            return $default;
        };

        $nombre = (string)$get($m, ['getNombre', 'nombre', 'Nombre'], '');
        $cantidad = (int)$get($m, ['getCantidad', 'cantidad', 'Cantidad_Mercancia'], 0);
        $stock_minimo = (int)$get($m, ['getStockMinimo', 'stock_minimo', 'Stock_Minimo'], 0);
        $stock_maximo = (int)$get($m, ['getStockMaximo', 'stock_maximo', 'Stock_Maximo'], 0);
        $fecha_ingreso = $get($m, ['getFechaIngreso', 'fecha_ingreso', 'Fecha_Ingreso'], date('Y-m-d'));
        $fecha_vencimiento = $get($m, ['getFechaVencimiento', 'fecha_vencimiento', 'Fecha_vencimiento'], null);
        $estado_id = (int)$get($m, ['getEstadoId', 'estado_id', 'Estado_Mercancia_idEstado_Mercancia'], 1);
        $tipo_id = (int)$get($m, ['getTipoId', 'tipo_id', 'Tipo_idEstado_Tipo'], 1);
        $inventario_id = (int)$get($m, ['getInventarioId', 'inventario_id', 'Inventario_idInventario'], 1);

        // tipos: s i i i s s d i i i i (ultimo i = id)
        $stmt->bind_param(
            "siiissdiiii",
            $nombre,
            $cantidad,
            $stock_minimo,
            $stock_maximo,
            $fecha_ingreso,
            $fecha_vencimiento,
            $precio,
            $estado_id,
            $tipo_id,
            $inventario_id,
            $id
        );

        if (!$stmt->execute()) {
            $err = $stmt->error;
            $stmt->close();
            $this->conexion->cerrar();
            throw new Exception("Error al actualizar mercancia: " . $err);
        }

        $stmt->close();
        $this->conexion->cerrar();
        return true;
    }

    // Eliminar mercancia por id
    public function eliminarMercancia($id)
    {
        $id = (int)$id;
        if ($id <= 0) return false;
        $sql = "DELETE FROM `mercancia` WHERE idMercancia = ?";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        if (!$stmt) {
            throw new Exception("Error en prepare (delete): " . $this->conexion->getConexion()->error);
        }
        $stmt->bind_param("i", $id);
        $ok = $stmt->execute();
        $stmt->close();
        return $ok;
    }

    public function cerrarConexion()
    {
        $this->conexion->cerrar();
    }
}

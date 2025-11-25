<?php
/**
 * Clase Deshabilitar
 * Maneja la deshabilitación de mercancías en el inventario
 * Arquitectura MVC - Modelo
 */

class Deshabilitar {
    
    private $conexion;
    private $tabla_mercancia = "Mercancia";
    private $tabla_estado = "Estado_Mercancia";
    
    // Propiedades de la mercancía
    public $idMercancia;
    public $motivo_deshabilitacion;
    public $fecha_deshabilitacion;
    
    /**
     * Constructor - Recibe la conexión a la base de datos
     */
    public function __construct($db) {
        $this->conexion = $db;
    }
    
    /**
     * Verifica si existe el estado "Deshabilitado" en la tabla Estado_Mercancia
     * Si no existe, lo crea
     * @return int ID del estado deshabilitado
     */
    private function verificarEstadoDeshabilitado() {
        $query = "SELECT idEstado_Mercancia FROM " . $this->tabla_estado . " 
                  WHERE Valor = 'Deshabilitado' LIMIT 1";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['idEstado_Mercancia'];
        } else {
            // Crear el estado si no existe
            $query_insert = "INSERT INTO " . $this->tabla_estado . " (Valor) 
                            VALUES ('Deshabilitado')";
            $stmt_insert = $this->conexion->prepare($query_insert);
            
            if ($stmt_insert->execute()) {
                return $this->conexion->lastInsertId();
            }
        }
        
        return null;
    }
    
    /**
     * Deshabilita una mercancía específica
     * @return bool True si se deshabilitó correctamente, False en caso contrario
     */
    public function deshabilitarMercancia() {
        
        // Verificar que la mercancía existe y no está ya deshabilitada
        if (!$this->verificarMercanciaActiva()) {
            return false;
        }
        
        // Obtener o crear el ID del estado "Deshabilitado"
        $idEstadoDeshabilitado = $this->verificarEstadoDeshabilitado();
        
        if ($idEstadoDeshabilitado === null) {
            return false;
        }
        
        // Actualizar el estado de la mercancía
        $query = "UPDATE " . $this->tabla_mercancia . "
                  SET Estado_Mercancia_idEstado_Mercancia = :estado_deshabilitado
                  WHERE idMercancia = :id_mercancia";
        
        $stmt = $this->conexion->prepare($query);
        
        // Vincular parámetros
        $stmt->bindParam(':estado_deshabilitado', $idEstadoDeshabilitado);
        $stmt->bindParam(':id_mercancia', $this->idMercancia);
        
        // Ejecutar query
        if ($stmt->execute()) {
            // Registrar log de deshabilitación (opcional)
            $this->registrarLogDeshabilitacion($idEstadoDeshabilitado);
            return true;
        }
        
        return false;
    }
    
    /**
     * Verifica si la mercancía existe y está activa (no deshabilitada)
     * @return bool
     */
    private function verificarMercanciaActiva() {
        $query = "SELECT m.idMercancia, m.Nombre, em.Valor as Estado
                  FROM " . $this->tabla_mercancia . " m
                  INNER JOIN " . $this->tabla_estado . " em 
                  ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
                  WHERE m.idMercancia = :id_mercancia
                  AND em.Valor != 'Deshabilitado'
                  LIMIT 1";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id_mercancia', $this->idMercancia);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Habilita nuevamente una mercancía deshabilitada
     * La devuelve al estado "Disponible"
     * @return bool
     */
    public function habilitarMercancia() {
        
        // Verificar que la mercancía está deshabilitada
        if (!$this->verificarMercanciaDeshabilitada()) {
            return false;
        }
        
        // Obtener el ID del estado "Disponible"
        $query = "SELECT idEstado_Mercancia FROM " . $this->tabla_estado . " 
                  WHERE Valor = 'Disponible' LIMIT 1";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $idEstadoDisponible = $row['idEstado_Mercancia'];
        
        // Actualizar el estado de la mercancía
        $query_update = "UPDATE " . $this->tabla_mercancia . "
                        SET Estado_Mercancia_idEstado_Mercancia = :estado_disponible
                        WHERE idMercancia = :id_mercancia";
        
        $stmt_update = $this->conexion->prepare($query_update);
        $stmt_update->bindParam(':estado_disponible', $idEstadoDisponible);
        $stmt_update->bindParam(':id_mercancia', $this->idMercancia);
        
        return $stmt_update->execute();
    }
    
    /**
     * Verifica si la mercancía está deshabilitada
     * @return bool
     */
    private function verificarMercanciaDeshabilitada() {
        $query = "SELECT m.idMercancia
                  FROM " . $this->tabla_mercancia . " m
                  INNER JOIN " . $this->tabla_estado . " em 
                  ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
                  WHERE m.idMercancia = :id_mercancia
                  AND em.Valor = 'Deshabilitado'
                  LIMIT 1";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id_mercancia', $this->idMercancia);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Obtiene todas las mercancías activas (excluye deshabilitadas)
     * @return array
     */
    public function obtenerMercanciasActivas() {
        $query = "SELECT m.*, em.Valor as Estado, t.Valor as Tipo, 
                         i.Inventariocol as Ubicacion
                  FROM " . $this->tabla_mercancia . " m
                  INNER JOIN Estado_Mercancia em 
                  ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
                  INNER JOIN Tipo t ON m.Tipo_idEstado_Tipo = t.idTipo_Mercancia
                  INNER JOIN Inventario i ON m.Inventario_idInventario = i.idInventario
                  WHERE em.Valor != 'Deshabilitado'
                  ORDER BY m.Nombre ASC";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene todas las mercancías deshabilitadas
     * @return array
     */
    public function obtenerMercanciasDeshabilitadas() {
        $query = "SELECT m.*, em.Valor as Estado, t.Valor as Tipo,
                         i.Inventariocol as Ubicacion
                  FROM " . $this->tabla_mercancia . " m
                  INNER JOIN Estado_Mercancia em 
                  ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
                  INNER JOIN Tipo t ON m.Tipo_idEstado_Tipo = t.idTipo_Mercancia
                  INNER JOIN Inventario i ON m.Inventario_idInventario = i.idInventario
                  WHERE em.Valor = 'Deshabilitado'
                  ORDER BY m.Nombre ASC";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene información detallada de una mercancía específica
     * @return array|false
     */
    public function obtenerDetallesMercancia() {
        $query = "SELECT m.*, em.Valor as Estado, t.Valor as Tipo,
                         i.Inventariocol as Ubicacion, i.Cantidad_Total
                  FROM " . $this->tabla_mercancia . " m
                  INNER JOIN Estado_Mercancia em 
                  ON m.Estado_Mercancia_idEstado_Mercancia = em.idEstado_Mercancia
                  INNER JOIN Tipo t ON m.Tipo_idEstado_Tipo = t.idTipo_Mercancia
                  INNER JOIN Inventario i ON m.Inventario_idInventario = i.idInventario
                  WHERE m.idMercancia = :id_mercancia
                  LIMIT 1";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id_mercancia', $this->idMercancia);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        return false;
    }
    
    /**
     * Deshabilita múltiples mercancías en lote
     * @param array $ids_mercancias Array de IDs de mercancías
     * @return array Resultado de cada operación
     */
    public function deshabilitarLote($ids_mercancias) {
        $resultados = [];
        
        foreach ($ids_mercancias as $id) {
            $this->idMercancia = $id;
            $resultados[$id] = $this->deshabilitarMercancia();
        }
        
        return $resultados;
    }
    
    /**
     * Registra un log de la deshabilitación (opcional)
     * Puedes crear una tabla de auditoría para esto
     * @param int $id_estado_deshabilitado
     */
    private function registrarLogDeshabilitacion($id_estado_deshabilitado) {
        // Implementar si tienes una tabla de logs/auditoría
        // Por ejemplo: INSERT INTO Log_Deshabilitacion (idMercancia, Fecha, Motivo)
        
        // Esta es una implementación básica de ejemplo:
        /*
        $query = "INSERT INTO Log_Deshabilitacion 
                  (idMercancia, Fecha_Deshabilitacion, Motivo) 
                  VALUES (:id_mercancia, NOW(), :motivo)";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id_mercancia', $this->idMercancia);
        $stmt->bindParam(':motivo', $this->motivo_deshabilitacion);
        $stmt->execute();
        */
    }
    
    /**
     * Verifica si una mercancía tiene relaciones activas
     * (ventas, lotes, etc.) antes de deshabilitar
     * @return bool
     */
    public function tieneRelacionesActivas() {
        $query = "SELECT COUNT(*) as total
                  FROM Mercancia_has_Proveedor
                  WHERE Mercancia_idMercancia = :id_mercancia";
        
        $stmt = $this->conexion->prepare($query);
        $stmt->bindParam(':id_mercancia', $this->idMercancia);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row['total'] > 0;
    }
}
?>
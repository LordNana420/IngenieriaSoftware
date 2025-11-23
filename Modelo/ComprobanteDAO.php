<?php
require_once __DIR__ . "/Conexion.php";

class ComprobanteDAO
{
    private $conexion;

    public function __construct()
    {
        $conexionObj = new Conexion();
        $this->conexion->abrir();
    }

    public function registrarComprobante(Comprobante $c)
    {
        $stmt = $this->conexion->prepare("
            INSERT INTO comprobante 
            (idVenta, idEmpleado, fechaGenerado, tipo, total, contenido)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "iissds",
            $c->getIdVenta(),
            $c->getIdEmpleado(),
            $c->getFechaGenerado(),
            $c->getTipo(),
            $c->getTotal(),
            $c->getContenido()
        );

        if ($stmt->execute()) {
            return $this->conexion->insert_id;
        }

        return false;
    }

    public function obtenerHistorial($idVenta = null)
    {
        $sql = "SELECT * FROM comprobante";
        if ($idVenta !== null) {
            $sql .= " WHERE idVenta = " . intval($idVenta);
        }

        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public function listarComprobantes()
    {
        $sql = "SELECT * FROM comprobante ORDER BY fechaGenerado DESC";
        return $this->conexion->query($sql)->fetch_all(MYSQLI_ASSOC);
    }
}

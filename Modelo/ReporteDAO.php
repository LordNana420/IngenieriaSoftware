<?php
require_once 'Conexion.php';
require_once 'Reporte.php';

class ReporteDAO {

    private $conexion;

    public function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion->abrir();
    }

    // =======================================================
    // CONVERTIR RESULTADO DE fetch_row() A ARRAY ASOCIATIVO
    // =======================================================
    private function rowsToAssocArray($rows, $columns)
    {
        $result = [];
        foreach ($rows as $row) {
            $assoc = [];
            foreach ($columns as $index => $colName) {
                $assoc[$colName] = $row[$index];
            }
            $result[] = $assoc;
        }
        return $result;
    }

    // =======================================================
    // REPORTE DIARIO
    // =======================================================
    public function generarReporteDiario($fecha)
    {
        $sql = "
        SELECT v.idVenta, v.fecha, v.total, dv.idProducto, dv.cantidad, dv.subtotal
        FROM venta v
        INNER JOIN detalle_venta dv ON v.idVenta = dv.idVenta
        WHERE DATE(v.fecha) = '$fecha'";

        $this->conexion->ejecutar($sql);

        $rows = [];
        while ($r = $this->conexion->registro()) {
            $rows[] = $r;
        }

        // columnas en orden exacto como las devuelve fetch_row()
        $colNames = ["idVenta", "fecha", "total", "idProducto", "cantidad", "subtotal"];

        $ventas = $this->rowsToAssocArray($rows, $colNames);

        return new Reporte($fecha, $fecha, $ventas);
    }

    // =======================================================
    // REPORTE SEMANAL
    // =======================================================
    public function generarReporteSemanal($fechaInicio, $fechaFin)
    {
        $sql = "
        SELECT v.idVenta, v.fecha, v.total, dv.idProducto, dv.cantidad, dv.subtotal
        FROM venta v
        INNER JOIN detalle_venta dv ON v.idVenta = dv.idVenta
        WHERE DATE(v.fecha) BETWEEN '$fechaInicio' AND '$fechaFin'";

        $this->conexion->ejecutar($sql);

        $rows = [];
        while ($r = $this->conexion->registro()) {
            $rows[] = $r;
        }

        $colNames = ["idVenta", "fecha", "total", "idProducto", "cantidad", "subtotal"];

        $ventas = $this->rowsToAssocArray($rows, $colNames);

        return new Reporte($fechaInicio, $fechaFin, $ventas);
    }

    // =======================================================
    // REPORTE MENSUAL
    // =======================================================
    public function generarReporteMensual($mes, $anio)
    {
        $sql = "
        SELECT v.idVenta, v.fecha, v.total, dv.idProducto, dv.cantidad, dv.subtotal
        FROM venta v
        INNER JOIN detalle_venta dv ON v.idVenta = dv.idVenta
        WHERE MONTH(v.fecha) = '$mes' AND YEAR(v.fecha) = '$anio'";

        $this->conexion->ejecutar($sql);

        $rows = [];
        while ($r = $this->conexion->registro()) {
            $rows[] = $r;
        }

        $colNames = ["idVenta", "fecha", "total", "idProducto", "cantidad", "subtotal"];

        $ventas = $this->rowsToAssocArray($rows, $colNames);

        // Rango completo del mes
        $fechaInicio = "$anio-$mes-01";
        $fechaFin = "$anio-$mes-" . date("t", strtotime($fechaInicio));

        return new Reporte($fechaInicio, $fechaFin, $ventas);
    }
}

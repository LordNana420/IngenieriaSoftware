<?php
class Reporte {
    private $fechaInicio;
    private $fechaFin;
    private $ventas; // array de ventas detalladas

    public function __construct($fechaInicio, $fechaFin, $ventas) {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
        $this->ventas = $ventas;
    }

    public function getFechaInicio(){ return $this->fechaInicio; }
    public function getFechaFin(){ return $this->fechaFin; }
    public function getVentas(){ return $this->ventas; }
}

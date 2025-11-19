<?php
class Venta {
    private $idVenta;
    private $idCliente;
    private $idEmpleado;
    private $fecha;
    private $total;

    public function __construct($idCliente, $idEmpleado, $fecha, $total) {
        $this->idCliente = $idCliente;
        $this->idEmpleado = $idEmpleado;
        $this->fecha = $fecha;
        $this->total = $total;
    }

    public function getIdCliente(){ return $this->idCliente; }
    public function getIdEmpleado(){ return $this->idEmpleado; }
    public function getFecha(){ return $this->fecha; }
    public function getTotal(){ return $this->total; }
}

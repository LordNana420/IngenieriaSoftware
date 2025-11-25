<?php
class Movimiento {

    private $id;
    private $idProducto;   // ← ahora coincide con el DAO
    private $tipo;
    private $cantidad;
    private $fecha;
    private $responsable;

    public function __construct($id = 0, $idProducto = 0, $tipo = "", $cantidad = 0, $fecha = "", $responsable = "") {
        $this->id = $id;
        $this->idProducto = $idProducto;
        $this->tipo = $tipo;
        $this->cantidad = $cantidad;
        $this->fecha = $fecha;
        $this->responsable = $responsable;
    }

    public function getId() { return $this->id; }
    public function getIdProducto() { return $this->idProducto; }
    public function getTipo() { return $this->tipo; }
    public function getCantidad() { return $this->cantidad; }
    public function getFecha() { return $this->fecha; }
    public function getResponsable() { return $this->responsable; }

}
?>

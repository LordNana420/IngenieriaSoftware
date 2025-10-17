<?php
class Mercancia {
    private $id;
    private $nombre;
    private $cantidad;
    private $stockMinimo;

    public function __construct($id = 0, $nombre = "", $cantidad = 0, $stockMinimo = 0) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->cantidad = $cantidad;
        $this->stockMinimo = $stockMinimo;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCantidad() { return $this->cantidad; }
    public function getStockMinimo() { return $this->stockMinimo; }

    // Setters
    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
    public function setStockMinimo($stockMinimo) { $this->stockMinimo = $stockMinimo; }
}
?>

<?php
class Producto {
    private $idProducto;
    private $stock;
    private $precio;

    public function __construct($idProducto, $stock, $precio) {
        $this->idProducto = $idProducto;
        $this->stock = $stock;
        $this->precio = $precio;
    }

    public function getIdProducto(){ return $this->idProducto; }
    public function getStock(){ return $this->stock; }
    public function getPrecio(){ return $this->precio; }
}

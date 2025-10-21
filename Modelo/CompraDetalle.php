<?php
class CompraDetalle {
    public $productoNombre;
    public $cantidad;
    public $precioUnitario;
    public $subtotal; // Propiedad calculada

    public function __construct($productoNombre, $cantidad, $precioUnitario) {
        $this->productoNombre = $productoNombre;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->subtotal = $cantidad * $precioUnitario;
    }
}
?>

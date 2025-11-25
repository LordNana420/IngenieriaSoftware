<?php
class Mercancia {
    private $id;
    private $nombre;
    private $cantidad;
    private $stockMinimo;
    private $stockMaximo;
    private $causa;
    private $fechaIngreso;
    private $fechaVencimiento;
    private $precioUnitario;
    private $estadoId;
    private $tipoId;
    private $inventarioId;

    public function __construct(
        $id = null, 
        $nombre = "", 
        $cantidad = 0, 
        $stockMinimo = 0, 
        $stockMaximo = 0, 
        $causa = "",
        $fechaIngreso = "",
        $fechaVencimiento = "",
        $precioUnitario = 0,
        $estadoId = 1,
        $tipoId = 1,
        $inventarioId = 1
    ) {
        $this->id = $id; // ahora puede ser null
        $this->nombre = $nombre;
        $this->cantidad = $cantidad;
        $this->stockMinimo = $stockMinimo;
        $this->stockMaximo = $stockMaximo;
        $this->causa = $causa;
        $this->fechaIngreso = $fechaIngreso;
        $this->fechaVencimiento = $fechaVencimiento;
        $this->precioUnitario = $precioUnitario;
        $this->estadoId = $estadoId;
        $this->tipoId = $tipoId;
        $this->inventarioId = $inventarioId;
    }

    // Getters y setters
    public function getId() { return $this->id; }
    public function getNombre() { return $this->nombre; }
    public function getCantidad() { return $this->cantidad; }
    public function getStockMinimo() { return $this->stockMinimo; }
    public function getStockMaximo() { return $this->stockMaximo; }
    public function getCausa() { return $this->causa; }
    public function getFechaIngreso() { return $this->fechaIngreso; }
    public function getFechaVencimiento() { return $this->fechaVencimiento; }
    public function getPrecioUnitario() { return $this->precioUnitario; }
    public function getEstadoId() { return $this->estadoId; }
    public function getTipoId() { return $this->tipoId; }
    public function getInventarioId() { return $this->inventarioId; }

    public function setNombre($nombre) { $this->nombre = $nombre; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }
    public function setStockMinimo($stockMinimo) { $this->stockMinimo = $stockMinimo; }
    public function setStockMaximo($stockMaximo) { $this->stockMaximo = $stockMaximo; }
    public function setCausa($causa) { $this->causa = $causa; }
    public function setFechaIngreso($fechaIngreso) { $this->fechaIngreso = $fechaIngreso; }
    public function setFechaVencimiento($fechaVencimiento) { $this->fechaVencimiento = $fechaVencimiento; }
    public function setPrecioUnitario($precioUnitario) { $this->precioUnitario = $precioUnitario; }
    public function setEstadoId($estadoId) { $this->estadoId = $estadoId; }
    public function setTipoId($tipoId) { $this->tipoId = $tipoId; }
    public function setInventarioId($inventarioId) { $this->inventarioId = $inventarioId; }
}
?>

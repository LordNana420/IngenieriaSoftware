<?php
class HistorialCompras {
    private $idVenta;
    private $clienteNombre;
    private $clienteApellido;
    private $empleadoNombre;
    private $empleadoApellido;
    private $productoNombre;
    private $cantidad;
    private $precioUnitario;
    private $fechaIngreso;

    public function __construct($idVenta = null, $clienteNombre = null, $clienteApellido = null, $empleadoNombre = null, $empleadoApellido = null, $productoNombre = null, $cantidad = null, $precioUnitario = null, $fechaIngreso = null) {
        $this->idVenta = $idVenta;
        $this->clienteNombre = $clienteNombre;
        $this->clienteApellido = $clienteApellido;
        $this->empleadoNombre = $empleadoNombre;
        $this->empleadoApellido = $empleadoApellido; 
        $this->productoNombre = $productoNombre;
        $this->cantidad = $cantidad;
        $this->precioUnitario = $precioUnitario;
        $this->fechaIngreso = $fechaIngreso;
    }

    // ----- Getters y Setters -----
    public function getIdVenta() { return $this->idVenta; }
    public function setIdVenta($idVenta) { $this->idVenta = $idVenta; }

    public function getClienteNombre() { return $this->clienteNombre; }
    public function setClienteNombre($clienteNombre) { $this->clienteNombre = $clienteNombre; }

    public function getClienteApellido() { return $this->clienteApellido; }
    public function setClienteApellido($clienteApellido) { $this->clienteApellido = $clienteApellido; }

    public function getEmpleadoNombre() { return $this->empleadoNombre; }
    public function setEmpleadoNombre($empleadoNombre) { $this->empleadoNombre = $empleadoNombre; }

    public function getEmpleadoApellido() { return $this->empleadoApellido; }
    public function setEmpleadoApellido($empleadoApellido) { $this->empleadoApellido = $empleadoApellido; }

    public function getProductoNombre() { return $this->productoNombre; }
    public function setProductoNombre($productoNombre) { $this->productoNombre = $productoNombre; }

    public function getCantidad() { return $this->cantidad; }
    public function setCantidad($cantidad) { $this->cantidad = $cantidad; }

    public function getPrecioUnitario() { return $this->precioUnitario; }
    public function setPrecioUnitario($precioUnitario) { $this->precioUnitario = $precioUnitario; }

    public function getFechaIngreso() { return $this->fechaIngreso; }
    public function setFechaIngreso($fechaIngreso) { $this->fechaIngreso = $fechaIngreso; }
}
?>

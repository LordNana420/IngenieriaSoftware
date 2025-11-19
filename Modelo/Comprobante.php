<?php
class Comprobante
{
    private $idComprobante;
    private $idVenta;
    private $idEmpleado;
    private $fechaGenerado;
    private $tipo;          // Factura, Ticket, Recibo
    private $total;
    private $contenido;     // JSON o String con detalle

    public function __construct($idVenta, $idEmpleado, $tipo, $total, $contenido)
    {
        $this->idVenta = $idVenta;
        $this->idEmpleado = $idEmpleado;
        $this->tipo = $tipo;
        $this->total = $total;
        $this->contenido = $contenido;
        $this->fechaGenerado = date("Y-m-d H:i:s");
    }

    /* GETTERS */

    public function getIdComprobante()      { return $this->idComprobante; }
    public function getIdVenta()            { return $this->idVenta; }
    public function getIdEmpleado()         { return $this->idEmpleado; }
    public function getFechaGenerado()      { return $this->fechaGenerado; }
    public function getTipo()               { return $this->tipo; }
    public function getTotal()              { return $this->total; }
    public function getContenido()          { return $this->contenido; }

    /* SETTERS */

    public function setIdComprobante($id)   { $this->idComprobante = $id; }
}

<?php
require_once("../Modelo/MercanciaDAO.php");

class MercanciaControlador {
    private $mercanciaDAO;

    // Constructor: inicializa el DAO
    public function __construct() {
        $this->mercanciaDAO = new MercanciaDAO();
    }

    // Retorna las alertas de stock bajo
    public function obtenerAlertasStock() {
        return $this->mercanciaDAO->consultarStockBajo();
    }

    // Opcional: cerrar conexión
    public function cerrarConexion() {
        $this->mercanciaDAO->cerrarConexion();
    }
}
?>

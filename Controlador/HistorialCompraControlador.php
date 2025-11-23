<?php
require_once __DIR__ . "/../Modelo/HistorialComprasDAO.php";


class HistorialCompraControlador {

    public function mostrarHistorialCliente($idCliente) {
        $dao = new HistorialComprasDAO();
        $historial = $dao->obtenerHistorialPorCliente($idCliente);
        include '../vista/historial_cliente_vista.php';
    }
}

// Si viene una petición directa desde la URL:
if (isset($_GET['idCliente'])) {
    $controlador = new HistorialCompraControlador();
    $controlador->mostrarHistorialCliente($_GET['idCliente']);
}
?>

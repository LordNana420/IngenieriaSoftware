<?php
require_once "../Modelo/ClienteDAO.php";

class ClienteControlador {
    private $clienteDAO;

    public function __construct() {
        $this->clienteDAO = new ClienteDAO();
    }

    public function obtenerClientes() {
        $clienteDAO = new ClienteDAO();
        return $clienteDAO->consultarTodos();
    }

    public function registrarCliente(Cliente $cliente) {
        $clienteDAO = new ClienteDAO();
        return $clienteDAO->insertar($cliente);
    }
}
?>

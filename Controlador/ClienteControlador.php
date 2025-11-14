<?php
require_once "Modelo/ClienteDAO.php";

class ClienteControlador {
    private $clienteDAO;

    public function __construct() {
        $this->clienteDAO = new ClienteDAO();
    }

    public function obtenerClientes() {
        $clienteDAO = new ClienteDAO();
        return $clienteDAO->consultarTodos();
    }
    public function BuscarClientes($nom,$ape, $doc, $tel) {
        $clienteDAO = new ClienteDAO();
        return $clienteDAO->consultarPorParametros($nom,$ape, $doc, $tel);
    }

    public function registrarCliente(Cliente $cliente) {
        $clienteDAO = new ClienteDAO();
        return $clienteDAO->insertar($cliente);
    }
}
?>

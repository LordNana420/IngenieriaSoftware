<?php
require_once __DIR__ . "/../Modelo/ClienteDAO.php";
require_once __DIR__ . "/../Modelo/Cliente.php"; // si lo necesitas también


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

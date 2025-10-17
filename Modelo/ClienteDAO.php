<?php
require_once("persistencia/Conexion.php");

class ClienteDAO {
    private $idCliente;
    private $nombre;
    private $apellido;
    private $telefono;

    public function __construct($idCliente = 0, $nombre = "", $apellido = "", $telefono = "") {
        $this->idCliente = $idCliente;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
    }

    /**
     * 🔹 Consultar todos los clientes
     * Retorna un listado completo ordenado alfabéticamente
     */
    public function consultarTodos() {
        return "SELECT idCliente, Nombre, Apellido, Telefono 
                FROM Cliente 
                ORDER BY Nombre ASC";
    }

    /**
     * 🔹 Consultar cliente por ID
     */
    public function consultarPorId() {
        return "SELECT idCliente, Nombre, Apellido, Telefono 
                FROM Cliente 
                WHERE idCliente = " . $this->idCliente;
    }

    

   
}
?>

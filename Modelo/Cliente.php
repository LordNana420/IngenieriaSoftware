<?php
class Cliente {
    private $id;
    private $nombre;
    private $apellido;
    private $telefono;
    private $estado;

    public function __construct($id = "", $nombre = "", $apellido = "", $telefono = "", $estado = "") {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->estado = $estado;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellido() {
        return $this->apellido;
    }

    public function getTelefono() {
        return $this->telefono;
    }
    public function getEstado() {
        return $this->estado;
    }

    // Setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    public function setTelefono($telefono) {
        $this->telefono = $telefono;
    }

    public function setEstado($estado) {
    $this->estado = $estado;
}
}
?>

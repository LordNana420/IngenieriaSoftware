<?php
require_once __DIR__ . "/../Modelo/MercanciaDAO.php";
require_once __DIR__ . "/../Modelo/Mercancia.php";

class MercanciaControlador
{
    private $dao;

    public function __construct()
    {
        $this->dao = new MercanciaDAO();
        $this->procesarPost();
    }

    public function obtenerTodos()
    {
        return $this->dao->consultarTodos();
    }

    public function obtenerAlertasStock()
    {
        return $this->dao->consultarStock();
    }

    public function registrarInsumo($data)
    {
        $mercancia = new Mercancia(
            null,
            $data['nombre'] ?? '',
            $data['cantidad'] ?? 0,
            $data['stock_minimo'] ?? 0,
            '' // causa opcional
        );

        return $this->dao->registrarInsumo($mercancia, $data['descripcion'] ?? '', $data['precio'] ?? 0);
    }

    private function procesarPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['accion'])) {

            $accion = $_POST['accion'];

            if ($accion === 'registrar') {
                $this->registrarInsumo($_POST);
                header("Location: ../Vista/MercanciaRegistro.php");
                exit();
            }
        }
    }

    public function cerrarConexion()
    {
        $this->dao->cerrarConexion();
    }
}

$controlador = new MercanciaControlador();
?>

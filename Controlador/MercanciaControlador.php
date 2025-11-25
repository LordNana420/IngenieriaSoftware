<?php
require_once __DIR__ . "/../Modelo/MercanciaDAO.php";
require_once __DIR__ . "/../Modelo/Mercancia.php";

class MercanciaControlador
{
    private $dao;

    public function __construct()
    {
        $this->dao = new MercanciaDAO();
        $this->procesarPost(); // Procesar registro si viene POST
    }

    /**
     * Obtener todos los productos
     */
    public function obtenerTodos()
    {
        return $this->dao->consultarTodos();
    }

    /**
     * Obtener alertas de stock bajo
     */
    public function obtenerAlertasStock()
    {
        return $this->dao->consultarStock();
    }

    /**
     * Registrar un nuevo insumo
     */
    public function registrarInsumo($data)
    {
        $responsable = $data['responsable'] ?? 'Desconocido';

        $mercancia = new Mercancia(
            null, // ID nulo para auto_increment
            $data['nombre'] ?? '',
            $data['cantidad'] ?? 0,
            $data['stock_minimo'] ?? 0,
            $data['stock_maximo'] ?? 0,
            '', // causa (para alertas)
            $data['fecha_ingreso'] ?? date('Y-m-d'),
            $data['fecha_vencimiento'] ?? '',
            $data['precio_unitario'] ?? 0,
            $data['estado_id'] ?? 1,
            $data['tipo_id'] ?? 1,
            $data['inventario_id'] ?? 1
        );

        return $this->dao->registrarInsumo($mercancia, $responsable);
    }

    public function deshabilitarInsumo($idMercancia)
    {
        return $this->dao->deshabilitarMercancia($idMercancia);
    }

    /**
     * Procesar formulario POST para registrar insumo
     */
    private function procesarPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['accion'])) {
            $accion = $_POST['accion'];

            if ($accion === 'registrar') {
                $this->registrarInsumo($_POST);
                // Redirigir para evitar resubmission
                header("Location: ../Vista/MercanciaRegistro.php");
                exit();
            }
        }
    }

    /**
     * Cerrar conexión a la base de datos
     */
    public function cerrarConexion()
    {
        $this->dao->cerrarConexion();
    }
}

// Instanciar controlador para procesar POST automáticamente
$controlador = new MercanciaControlador();
?>

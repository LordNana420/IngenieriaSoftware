<?php
require_once("Modelo/MercanciaDAO.php");

class MercanciaControlador {
    private $mercanciaDAO;

    // Constructor: inicializa el DAO
    public function __construct() {
        $this->mercanciaDAO = new MercanciaDAO();
    }

    // Retorna las alertas de stock bajo
    public function obtenerAlertasStock() {
        return $this->mercanciaDAO->consultarStock();
    }

    // Opcional: cerrar conexión
    public function cerrarConexion() {
        $this->mercanciaDAO->cerrarConexion();
    }


    /* ======================================================
     * MÉTODOS DE INVENTARIO (DESHABILITAR/HABILITAR)
     * ====================================================== */

    public function index() {
        $mercancias_activas = $this->mercanciaDAO->obtenerMercanciasActivas();
        $mercancias_deshabilitadas = $this->mercanciaDAO->obtenerMercanciasDeshabilitadas();

        require_once __DIR__ . '/../Vista/inventario/index.php';
    }

    public function deshabilitar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        if (!isset($_POST['idMercancia']) || empty($_POST['idMercancia'])) {
            return $this->json(['success' => false, 'message' => 'ID inválido'], 400);
        }

        $id = filter_var($_POST['idMercancia'], FILTER_SANITIZE_NUMBER_INT);

        if ($this->mercanciaDAO->deshabilitarMercancia($id)) {
            return $this->json([
                'success' => true,
                'message' => 'Mercancía deshabilitada correctamente',
                'idMercancia' => $id
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Error al deshabilitar la mercancía.'
        ], 400);
    }

    public function habilitar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        if (!isset($_POST['idMercancia']) || empty($_POST['idMercancia'])) {
            return $this->json(['success' => false, 'message' => 'ID inválido'], 400);
        }

        $id = filter_var($_POST['idMercancia'], FILTER_SANITIZE_NUMBER_INT);

        if ($this->mercanciaDAO->habilitarMercancia($id)) {
            return $this->json([
                'success' => true,
                'message' => 'Mercancía habilitada correctamente',
                'idMercancia' => $id
            ]);
        }

        return $this->json(['success' => false, 'message' => 'Error al habilitar mercancía'], 400);
    }

    public function detalles() {
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            return $this->json(['success' => false, 'message' => 'ID no proporcionado'], 400);
        }

        $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

        $detalles = $this->mercanciaDAO->obtenerDetallesMercancia($id);

        if ($detalles) {
            return $this->json(['success' => true, 'data' => $detalles]);
        }

        return $this->json(['success' => false, 'message' => 'Mercancía no encontrada'], 404);
    }

    public function listarDeshabilitadas() {
        $lista = $this->mercanciaDAO->obtenerMercanciasDeshabilitadas();

        return $this->json([
            'success' => true,
            'data' => $lista,
            'total' => count($lista)
        ]);
    }

    public function deshabilitarLote() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        if (!isset($_POST['ids']) || !is_array($_POST['ids'])) {
            return $this->json(['success' => false, 'message' => 'IDs inválidos'], 400);
        }

        $ids = array_map(function($id) {
            return filter_var($id, FILTER_SANITIZE_NUMBER_INT);
        }, $_POST['ids']);

        $resultado = $this->mercanciaDAO->deshabilitarLote($ids);

        return $this->json([
            'success' => true,
            'message' => 'Proceso de deshabilitación de lote completado.',
            'resultado' => $resultado
        ]);
    }


    /* ======================================================
     * RESPUESTA JSON
     * ====================================================== */

    private function json($data, $status = 200) {
        http_response_code($status);
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}
?>

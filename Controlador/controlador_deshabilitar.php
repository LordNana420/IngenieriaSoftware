<?php
/**
 * Controlador DeshabilitarController
 * Maneja las peticiones relacionadas con deshabilitar/habilitar mercancías
 */

require_once 'Modelo/Deshabilitar.php';
require_once 'Modelo/Conexion.php';

class DeshabilitarController {
    
    private $db;
    private $deshabilitar;
    
    public function __construct() {
        $database = new Conexion();
        $this->db = $database->getConexion();
        $this->deshabilitar = new Deshabilitar($this->db);
    }
    
    /**
     * Muestra la vista del inventario con mercancías activas
     */
    public function index() {
        $mercancias_activas = $this->deshabilitar->obtenerMercanciasActivas();
        $mercancias_deshabilitadas = $this->deshabilitar->obtenerMercanciasDeshabilitadas();
        
        // Cargar la vista
        require_once 'views/inventario/index.php';
    }
    
    /**
     * Procesa la deshabilitación de una mercancía
     */
    public function deshabilitar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Validar datos recibidos
            if (isset($_POST['idMercancia']) && !empty($_POST['idMercancia'])) {
                
                $this->deshabilitar->idMercancia = filter_var($_POST['idMercancia'], FILTER_SANITIZE_NUMBER_INT);
                
                // Opcional: capturar motivo de deshabilitación
                if (isset($_POST['motivo'])) {
                    $this->deshabilitar->motivo_deshabilitacion = htmlspecialchars($_POST['motivo']);
                }
                
                // Verificar si tiene relaciones activas (opcional)
                if ($this->deshabilitar->tieneRelacionesActivas()) {
                    $mensaje = "Advertencia: Esta mercancía tiene proveedores asociados.";
                }
                
                // Intentar deshabilitar
                if ($this->deshabilitar->deshabilitarMercancia()) {
                    $this->respuestaJSON([
                        'success' => true,
                        'message' => 'Mercancía deshabilitada correctamente',
                        'idMercancia' => $this->deshabilitar->idMercancia
                    ]);
                } else {
                    $this->respuestaJSON([
                        'success' => false,
                        'message' => 'Error al deshabilitar la mercancía. Verifique que esté activa.'
                    ], 400);
                }
                
            } else {
                $this->respuestaJSON([
                    'success' => false,
                    'message' => 'ID de mercancía no válido'
                ], 400);
            }
        } else {
            $this->respuestaJSON([
                'success' => false,
                'message' => 'Método no permitido'
            ], 405);
        }
    }
    
    /**
     * Procesa la habilitación de una mercancía
     */
    public function habilitar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (isset($_POST['idMercancia']) && !empty($_POST['idMercancia'])) {
                
                $this->deshabilitar->idMercancia = filter_var($_POST['idMercancia'], FILTER_SANITIZE_NUMBER_INT);
                
                if ($this->deshabilitar->habilitarMercancia()) {
                    $this->respuestaJSON([
                        'success' => true,
                        'message' => 'Mercancía habilitada correctamente',
                        'idMercancia' => $this->deshabilitar->idMercancia
                    ]);
                } else {
                    $this->respuestaJSON([
                        'success' => false,
                        'message' => 'Error al habilitar la mercancía'
                    ], 400);
                }
                
            } else {
                $this->respuestaJSON([
                    'success' => false,
                    'message' => 'ID de mercancía no válido'
                ], 400);
            }
        }
    }
    
    /**
     * Obtiene los detalles de una mercancía específica
     */
    public function detalles() {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            
            $this->deshabilitar->idMercancia = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            $detalles = $this->deshabilitar->obtenerDetallesMercancia();
            
            if ($detalles) {
                $this->respuestaJSON([
                    'success' => true,
                    'data' => $detalles
                ]);
            } else {
                $this->respuestaJSON([
                    'success' => false,
                    'message' => 'Mercancía no encontrada'
                ], 404);
            }
        }
    }
    
    /**
     * Lista las mercancías deshabilitadas
     */
    public function listarDeshabilitadas() {
        $mercancias = $this->deshabilitar->obtenerMercanciasDeshabilitadas();
        
        $this->respuestaJSON([
            'success' => true,
            'data' => $mercancias,
            'total' => count($mercancias)
        ]);
    }
    
    /**
     * Deshabilita múltiples mercancías en lote
     */
    public function deshabilitarLote() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            if (isset($_POST['ids']) && is_array($_POST['ids'])) {
                
                $ids = array_map(function($id) {
                    return filter_var($id, FILTER_SANITIZE_NUMBER_INT);
                }, $_POST['ids']);
                
                $resultados = $this->deshabilitar->deshabilitarLote($ids);
                
                $exitosos = array_filter($resultados);
                
                $this->respuestaJSON([
                    'success' => true,
                    'message' => count($exitosos) . ' mercancías deshabilitadas de ' . count($ids),
                    'resultados' => $resultados
                ]);
                
            } else {
                $this->respuestaJSON([
                    'success' => false,
                    'message' => 'No se proporcionaron IDs válidos'
                ], 400);
            }
        }
    }
    
    /**
     * Envía respuesta en formato JSON
     */
    private function respuestaJSON($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// Enrutamiento simple
if (isset($_GET['action'])) {
    $controller = new DeshabilitarController();
    $action = $_GET['action'];
    
    switch($action) {
        case 'deshabilitar':
            $controller->deshabilitar();
            break;
        case 'habilitar':
            $controller->habilitar();
            break;
        case 'detalles':
            $controller->detalles();
            break;
        case 'listar-deshabilitadas':
            $controller->listarDeshabilitadas();
            break;
        case 'deshabilitar-lote':
            $controller->deshabilitarLote();
            break;
        default:
            $controller->index();
    }
} else {
    $controller = new DeshabilitarController();
    $controller->index();
}
?>
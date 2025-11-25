<?php
// DEBUG temporal: mostrar errores en pantalla
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

        // pasar el precio correcto a la DAO (y mantener responsable separado)
        $precio = $data['precio_unitario'] ?? ($data['precio'] ?? 0);
        return $this->dao->registrarInsumo($mercancia, $precio);
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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // aceptar 'accion' (es) o 'action' (form delete actual)
            $accion = $_POST['accion'] ?? $_POST['action'] ?? null;
            if (!$accion) return;

            if ($accion === 'registrar') {
                $ok = $this->registrarInsumo($_POST);
                if (session_status() !== PHP_SESSION_ACTIVE) session_start();
                $_SESSION['flash'] = $ok ? 'Mercancía registrada correctamente.' : 'Error al registrar la mercancía.';
                header("Location: ../Vista/Mercancia.php");
                exit();
            }

            if ($accion === 'actualizar') {
                // construir Mercancia con el id
                $mercancia = new Mercancia(
                    $_POST['id'] ?? null,
                    $_POST['nombre'] ?? '',
                    $_POST['cantidad'] ?? 0,
                    $_POST['stock_minimo'] ?? 0,
                    $_POST['stock_maximo'] ?? 0,
                    '', // causa
                    $_POST['fecha_ingreso'] ?? date('Y-m-d'),
                    $_POST['fecha_vencimiento'] ?? '',
                    $_POST['precio_unitario'] ?? 0,
                    $_POST['estado_id'] ?? 1,
                    $_POST['tipo_id'] ?? 1,
                    $_POST['inventario_id'] ?? 1
                );
                $precio = $_POST['precio_unitario'] ?? ($_POST['precio'] ?? 0);
                $ok = $this->dao->actualizarInsumo($mercancia, $precio);
                if (session_status() !== PHP_SESSION_ACTIVE) session_start();
                $_SESSION['flash'] = $ok ? 'Mercancía actualizada correctamente.' : 'Error al actualizar la mercancía.';
                header("Location: ../Vista/Mercancia.php");
                exit();
            }

            if ($accion === 'delete' || $accion === 'eliminar' || $accion === 'remove') {
                $id = $_POST['id'] ?? $_POST['idMercancia'] ?? null;
                $ok = false;
                if ($id) {
                    $ok = $this->dao->deshabilitarMercancia($id);
                }
                if (session_status() !== PHP_SESSION_ACTIVE) session_start();
                $_SESSION['flash'] = $ok ? 'Mercancía eliminada.' : 'Error al eliminar la mercancía.';
                header("Location: ../Vista/Mercancia.php");
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
try {
    $controlador = new MercanciaControlador();
} catch (\Throwable $e) {
    // asegurar sesión para mostrar flash si corresponde
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    $_SESSION['flash'] = 'Error interno: ' . $e->getMessage();
    // registrar en el log de Apache/PHP y mostrar en pantalla (temporal para debug)
    error_log('MercanciaControlador error: ' . $e->getMessage());
    echo '<pre>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</pre>';
    exit(1);
}

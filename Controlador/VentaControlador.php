<?php
require_once "Modelo/VentaDAO.php";
require_once "Modelo/ProductoDAO.php";
require_once "Modelo/ProductoVentaDAO.php";
require_once "Modelo/Venta.php";
require_once "Modelo/DetalleVenta.php";        // NUEVO
require_once "Modelo/ComprobanteDAO.php";      // NUEVO
require_once 'vendor/autoload.php';





class VentaControlador
{
    /* ==========================================================
       MOSTRAR FORMULARIO
    ========================================================== */
    public function mostrarFormulario()
    {
        $productoDAO = new ProductoDAO();
        $productos = $productoDAO->consultarTodos();
        require_once "Vista/venta/registrarVenta.php";
    }

    /* ==========================================================
       REGISTRAR VENTA COMPLETA
    ========================================================== */
    public function registrar($data)
    {
        try {

            /* VALIDACIONES */
            if (!isset($data['cliente']) || empty($data['cliente'])) {
                return ["exito" => false, "mensaje" => "Debe seleccionar un cliente válido."];
            }

            if (!isset($data['producto']) || count($data['producto']) == 0) {
                return ["exito" => false, "mensaje" => "Debe seleccionar al menos un producto."];
            }

            /* CAPTURA */
            $idCliente  = $data['cliente'];
            $idEmpleado = $_SESSION['idEmpleado'] ?? 1;
            $fecha      = $data['fecha'];
            $total      = $data['total'];

            $ventaDAO = new VentaDAO();

            /* REGISTRAR CABECERA */
            $idVenta = $ventaDAO->registrarVenta(new Venta($idCliente, $idEmpleado, $fecha, $total));

            if (!$idVenta) {
                return ["exito" => false, "mensaje" => "Error al registrar la venta."];
            }

            /* DETALLES */
            $productoDAO = new ProductoDAO();
            $detalleDAO  = new ProductoVentaDAO();

            $productos      = $data['producto'];
            $cantidades     = $data['cantidad'];
            $precioUnitario = $data['precio_unitario'];
            $precioTotal    = $data['precio_total'];

            for ($i = 0; $i < count($productos); $i++) {

                $idProd = $productos[$i];
                $cant   = $cantidades[$i];
                $pUnit  = $precioUnitario[$i];
                $pTot   = $precioTotal[$i];

                if ($detalleDAO->existeDetalle($idVenta, $idProd)) {
                    $detalleDAO->sumarDetalle($idVenta, $idProd, $cant, $pTot);
                } else {
                    $detalleDAO->registrarDetalle($idProd, $idVenta, $cant, $pUnit, $pTot);
                }

                $productoDAO->actualizarStock($idProd, $cant);
            }

            /* ==========================================================
               NUEVO: REGISTRAR Y GENERAR COMPROBANTE
            ========================================================== */

            $this->generarComprobante($idVenta);

            return [
                "exito" => true,
                "mensaje" => "Venta registrada correctamente"
            ];

        } catch (Exception $e) {
            return ["exito" => false, "mensaje" => "ERROR: " . $e->getMessage()];
        }
    }



    /* ==========================================================
                     NUEVAS FUNCIONES HU-014
    ========================================================== */

    /** Generar comprobante PDF/HTML */
    public function generarComprobante($idVenta)
    {
        $ventaDAO   = new VentaDAO();
        $detalleDAO = new ProductoVentaDAO();

        $venta    = $ventaDAO->consultarVenta($idVenta);
        $detalles = $detalleDAO->obtenerDetallesPorVenta($idVenta);

        $comprobanteDAO = new ComprobanteDAO();
        $idComprobante = $comprobanteDAO->registrarComprobante($idVenta, date("Y-m-d H:i:s"));

        // Generar archivo HTML para la vista
        require "Vista/venta/comprobanteVenta.php";
    }


    /** Registrar comprobante en BD (llamado dentro de generarComprobante) */
    public function registrarComprobante($comprobante)
    {
        $dao = new ComprobanteDAO();
        return $dao->registrarComprobante($comprobante);
    }


    /** Enviar comprobante por email */
    public function enviarComprobanteEmail($comprobante, $correoCliente)
    {
        // Lógica simple de envío
        mail($correoCliente, "Comprobante de compra", "Su comprobante está adjunto.");
        return true;
    }


    /** Imprimir comprobante */
    public function imprimirComprobante($comprobante)
    {
        // Versión HTML lista para imprimir
        require "Vista/venta/comprobanteVenta.php";
    }


    /** Mostrar historial de comprobantes */
    public function obtenerHistorialComprobantes()
    {
        $dao = new ComprobanteDAO();
        return $dao->listarComprobantes();
    }


    /** Generar reporte de ventas */
    public function generarReporteVentas($tipo, $fechaInicio, $fechaFin)
    {
        $dao = new VentaDAO();
        return $dao->reporteVentas($tipo, $fechaInicio, $fechaFin);
    }


    /** Exportar PDF */
    public function exportarPDF($reporte)
    {
        require_once "Libs/dompdf/autoload.inc.php";
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($reporte);
        $dompdf->render();
        $dompdf->stream("reporte_ventas.pdf");
    }


    /** Exportar Excel */
    public function exportarExcel($reporte)
    {
        header("Content-type: application/xls");
        header("Content-Disposition: attachment; filename=reporte_ventas.xls");
        echo $reporte;
    }
}

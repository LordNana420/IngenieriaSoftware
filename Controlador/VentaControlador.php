<?php
require_once "Modelo/VentaDAO.php";
require_once "Modelo/ProductoDAO.php";
require_once "Modelo/ProductoVentaDAO.php";
require_once "Modelo/Venta.php";

class VentaControlador
{

    /* ==========================================================
       MOSTRAR FORMULARIO (si lo necesitas en rutas MVC)
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
    public function registrar($data): array
    {
        try {

            /* ---------------------------------------------
               VALIDACIONES BÁSICAS
            --------------------------------------------- */
            if (!isset($data['cliente']) || empty($data['cliente'])) {
                return [
                    "exito" => false,
                    "mensaje" => "Debe seleccionar un cliente válido."
                ];
            }

            if (!isset($data['producto']) || count($data['producto']) == 0) {
                return [
                    "exito" => false,
                    "mensaje" => "Debe seleccionar al menos un producto."
                ];
            }


            /* ---------------------------------------------
               CAPTURA DE DATOS
            --------------------------------------------- */
            $idCliente  = $data['cliente'];
            $idEmpleado = $_SESSION['idEmpleado'] ?? 1;
            $fecha      = $data['fecha'];
            $total      = $data['total'];

            $ventaDAO = new VentaDAO();

            /* ---------------------------------------------
               REGISTRAR CABECERA DE LA VENTA
            --------------------------------------------- */
            $idVenta = $ventaDAO->registrarVenta(
                new Venta($idCliente, $idEmpleado, $fecha, $total)
            );

            if (!$idVenta) {
                return [
                    "exito" => false,
                    "mensaje" => "Error al registrar la venta."
                ];
            }

            /* ---------------------------------------------
               DAO necesarios para detalles y stock
            --------------------------------------------- */
            $productoDAO = new ProductoDAO();
            $detalleDAO  = new ProductoVentaDAO();

            $productos      = $data['producto'];
            $cantidades     = $data['cantidad'];
            $precioUnitario = $data['precio_unitario'];
            $precioTotal    = $data['precio_total'];


            /* ---------------------------------------------
               REGISTRAR DETALLES (permitir repetidos → sumar)
            --------------------------------------------- */
            for ($i = 0; $i < count($productos); $i++) {

                $idProd = $productos[$i];
                $cant   = $cantidades[$i];
                $pUnit  = $precioUnitario[$i];
                $pTot   = $precioTotal[$i];

                // Si ya existe el producto en esta venta → sumar
                if ($detalleDAO->existeDetalle($idVenta, $idProd)) {

                    $detalleDAO->sumarDetalle(
                        $idVenta,
                        $idProd,
                        $cant,
                        $pTot
                    );

                } else {

                    // Si NO existe → insertar normal
                    $detalleDAO->registrarDetalle(
                        $idProd,
                        $idVenta,
                        $cant,
                        $pUnit,
                        $pTot
                    );
                }

                // Actualizar stock en inventario/mercancia
                $productoDAO->actualizarStock($idProd, $cant);
            }


            return [
                "exito" => true,
                "mensaje" => "Venta registrada correctamente"
            ];

        } catch (Exception $e) {

            return [
                "exito" => false,
                "mensaje" => "ERROR: " . $e->getMessage()
            ];
        }
    }

}

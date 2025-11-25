<?php
require_once("Controlador/VentaControlador.php");
require_once("Controlador/ProductoControlador.php");

// Controladores
$controlProducto = new ProductoControlador();
$controlVenta = new VentaControlador();
$productos = $controlProducto->obtenerProductos();

// Variables para mensaje
$mensaje = "";
$exito = null;

// Fecha del servidor
$fechaServidor = date("Y-m-d H:i:s");

// Si el usuario presionó el botón registrar
if (isset($_POST['registrar'])) {

    // Validaciones básicas
    if (!empty($_POST["cliente"]) && isset($_POST["producto"]) && count($_POST["producto"]) > 0) {

        $_POST["fecha"] = $fechaServidor; // fuerza la fecha del servidor

        // Registrar venta
        // Nota: tu controlador debe aceptar $_POST dentro del método registrar()
        $resultado = $controlVenta->registrar($_POST);

        if (is_array($resultado)) {
            $mensaje = $resultado["mensaje"];
            $exito = $resultado["exito"];
        } else {
            $mensaje = "Error inesperado al registrar la venta.";
            $exito = false;
        }

    } else {
        $mensaje = "Debe seleccionar al menos un producto y un cliente válido.";
        $exito = false;
    }
}
?>


<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-warning py-3 shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4"><i class="bi bi-cart-plus"></i> Registrar Venta</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link text-dark fw-semibold" href="#"><i class="bi bi-box-arrow-right"></i> Cerrar
                    sesión</a>
            </li>
        </ul>
    </div>
</nav>

<!-- CONTENIDO -->
<div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-dark"><i class="bi bi-receipt"></i> Nueva Venta</h2>
        <a href="?pid=Vista/listarVentas.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if ($mensaje != "" && isset($_POST['registrar'])): ?>
        <?php if ($exito): ?>
            <div class='border border-success bg-success-subtle rounded-5 text-center text-success-emphasis fw-bold mb-3'>
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php else: ?>
            <div class='border border-danger bg-danger-subtle rounded-5 text-center text-danger-emphasis fw-bold mb-3'>
                <?= htmlspecialchars($mensaje) ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form action="?pid=Vista/registrarVenta.php" method="POST" class="card shadow p-4">

        <!-- Fecha (oculta) -->
        <input type="hidden" name="fecha" value="<?= $fechaServidor ?>">

        <!-- ID Cliente -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label fw-semibold">ID Cliente</label>
                <input type="number" name="cliente" class="form-control" placeholder="Ej: 1" required>
            </div>
        </div>

        <!-- Tabla de productos -->
        <table class="table table-bordered table-hover align-middle" id="tablaProductos">
            <thead class="table-warning">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Precio Total</th>
                    <th>Quitar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <select name="producto[]" class="form-select producto">
                            <?php foreach ($productos as $p): ?>
                                <option value="<?= $p['idProducto'] ?>" data-precio="<?= $p['precio'] ?>">
                                    <?= $p['nombre'] ?> - Stock: <?= $p['stock'] ?> - $<?= $p['precio'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>

                    <td><input type="number" name="cantidad[]" class="form-control cantidad" min="1" required></td>

                    <td><input type="number" name="precio_unitario[]" class="form-control precio_unitario" readonly></td>

                    <td><input type="number" name="precio_total[]" class="form-control precio_total" readonly></td>

                    <td>
                        <button type="button" class="btn btn-danger btn-sm eliminar">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Agregar producto (restaurado) -->
        <button type="button" id="agregar" class="btn btn-success mb-3">
            <i class="bi bi-plus-lg"></i> Agregar Producto
        </button>

        <!-- Total -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Total Venta</label>
            <input type="number" name="total" id="total" class="form-control" readonly required>
        </div>

        <!-- Botón único enviar -->
        <button type="submit" name="registrar" class="btn btn-warning fw-bold">
            <i class="bi bi-check-circle"></i> Registrar Venta
        </button>
    </form>

</div>

<!-- JAVASCRIPT -->
<script>
    // función auxiliar para inicializar precio en una fila (por select)
    function inicializarFila($fila) {
        let precio = $fila.find("select.producto option:selected").data("precio") || 0;
        $fila.find(".precio_unitario").val(precio);
        let cantidad = parseFloat($fila.find(".cantidad").val()) || 0;
        $fila.find(".precio_total").val(precio * cantidad);
    }

    // al cargar: inicializar la fila existente
    $(document).ready(function () {
        $("#tablaProductos tbody tr").each(function () {
            inicializarFila($(this));
        });
        calcularTotalGeneral();
    });

    // Agregar nueva fila
    $("#agregar").click(function () {

        let fila = `
        <tr>
            <td>
                <select name="producto[]" class="form-select producto">
                    <?php foreach ($productos as $p): ?>
                        <option value="<?= $p['idProducto'] ?>" data-precio="<?= $p['precio'] ?>">
                            <?= $p['nombre'] ?> - Stock: <?= $p['stock'] ?> - $<?= $p['precio'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </td>

            <td><input type="number" name="cantidad[]" class="form-control cantidad" min="1" value="1" required></td>

            <td><input type="number" name="precio_unitario[]" class="form-control precio_unitario" readonly></td>

            <td><input type="number" name="precio_total[]" class="form-control precio_total" readonly></td>

            <td>
                <button type="button" class="btn btn-danger btn-sm eliminar">
                    <i class="bi bi-x-lg"></i>
                </button>
            </td>
        </tr>
    `;

        $("#tablaProductos tbody").append(fila);

        // inicializar la última fila agregada
        let $ultima = $("#tablaProductos tbody tr").last();
        inicializarFila($ultima);
        calcularTotalGeneral();
    });

    // Eliminar fila
    $(document).on("click", ".eliminar", function () {
        $(this).closest("tr").remove();
        calcularTotalGeneral();
    });

    // Colocar precio unitario al seleccionar producto (funciona para filas nuevas y existentes)
    $(document).on("change", ".producto", function () {
        let $fila = $(this).closest("tr");
        inicializarFila($fila);
        calcularTotalGeneral();
    });

    // Recalcular al cambiar cantidad
    $(document).on("keyup change", ".cantidad", function () {
        let $fila = $(this).closest("tr");
        let cantidad = parseFloat($(this).val()) || 0;
        let precioUnitario = parseFloat($fila.find(".precio_unitario").val()) || 0;
        $fila.find(".precio_total").val(cantidad * precioUnitario);
        calcularTotalGeneral();
    });

    // Calcular total general
    function calcularTotalGeneral() {
        let total = 0;
        $(".precio_total").each(function () {
            total += parseFloat($(this).val()) || 0;
        });
        $("#total").val(total);
    }
</script>

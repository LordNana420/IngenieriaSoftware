<?php
// Normalmente aquí se incluiría el Controlador de Cliente y Producto,
// y se inicializarían variables para la venta (carrito, totales, etc.)
// require_once("../../Controlador/ClienteControlador.php");
// require_once("../../Controlador/ProductoControlador.php");
?>

<div class="container mt-5">
    <h2 class="fw-bold mb-4 text-center text-primary"><i class="bi bi-cart-plus-fill"></i> Nueva Venta</h2>
    
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white fw-bold">
            <i class="bi bi-person-lines-fill"></i> Datos de la Venta
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="documentoCliente" class="form-label fw-semibold">Documento del Cliente:</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="documentoCliente" placeholder="Cédula o NIT">
                        <button class="btn btn-outline-secondary" type="button" id="btnBuscarCliente">Buscar</button>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="nombreCliente" class="form-label fw-semibold">Cliente:</label>
                    <input type="text" class="form-control" id="nombreCliente" value="Consumidor Final" readonly>
                </div>
                <div class="col-md-6">
                    <label for="vendedor" class="form-label fw-semibold">Vendedor:</label>
                    <input type="text" class="form-control" id="vendedor" value="Juan Pérez (Ejemplo)" readonly> 
                </div>
                <div class="col-md-6">
                    <label for="fechaVenta" class="form-label fw-semibold">Fecha:</label>
                    <input type="date" class="form-control" id="fechaVenta" value="<?= date('Y-m-d') ?>" readonly>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card shadow mb-4">
        <div class="card-header bg-info text-white fw-bold">
            <i class="bi bi-bag-plus-fill"></i> Agregar Productos
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label for="nombreProducto" class="form-label fw-semibold">Nombre/Código del Producto:</label>
                    <input type="text" class="form-control" id="nombreProducto" placeholder="Buscar producto...">
                </div>
                <div class="col-md-2">
                    <label for="cantidad" class="form-label fw-semibold">Cantidad:</label>
                    <input type="number" class="form-control" id="cantidad" value="1" min="1">
                </div>
                <div class="col-md-2">
                    <label for="precio" class="form-label fw-semibold">Precio Unitario:</label>
                    <input type="text" class="form-control" id="precio" value="0.00" readonly>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-success" type="button" id="btnAgregarProducto">
                        <i class="bi bi-plus-lg"></i> Añadir
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white fw-bold">
                    <i class="bi bi-list-check"></i> Detalle de la Venta
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Producto</th>
                                    <th>Cant.</th>
                                    <th>Precio Unit.</th>
                                    <th>Total</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoCarrito">
                                <tr>
                                    <td colspan="6" class="text-center text-muted p-4">
                                        <i class="bi bi-info-circle"></i> No hay productos en el carrito.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Resumen de Pago</h5>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Subtotal:
                            <span class="fw-semibold" id="subtotalVenta">$ 0.00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            IVA (19%):
                            <span class="fw-semibold" id="ivaVenta">$ 0.00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light fw-bold fs-5">
                            Total a Pagar:
                            <span class="text-success" id="totalVenta">$ 0.00</span>
                        </li>
                    </ul>
                    
                    <button class="btn btn-success btn-lg w-100" id="btnFinalizarVenta">
                        <i class="bi bi-currency-dollar"></i> Finalizar Venta
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</div>

<script>
    // Tu lógica AJAX y JavaScript de venta irá aquí.
    // Ejemplo de funcionalidad básica con jQuery:
    
    $(document).ready(function() {
        // Lógica para buscar cliente...
        $('#btnBuscarCliente').on('click', function() {
            let doc = $('#documentoCliente').val();
            // Lógica AJAX para buscar cliente en la BD
            console.log('Buscando cliente con documento:', doc);
        });

        // Lógica para agregar producto...
        $('#btnAgregarProducto').on('click', function() {
            // Lógica para validar, buscar precio y añadir fila a #cuerpoCarrito
            console.log('Producto añadido.');
            // Aquí iría el código para actualizar #cuerpoCarrito, #subtotalVenta, etc.
        });

        $('#btnFinalizarVenta').on('click', function() {
            // Lógica AJAX para guardar la venta en la BD
            alert('Procesando Venta...');
        });
    });
</script>
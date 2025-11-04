<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary">
            <i class="fa-solid fa-receipt me-2"></i>Historial de Compras del Cliente
        </h2>
        <a href="../provisionalIndex.php" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left"></i> Volver al menú
        </a>
    </div>

    <?php if (empty($historial)): ?>
        <div class="alert alert-warning text-center" role="alert">
            <i class="fa-solid fa-circle-exclamation me-2"></i>
            No hay compras registradas para este cliente.
        </div>
    <?php else: ?>
        <div class="table-responsive shadow-sm rounded">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th>ID Venta</th>
                        <th>Cliente</th>
                        <th>Empleado</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unitario</th>
                        <th>Fecha Ingreso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial as $h): ?>
                        <tr>
                            <td class="text-center"><?= $h->getIdVenta() ?></td>
                            <td><?= htmlspecialchars($h->getClienteNombre() . " " . $h->getClienteApellido()) ?></td>
                            <td><?= htmlspecialchars($h->getEmpleadoNombre() . " " . $h->getEmpleadoApellido()) ?></td>
                            <td><?= htmlspecialchars($h->getProductoNombre()) ?></td>
                            <td class="text-center"><?= $h->getCantidad() ?></td>
                            <td class="text-end">$<?= number_format($h->getPrecioUnitario(), 2) ?></td>
                            <td class="text-center"><?= htmlspecialchars($h->getFechaIngreso()) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</div>
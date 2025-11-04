
    <div class="container mt-5">
        <h2 class="text-center mb-4">🛒 Historial de Compras del Cliente</h2>

        <?php if (empty($historial)): ?>
            <div class="alert alert-warning text-center" role="alert">
                No hay compras registradas para este cliente.
            </div>
        <?php else: ?>
            <div class="table-responsive shadow-sm">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th scope="col">ID Venta</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Empleado</th>
                            <th scope="col">Producto</th>
                            <th scope="col">Cantidad</th>
                            <th scope="col">Precio Unitario</th>
                            <th scope="col">Fecha Ingreso</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $h): ?>
                            <tr>
                                <td><?= htmlspecialchars($h->getIdVenta()) ?></td>
                                <td><?= htmlspecialchars($h->getClienteNombre() . " " . $h->getClienteApellido()) ?></td>
                                <td><?= htmlspecialchars($h->getEmpleadoNombre() . " " . $h->getEmpleadoApellido()) ?></td>
                                <td><?= htmlspecialchars($h->getProductoNombre()) ?></td>
                                <td><?= htmlspecialchars($h->getCantidad()) ?></td>
                                <td>$<?= number_format($h->getPrecioUnitario(), 2) ?></td>
                                <td><?= htmlspecialchars($h->getFechaIngreso()) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="?pid=provisionalIndex.php" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Volver al Menú
            </a>
        </div>
    </div>

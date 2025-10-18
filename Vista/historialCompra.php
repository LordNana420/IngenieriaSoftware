<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Compras del Cliente</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <h2>Historial de Compras del Cliente</h2>

    <?php if (empty($historial)): ?>
        <p>No hay compras registradas para este cliente.</p>
    <?php else: ?>
        <table border="1">
            <thead>
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
                        <td><?= $h->getIdVenta() ?></td>
                        <td><?= $h->getClienteNombre() . " " . $h->getClienteApellido() ?></td>
                        <td><?= $h->getEmpleadoNombre() . " " . $h->getEmpleadoApellido() ?></td>
                        <td><?= $h->getProductoNombre() ?></td>
                        <td><?= $h->getCantidad() ?></td>
                        <td>$<?= number_format($h->getPrecioUnitario(), 2) ?></td>
                        <td><?= $h->getFechaIngreso() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

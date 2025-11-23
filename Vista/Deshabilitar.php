<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Compras del Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">
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
                                <td>#<?= htmlspecialchars($m['idMercancia']) ?></td>
                                <td><strong><?= htmlspecialchars($m['Nombre']) ?></strong></td>
                                <td><?= htmlspecialchars($m['Tipo']) ?></td>
                                <td><?= htmlspecialchars($m['Ubicacion']) ?></td>
                                <td><span class="badge bg-danger">Deshabilitado</span></td>
                                <td>
                                    <button class="btn btn-info btn-sm text-white" onclick="verDetalles(<?= $m['idMercancia'] ?>)">👁️ Ver</button>
                                    <button class="btn btn-success btn-sm" onclick="habilitarMercancia(<?= $m['idMercancia'] ?>, '<?= htmlspecialchars($m['Nombre']) ?>')">✅ Habilitar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="../provisionalIndex.php" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Volver al Menú
            </a>
        </div>
    </div>
</body>
</html>

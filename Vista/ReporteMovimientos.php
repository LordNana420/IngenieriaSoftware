<?php
// Asegurar que $movimientos existe
if (!isset($movimientos)) {
    $movimientos = [];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Movimientos</title>

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" 
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body { background: #f2f2f2; }
        .container {
            margin-top: 30px;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px #ccc;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        table { font-size: 14px; }
    </style>
</head>

<body>

<div class="container">
    <h2><i class="bi bi-table"></i> Reporte de Movimientos de Inventario</h2>

    <div class="text-end mb-3">
        <a href="../Controlador/ReporteInventarioControlador.php?accion=pdf" 
           class="btn btn-danger" target="_blank">
            <i class="bi bi-file-earmark-pdf-fill"></i> Descargar PDF
        </a>
    </div>

    <?php if (count($movimientos) === 0): ?>
        <div class="alert alert-warning text-center">
            No hay movimientos registrados en el inventario.
        </div>
    <?php else: ?>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>ID Producto</th>
                    <th>Tipo</th>
                    <th>Cantidad</th>
                    <th>Fecha</th>
                    <th>Responsable</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimientos as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m->getId()); ?></td>
                    <td><?= htmlspecialchars($m->getIdProducto()); ?></td>
                    <td><?= htmlspecialchars($m->getTipo()); ?></td>
                    <td><?= htmlspecialchars($m->getCantidad()); ?></td>
                    <td><?= htmlspecialchars($m->getFecha()); ?></td>
                    <td><?= htmlspecialchars($m->getResponsable()); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>
</div>

</body>
</html>

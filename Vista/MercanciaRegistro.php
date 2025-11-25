<?php
require_once __DIR__ . "/../Controlador/MercanciaControlador.php";
$controlador = new MercanciaControlador();
$mercancias = $controlador->obtenerTodos();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Insumos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Registro de Insumos al Inventario</h2>

    <!-- Formulario para registrar insumo -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark fw-bold">Nuevo Insumo</div>
        <div class="card-body">
           <form action="../Controlador/MercanciaControlador.php" method="POST">
    <input type="hidden" name="accion" value="registrar">
    <div class="row mb-3">
        <div class="col">
            <label>Nombre del insumo</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>
        <div class="col">
            <label>Cantidad</label>
            <input type="number" name="cantidad" class="form-control" required>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label>Fecha de ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="col">
            <label>Fecha de vencimiento</label>
            <input type="date" name="fecha_vencimiento" class="form-control">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col">
            <label>Stock mínimo</label>
            <input type="number" name="stock_minimo" class="form-control" required>
        </div>
        <div class="col">
            <label>Stock máximo</label>
            <input type="number" name="stock_maximo" class="form-control" required>
        </div>
    </div>
    <div class="mb-3">
        <label>Precio unitario</label>
        <input type="number" name="precio_unitario" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Responsable</label>
        <input type="text" name="responsable" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-success">Registrar Insumo</button>
</form>

        </div>
    </div>

    <!-- Tabla de mercancias -->
    <h3>Mercancias Registradas</h3>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Cantidad</th>
                <th>Stock Min.</th>
                <th>Stock Max.</th>
                <th>Precio Unit.</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Venc.</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($mercancias as $m): ?>
            <tr>
                <td><?= $m->getId() ?></td>
                <td><?= htmlspecialchars($m->getNombre()) ?></td>
                <td><?= $m->getCantidad() ?></td>
                <td><?= $m->getStockMinimo() ?></td>
                <td><?= $m->getStockMaximo() ?></td>
                <td><?= $m->getPrecioUnitario() ?></td>
                <td><?= $m->getFechaIngreso() ?></td>
                <td><?= $m->getFechaVencimiento() ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

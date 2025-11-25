<?php
require_once("Controlador/MercanciaControlador.php");
$controlador = new MercanciaControlador();

// Traer alertas de stock
$alertas = $controlador->obtenerAlertasStock();

// Traer inventario completo
$inventario = $controlador->obtenerTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - Sesión Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-warning py-3 shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4" href="#"><i class="bi bi-cupcake"></i> Panadería P.mirador</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="#"><i class="bi bi-person-circle"></i> Editar mis datos</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="#"><i class="bi bi-gear"></i> Configuración</a></li>
                <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="?salir=true"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
                <li class="nav-item ms-2">
                    <a class="btn btn-sm btn-primary text-white" href="Vista/ReporteMovimientos.php" target="_blank">
                        <i class="bi bi-basket"></i> Ver Movimientos
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-brown"><i class="bi bi-basket"></i> Inventario de Insumos</h2>
        <a href="Vista/MercanciaRegistro.php" class="btn btn-success"><i class="bi bi-plus-circle"></i> Registrar Insumo</a>
    </div>

    <table class="table table-hover table-bordered align-middle">
        <thead class="table-warning">
        <tr>
            <th><i class="bi bi-box"></i> Nombre</th>
            <th><i class="bi bi-person-vcard"></i> Proveedor</th>
            <th><i class="bi bi-bag"></i> Cantidad</th>
            <th><i class="bi bi-calendar-date"></i> Fecha de ingreso</th>
            <th><i class="bi bi-toggle-on"></i> Estado</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($inventario)): ?>
            <?php foreach ($inventario as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m->getNombre()) ?></td>
                    <td><?= htmlspecialchars($m->getProveedor() ?? 'N/A') ?></td>
                    <td><?= htmlspecialchars($m->getCantidad()) ?></td>
                    <td><?= htmlspecialchars($m->getFechaIngreso() ?? '') ?></td>
                    <td>
                        <?php if ($m->getEstadoId() == 1): ?>
                            <span class="badge bg-success">Activo</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Inactivo</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" class="text-center">No hay insumos registrados.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- ALERTAS DE STOCK -->
<div class="container mt-4">
    <h2 class="fw-bold mb-3"><i class="bi bi-exclamation-triangle-fill"></i> Alertas de Stock</h2>
    <table class="table table-bordered">
        <thead class="table-warning">
        <tr>
            <th>Nombre</th>
            <th>Cantidad Disponible</th>
            <th>Stock</th>
            <th>Causa</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($alertas)): ?>
            <?php foreach ($alertas as $m): ?>
                <tr>
                    <td><?= htmlspecialchars($m->getNombre()) ?></td>
                    <td><?= htmlspecialchars($m->getCantidad()) ?></td>
                    <td><?= htmlspecialchars($m->getStockMinimo()) ?></td>
                    <td>
                        <?php if (strcasecmp(htmlspecialchars($m->getCausa()), 'Stock muy bajo') === 0): ?>
                            <div class="alert alert-warning mt-2" role="alert">
                                <i class="bi bi-exclamation-triangle-fill"></i> <strong>Alerta de stock bajo</strong>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-danger mt-2" role="alert">
                                <i class="bi bi-exclamation-octagon-fill"></i> <strong>Alerta de exceso de producto</strong>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-center">No hay alertas de stock.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

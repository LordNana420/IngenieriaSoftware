<?php
// /opt/lampp/htdocs/IngenieriaSoftware/Vista/Venta.php
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Módulo de Ventas - IngenieriaSoftware</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet"/>
    <style>
        body { padding-top: 70px; }
        .card .display-1 { font-size: 4rem; opacity: .95; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="#">Ingeniería - Ventas</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="?pid=Vista/Venta.php">Ventas</a></li>
                <li class="nav-item"><a class="nav-link" href="?pid=Vista/Productos.php">Productos</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-5">
    <div class="py-4 text-center">
        <h1 class="fw-bold">🛒 Módulo de Ventas</h1>
        <p class="text-muted">Gestiona ventas, historial y reportes desde esta interfaz.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-5 mb-4">
            <div class="card shadow text-center p-4 h-100">
                <div class="card-body">
                    <i class="bi bi-cash-stack display-1 text-success"></i>
                    <h5 class="card-title mt-3 fs-3 fw-bold">Realizar Venta</h5>
                    <p class="card-text text-muted">Inicia un nuevo proceso de venta registrando productos y clientes.</p>
                    <a href="?pid=Vista/NuevaVenta.php" class="btn btn-success btn-lg mt-3">
                        <i class="bi bi-plus-circle"></i> Nueva Venta
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-5 mb-4">
            <div class="card shadow text-center p-4 h-100">
                <div class="card-body">
                    <i class="bi bi-journal-text display-1 text-primary"></i>
                    <h5 class="card-title mt-3 fs-3 fw-bold">Ver Ventas</h5>
                    <p class="card-text text-muted">Consulta el historial, detalles y reportes de todas las transacciones.</p>
                    <a href="?pid=Vista/reporteVentas.php" class="btn btn-primary btn-lg mt-3">
                        <i class="bi bi-table"></i> Historial
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="bg-light py-3 mt-5">
    <div class="container text-center text-muted">
        &copy; <?= date('Y') ?> Ingeniería - Sistema de Ventas
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
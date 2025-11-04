<?php
// ✅ PROVISIONAL INDEX - acceso libre a todas las vistas

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Si se solicita salir (solo por limpieza)
if (isset($_GET["salir"])) {
    session_unset();
    session_destroy();
    header("Location: provisionalIndex.php");
    exit;
}

// Si no hay parámetro pid, mostrar el menú principal

    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <title>Acceso Provisional - Panadería PMirador</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </head>

    <body class="bg-light">
        <div class="container mt-5">
            <h1 class="text-center mb-4">🌾 Acceso Provisional - Panadería PMirador</h1>
            <div class="list-group">
                <a href="?pid=Vista/inicio.php" class="list-group-item list-group-item-action">
                    🏠 Inicio
                </a>
                <a href="?pid=Vista/registrarCliente.php" class="list-group-item list-group-item-action">
                    👤 Registrar Cliente
                </a>
                <a href="?pid=Vista/sesionCliente.php" class="list-group-item list-group-item-action">
                    💼 Sesión Cliente
                </a>
                <a href="?pid=Vista/sesionAdmin.php" class="list-group-item list-group-item-action">
                    🛠️ Sesión Administrador
                </a>
                <a href="?pid=Vista/Deshabilitar.php" class="list-group-item list-group-item-action">
                    ⚙️ Deshabilitar Cliente
                </a>
                <a href="?pid=Vista/historialCompra.php" class="list-group-item list-group-item-action">
                    📜 Consulta Historial de Compras
                </a>
            </div>

            <p class="text-center mt-4 text-muted">
                *Versión provisional para pruebas. No requiere autenticación.*
            </p>
        </div>
    </body>
    </html>

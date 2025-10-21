<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_GET["salir"])) {
    session_unset();
    session_destroy();
    header("Location: index.php?pid=Vista/autenticar.php");
    exit;
}

$paginas_sin_autenticacion = array(
    "Vista/inicio.php",
    "Vista/autenticar.php",
    "Vista/registrarCliente.php"
);

$paginas_con_autenticacion = array(
    "Vista/sesionAdmin.php",
    "Vista/sesionCliente.php",
    "Vista/Deshabilitar.php"
);

if (!isset($_GET["pid"])) {
    include("Vista/inicio.php");
} else {
    $pid = $_GET["pid"];

    if (in_array($pid, $paginas_sin_autenticacion)) {
        include $pid;
    } else if (in_array($pid, $paginas_con_autenticacion)) {
        if (!isset($_SESSION["id"])) {
            include "Vista/autenticar.php";
        }
    } else {
        echo "error 404";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panadería PMirador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <h1 class="text-center mb-4">Accesos rápidos - Panadería PMirador</h1>

        <div class="list-group">
            <!-- 🔗 Enlaces temporales para probar vistas -->
            <a href="index.php?pid=Vista/inicio.php" class="list-group-item list-group-item-action">
                🏠 Inicio
            </a>
            <a href="index.php?pid=Vista/registrarCliente.php" class="list-group-item list-group-item-action">
                👤 Registrar Cliente
            </a>
            <a href="index.php?pid=Vista/sesionCliente.php" class="list-group-item list-group-item-action">
                💼 Sesión Cliente
            </a>
            <a href="index.php?pid=Vista/sesionAdmin.php" class="list-group-item list-group-item-action">
                🛠️ Sesión Administrador
            </a>
            <a href="index.php?pid=Vista/Deshabilitar.php" class="list-group-item list-group-item-action">
                ⚙️ Deshabilitar Cliente
            </a>
        </div>

        <p class="text-center mt-4 text-muted">
            *Estos accesos son solo para pruebas; la autenticación se habilitará más adelante.*
        </p>
    </div>

</body>
</html>

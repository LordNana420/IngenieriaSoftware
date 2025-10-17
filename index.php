<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (isset($_GET["salir"])) {
    session_unset();
    session_destroy();



    header("Location: index.php?pid=autenticar.php");
    exit;
}


$paginas_sin_autenticacion = array(
    "Vista/inicio.php",
    "Vista/autenticar.php"
);

$paginas_con_autenticacion = array(
    "Vista/sesionAdmin.php",
    "Vista/sesionCliente.php"
);


if (!isset($_GET["pid"])) {
    include("vista/inicio.php");
} else {
    $pid = $_GET["pid"];

    if (in_array($pid, $paginas_sin_autenticacion)) {
        include $pid;
    } else if (in_array($pid, $paginas_con_autenticacion)) {
        if (!isset($_SESSION["id"])) {
            include "autenticar.php";
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
    <title>Panaderia PMirador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

</html>
<?php
require_once("../Controlador/VentaControlador.php");
$controlador = new VentaControlador();

$mensaje = "";
$exito = false;

if (isset($_POST['generar'])) {

    $idVenta = $_POST['idVenta'];
    $idCajero = $_POST['idCajero'];

    $resultado = $controlador->generarComprobante($idVenta, $idCajero);

    if (is_array($resultado)) {
        $mensaje = $resultado['mensaje'];
        $exito = $resultado['exito'];
    } else {
        $mensaje = "Error inesperado al generar el comprobante.";
        $exito = false;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Comprobante de Venta - PMirador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container mt-5 col-md-6">
    <div class="card shadow">
        <div class="card-header bg-warning text-dark fw-bold">
            Generar Comprobante de Venta
        </div>

        <div class="card-body">

            <?php
            if ($mensaje && isset($_POST['generar'])) {
                if ($exito) {
                    echo "<div class='border border-success bg-success-subtle rounded-5 text-center 
                          text-success-emphasis fw-bold'>". $mensaje ."</div>";
                } else {
                    echo "<div class='border border-danger bg-danger-subtle rounded-5 text-center 
                          text-danger-emphasis fw-bold'>". $mensaje ."</div>";
                }
            }
            ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">ID Venta</label>
                    <input type="number" name="idVenta" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">ID Cajero</label>
                    <input type="number" name="idCajero" class="form-control" required>
                </div>

                <button type="submit" name="generar" class="btn btn-warning w-100 fw-bold">
                    Generar Comprobante
                </button>

            </form>

        </div>
    </div>
</div>
</body>
</html>

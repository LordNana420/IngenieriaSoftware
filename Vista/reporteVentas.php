<?php
session_start();
/*if ($_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}*/
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes de Ventas</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">

<div class="container py-4">
    <h2 class="mb-4 fw-bold text-center">Reportes Administrativos de Ventas</h2>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs mb-3" id="reporteTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="dia-tab" data-bs-toggle="tab" data-bs-target="#dia" type="button" role="tab">
                📅 Reporte Diario
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="semana-tab" data-bs-toggle="tab" data-bs-target="#semana" type="button" role="tab">
                📆 Reporte Semanal
            </button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mes-tab" data-bs-toggle="tab" data-bs-target="#mes" type="button" role="tab">
                📊 Reporte Mensual
            </button>
        </li>
    </ul>

    <!-- Contenido de las ventanitas -->
    <div class="tab-content" id="reporteTabsContent">

        <!-- REPORTES DIARIOS -->
        <div class="tab-pane fade show active" id="dia" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-semibold">Seleccione un día:</h5>
                    <form action="../controlador/ReporteControlador.php" method="POST">
                        <input type="hidden" name="tipo" value="diario">

                        <div class="mb-3">
                            <input type="date" name="fecha" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Generar reporte diario</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- REPORTES SEMANALES -->
        <div class="tab-pane fade" id="semana" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-semibold">Seleccione el rango de fechas:</h5>
                    <form action="../controlador/ReporteControlador.php" method="POST">
                        <input type="hidden" name="tipo" value="semanal">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Desde:</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label>Hasta:</label>
                                <input type="date" name="fecha_fin" class="form-control" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Generar reporte semanal</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- REPORTES MENSUALES -->
        <div class="tab-pane fade" id="mes" role="tabpanel">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-semibold">Seleccione el mes:</h5>
                    <form action="../controlador/ReporteControlador.php" method="POST">
                        <input type="hidden" name="tipo" value="mensual">

                        <div class="mb-3">
                            <select name="mes" class="form-control" required>
                                <option disabled selected>-- Seleccione un mes --</option>
                                <?php
                                for ($i = 1; $i <= 12; $i++) {
                                    $nombreMes = date("F", mktime(0, 0, 0, $i, 1));
                                    echo "<option value='$i'>$nombreMes</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-warning w-100">Generar reporte mensual</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- ÁREA DONDE SE VERÁ EL REPORTE -->
    <div class="card shadow mt-4">
        <div class="card-header bg-dark text-white fw-semibold">
            Resultado del reporte
        </div>

        <div class="card-body">
            <?php
            // Aquí se imprime lo que el controlador envía
            if (isset($_SESSION['reporte_html'])) {
                echo $_SESSION['reporte_html'];
                unset($_SESSION['reporte_html']);
            } else {
                echo "<p class='text-muted'>Aquí aparecerá el contenido del reporte generado.</p>";
            }
            ?>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

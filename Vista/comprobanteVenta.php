<?php
// Variables esperadas:
// $venta → datos generales de la venta
// $detalles → lista de productos vendidos
// $cliente → datos del cliente
// $empleado → datos del cajero

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Venta - PMirador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5 col-md-7">
    <div class="card shadow">
        
        <div class="card-header bg-warning text-dark fw-bold text-center">
            COMPROBANTE DE VENTA
        </div>

        <div class="card-body">

            <!-- INFORMACIÓN DEL CLIENTE Y CAJERO -->
            <h5 class="fw-bold mb-3">Información</h5>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>Cliente:</strong> <?= $cliente['nombre'] . " " . $cliente['apellido'] ?></p>
                    <p><strong>Documento:</strong> <?= $cliente['id'] ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Cajero:</strong> <?= $empleado['nombre'] ?></p>
                    <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
                </div>
            </div>

            <hr>

            <!-- DETALLE DE PRODUCTOS -->
            <h5 class="fw-bold mb-3">Productos Vendidos</h5>

            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio Unit.</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($detalles as $item): ?>
                    <tr>
                        <td><?= $item['nombre'] ?></td>
                        <td><?= $item['cantidad'] ?></td>
                        <td>$<?= number_format($item['precio_unitario'], 0, ',', '.') ?></td>
                        <td>$<?= number_format($item['precio_total'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="text-end">
                <h4><strong>Total Venta:</strong> $<?= number_format($venta['total'], 0, ',', '.') ?></h4>
            </div>

            <hr>

            <!-- BOTONES DE ACCIONES -->
            <div class="d-flex justify-content-between mt-4">

                <form action="index.php?accion=exportarComprobantePDF" method="POST" target="_blank">
                    <input type="hidden" name="idVenta" value="<?= $venta['idVenta'] ?>">
                    <button class="btn btn-danger">Descargar PDF</button>
                </form>

                <form action="index.php?accion=enviarComprobanteEmail" method="POST">
                    <input type="hidden" name="idVenta" value="<?= $venta['idVenta'] ?>">
                    <input type="email" name="correo" class="form-control form-control-sm d-inline-block w-auto" placeholder="Correo del cliente" required>
                    <button class="btn btn-primary btn-sm">Enviar por Email</button>
                </form>

                <button onclick="window.print()" class="btn btn-dark">Imprimir</button>
            </div>

        </div>

    </div>
</div>

</body>
</html>

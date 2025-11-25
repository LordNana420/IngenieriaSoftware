<?php
require_once __DIR__ . "/../Controlador/MercanciaControlador.php";
$controlador = new MercanciaControlador();
$mercancias = $controlador->obtenerTodos();

// manejar mensajes flash
session_start();
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
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

        <?php if ($flash): ?>
            <div class="alert alert-info"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <h2 class="mb-4">Registro de Insumos al Inventario</h2>

        <!-- FORMULARIO REGISTRO -->
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

                    <button type="submit" class="btn btn-success">Registrar Insumo</button>
                </form>
            </div>
        </div>

        <!-- TABLA DE MERCANCIAS -->
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
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($mercancias as $m): ?>
                    <?php
                        $id = $m['idMercancia'];
                        $nombre = $m['Nombre'];
                        $cantidad = $m['Cantidad_Mercancia'];
                        $stockMin = $m['Stock_Minimo'];
                        $stockMax = $m['Stock_Maximo'];
                        $precio = $m['Precio_Unitario'];
                        $fechaIng = $m['Fecha_Ingreso'];
                        $fechaVenc = $m['Fecha_vencimiento'];
                    ?>
                    <tr>
                        <td><?= $id ?></td>
                        <td><?= htmlspecialchars($nombre) ?></td>
                        <td><?= $cantidad ?></td>
                        <td><?= $stockMin ?></td>
                        <td><?= $stockMax ?></td>
                        <td><?= $precio ?></td>
                        <td><?= $fechaIng ?></td>
                        <td><?= $fechaVenc ?></td>

                        <td class="text-center">

                            <!-- Botón editar -->
                            <button class="btn btn-primary btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEditar<?= $id ?>">
                                Editar
                            </button>

                            <!-- Botón eliminar -->
                            <form action="../Controlador/MercanciaControlador.php" method="POST" class="d-inline">
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Eliminar este insumo?');">
                                    Eliminar
                                </button>
                            </form>

                        </td>
                    </tr>

                    <!-- MODAL EDITAR -->
                    <div class="modal fade" id="modalEditar<?= $id ?>" tabindex="-1">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                          <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">Editar Insumo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>

                          <form action="../Controlador/MercanciaControlador.php" method="POST">
                              <input type="hidden" name="accion" value="actualizar">
                              <input type="hidden" name="id" value="<?= $id ?>">

                              <div class="modal-body">

                                  <label>Nombre</label>
                                  <input type="text" name="nombre" class="form-control mb-2" value="<?= $nombre ?>" required>

                                  <label>Cantidad</label>
                                  <input type="number" name="cantidad" class="form-control mb-2" value="<?= $cantidad ?>" required>

                                  <label>Stock Mínimo</label>
                                  <input type="number" name="stock_minimo" class="form-control mb-2" value="<?= $stockMin ?>" required>

                                  <label>Stock Máximo</label>
                                  <input type="number" name="stock_maximo" class="form-control mb-2" value="<?= $stockMax ?>" required>

                                  <label>Precio Unitario</label>
                                  <input type="number" name="precio_unitario" class="form-control mb-2" value="<?= $precio ?>" required>

                                  <label>Fecha de Ingreso</label>
                                  <input type="date" name="fecha_ingreso" class="form-control mb-2" value="<?= $fechaIng ?>" required>

                                  <label>Fecha de Vencimiento</label>
                                  <input type="date" name="fecha_vencimiento" class="form-control mb-2" value="<?= $fechaVenc ?>">
                              </div>

                              <div class="modal-footer">
                                  <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                  <button class="btn btn-primary" type="submit">Guardar cambios</button>
                              </div>
                          </form>

                        </div>
                      </div>
                    </div>
                <?php endforeach; ?>
            </tbody>

        </table>

    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

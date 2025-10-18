<?php
require_once("../Controlador/MercanciaControlador.php");
$controlador = new MercanciaControlador();
$alertas = $controlador->obtenerAlertasStock();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Inventario Panadería Dulce Hogar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">
  <nav class="navbar navbar-expand-lg navbar-dark bg-warning py-3 shadow">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold fs-4" href="#"><i class="bi bi-cupcake"></i> Panadería P.mirador</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
        aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarMenu">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link text-dark fw-semibold" href="#"><i class="bi bi-person-circle"></i> Editar mis datos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark fw-semibold" href="#"><i class="bi bi-gear"></i> Configuración</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-dark fw-semibold" href="#"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="fw-bold text-brown"><i class="bi bi-basket"></i> Inventario de Insumos</h2>
      <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistro">
        <i class="bi bi-plus-circle"></i> Registrar Insumo
      </button>
    </div>

    <table class="table table-hover table-bordered align-middle">
      <thead class="table-warning">
        <tr>
          <th><i class="bi bi-box"></i> Nombre</th>
          <th><i class="bi bi-person-vcard"></i> Proveedor</th>
          <th><i class="bi bi-bag"></i> Cantidad</th>
          <th><i class="bi bi-calendar-date"></i> Fecha de ingreso</th>
          <th><i class="bi bi-toggle-on"></i> Estado</th>
          <th><i class="bi bi-gear"></i> Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Harina de trigo</td>
          <td>Molinos San Juan</td>
          <td>200 kg</td>
          <td>2025-10-10</td>
          <td><span class="badge bg-success">Activo</span></td>
          <td>
            <button class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Ver</button>
            <button class="btn btn-sm btn-warning text-dark">inactivo</button>
          </td>
        </tr>
        <tr>
          <td>Levadura</td>
          <td>PanPro</td>
          <td>30 kg</td>
          <td>2025-09-28</td>
          <td><span class="badge bg-danger">inactivo</span></td>
          <td>
            <button class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Ver</button>
            <button class="btn btn-sm btn-success">Activo</button>
          </td>
        </tr>
      </tbody>
    </table>




  </div>

  <!-- Modal Registrar Insumo -->
  <div class="modal fade" id="modalRegistro" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-warning">
          <h5 class="modal-title fw-bold"><i class="bi bi-journal-plus"></i> Registrar nuevo insumo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form>
            <div class="mb-3">
              <label class="form-label">Nombre del insumo</label>
              <input type="text" class="form-control" placeholder="Ej: Harina, Azúcar, Chocolate">
            </div>
            <div class="mb-3">
              <label class="form-label">Proveedor</label>
              <input type="text" class="form-control" placeholder="Ej: Molinos San Juan">
            </div>
            <div class="mb-3">
              <label class="form-label">Cantidad</label>
              <input type="number" class="form-control" placeholder="Ej: 50">
            </div>
            <div class="mb-3">
              <label class="form-label">Fecha de ingreso</label>
              <input type="date" class="form-control">
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i>
            Cancelar</button>
          <button type="button" class="btn btn-warning"><i class="bi bi-save"></i> Guardar</button>
        </div>
      </div>
    </div>
  </div>
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
              <?php
              if (strcasecmp(htmlspecialchars($m->getCausa()), 'Stock muy bajo')) {
                echo "<td><div class='alert alert-danger mt-2' role='alert'>
      <i class='bi bi-exclamation-octagon-fill'></i> <strong>Alerta de exceso de producto</strong>
    </div></td>";
              } else {

                echo "<td class= ><div class='alert alert-warning mt-4' role='alert'>
      <i class='bi bi-exclamation-triangle-fill'></i> <strong>Alerta de stock bajo</strong>
    </div></td>";
              } ?>

            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="3" class="text-center">No hay alertas de stock.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
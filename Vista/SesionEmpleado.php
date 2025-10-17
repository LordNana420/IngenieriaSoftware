<?php
require_once("../Controlador/ClienteControlador.php");
$controlador = new ClienteControlador();
$clientes = $controlador->obtenerClientes(); 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consulta de Clientes - Panadería P.mirador</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-warning py-3 shadow">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold fs-4" href="#"><i class="bi bi-cupcake"></i> Panadería P.mirador</a>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link text-dark fw-semibold" href="#"><i class="bi bi-box-arrow-right"></i> Cerrar sesión</a></li>
      </ul>
    </div>
  </nav>

  <div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="fw-bold"><i class="bi bi-search"></i> Consulta de Clientes</h2>
      <button class="btn btn-success"><i class="bi bi-person-plus"></i> Nuevo Cliente</button>
    </div>

    <div class="input-group mb-3">
      <span class="input-group-text bg-warning"><i class="bi bi-funnel"></i></span>
      <input type="text" class="form-control" placeholder="Buscar por nombre, documento o correo...">
      <button class="btn btn-warning"><i class="bi bi-search"></i> Buscar</button>
    </div>
<table class="table table-hover table-bordered align-middle">
  <thead class="table-warning">
    <tr>
      <th><i class="bi bi-hash"></i> ID</th>
      <th><i class="bi bi-person-badge"></i> Nombre</th>
      <th><i class="bi bi-person-badge-fill"></i> Apellido</th>
      <th><i class="bi bi-telephone"></i> Teléfono</th>
      <th><i class="bi bi-gear"></i> Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if (!empty($clientes)): ?>
        <?php foreach ($clientes as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c->getId()) ?></td>
                <td><?= htmlspecialchars($c->getNombre()) ?></td>
                <td><?= htmlspecialchars($c->getApellido()) ?></td>
                <td><?= htmlspecialchars($c->getTelefono()) ?></td>
                <td>
                    <button class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Ver</button>
                    <button class="btn btn-sm btn-warning text-dark"><i class="bi bi-pencil"></i> Editar</button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr><td colspan="5" class="text-center">No hay clientes registrados.</td></tr>
    <?php endif; ?>
</tbody>

</table>

    <div class="alert alert-success mt-3 d-none" id="alertaConsulta">
      <i class="bi bi-check-circle-fill"></i> Consulta realizada con éxito.
    </div>
  </div>
</body>
</html>
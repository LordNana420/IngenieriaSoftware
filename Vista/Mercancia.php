<?php
// Archivo: /opt/lampp/htdocs/IngenieriaSoftware/Vista/Mercancia.php
// Vista de mercancía con Bootstrap. Ajusta rutas/propiedades según tu proyecto.

require_once __DIR__ . '/../Modelo/MercanciaDAO.php';

$dao = new MercanciaDAO();

// Intentar recuperar lista con nombres de método comunes
$mercancias = [];
foreach (['getAll', 'listar', 'findAll'] as $method) {
  if (method_exists($dao, $method) && is_callable([$dao, $method])) {
    // llamada dinámica para evitar errores de método undefined en análisis estático
    $mercancias = $dao->{$method}();
    break;
  }
}

// Función auxiliar para obtener propiedades desde array u object y distintos nombres/getters
function val($item, $keys)
{
  foreach ((array)$keys as $k) {
    // array access
    if (is_array($item) && array_key_exists($k, $item)) return $item[$k];
    // object public property
    if (is_object($item) && isset($item->$k)) return $item->$k;
    // getter method
    $getter = 'get' . ucfirst($k);
    if (is_object($item) && method_exists($item, $getter)) return $item->$getter();
  }
  return '';
}

function h($s)
{
  return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

?>
<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <title>Mercancía</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1 class="h3">Listado de Mercancías</h1>
      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#mercanciaModal" onclick="openCreate()">Añadir Mercancía</button>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th class="text-end">Precio</th>
                <th class="text-end">Cantidad</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($mercancias)): ?>
                <?php foreach ($mercancias as $m): ?>
                  <?php
                  $id = val($m, ['id', 'ID', 'codigo', 'getId']);
                  $nombre = val($m, ['nombre', 'name', 'getNombre']);
                  $descripcion = val($m, ['descripcion', 'descripcion', 'getDescripcion', 'desc']);
                  $precio = val($m, ['precio', 'price', 'getPrecio']);
                  $cantidad = val($m, ['cantidad', 'stock', 'getCantidad']);
                  ?>
                  <tr>
                    <td><?php echo h($id); ?></td>
                    <td><?php echo h($nombre); ?></td>
                    <td><?php echo h($descripcion); ?></td>
                    <td class="text-end"><?php echo h($precio); ?></td>
                    <td class="text-end"><?php echo h($cantidad); ?></td>
                    <td class="text-center">
                      <div class="btn-group btn-group-sm" role="group">
                        <button class="btn btn-outline-secondary" onclick="openEdit(<?php echo json_encode([
                                                                                      'id' => $id,
                                                                                      'nombre' => $nombre,
                                                                                      'descripcion' => $descripcion,
                                                                                      'precio' => $precio,
                                                                                      'cantidad' => $cantidad
                                                                                    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>)" data-bs-toggle="modal" data-bs-target="#mercanciaModal">Editar</button>
                        <form method="post" action="../Controlador/MercanciaControlador.php" style="display:inline;">
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="id" value="<?php echo h($id); ?>">
                          <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Eliminar mercancía ID <?php echo h($id); ?>?')">Eliminar</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" class="text-center py-4">No hay mercancías registradas.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <p class="text-muted small mt-3">Ajusta los nombres de campos y rutas del controlador según tu implementación.</p>
  </div>

  <!-- Modal para crear -->
  <div class="modal fade" id="mercanciaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <form id="mercanciaForm" method="POST" action="../Controlador/MercanciaControlador.php" class="modal-content">

        <!-- ESTA ES LA ACCIÓN REAL QUE YA TIENES -->
        <input type="hidden" name="accion" value="registrar">

        <div class="modal-header">
          <h5 class="modal-title">Nueva Mercancía</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
          </div>

          <div class="row">
            <div class="col">
              <label class="form-label">Precio</label>
              <input type="number" name="precio_unitario" class="form-control" required>
            </div>
            <div class="col">
              <label class="form-label">Cantidad</label>
              <input type="number" name="cantidad" class="form-control" required>
            </div>
          </div>

          <div class="row mt-3">
            <div class="col">
              <label class="form-label">Stock mín.</label>
              <input type="number" name="stock_minimo" class="form-control" required>
            </div>
            <div class="col">
              <label class="form-label">Stock máx.</label>
              <input type="number" name="stock_maximo" class="form-control" required>
            </div>
          </div>

          <div class="mt-3">
            <label class="form-label">Fecha ingreso</label>
            <input type="date" name="fecha_ingreso" class="form-control" value="<?= date('Y-m-d') ?>">
          </div>

          <div class="mt-3">
            <label class="form-label">Fecha vencimiento</label>
            <input type="date" name="fecha_vencimiento" class="form-control">
          </div>

          <div class="mt-3">
            <label class="form-label">Responsable</label>
            <input type="text" name="responsable" class="form-control" required>
          </div>

        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>

      </form>
    </div>
  </div>

  <!-- Bootstrap JS (popper incluido) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function openCreate() {
      document.getElementById('modalTitle').textContent = 'Nueva Mercancía';
      document.getElementById('formAction').value = 'save';
      document.getElementById('formId').value = '';
      document.getElementById('nombre').value = '';
      document.getElementById('descripcion').value = '';
      document.getElementById('precio').value = '';
      document.getElementById('cantidad').value = '';
    }

    function openEdit(data) {
      // data es un objeto JSON pasado desde PHP
      document.getElementById('modalTitle').textContent = 'Editar Mercancía';
      document.getElementById('formAction').value = 'update';
      document.getElementById('formId').value = data.id ?? '';
      document.getElementById('nombre').value = data.nombre ?? '';
      document.getElementById('descripcion').value = data.descripcion ?? '';
      document.getElementById('precio').value = data.precio ?? '';
      document.getElementById('cantidad').value = data.cantidad ?? '';
    }
  </script>
</body>

</html>
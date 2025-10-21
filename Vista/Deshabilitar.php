<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mercancías</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <!-- ENCABEZADO -->
    <div class="text-center mb-4">
        <h1 class="fw-bold text-primary">📦 Gestión de Mercancías</h1>
        <p class="text-muted">Administra el estado de tu inventario</p>
        <a href="index.php" class="btn btn-outline-secondary mt-3">⬅️ Volver</a>
    </div>

    <!-- ALERTAS -->
    <div id="alertContainer"></div>

    <!-- PESTAÑAS -->
    <ul class="nav nav-tabs mb-3" id="mercanciaTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="activos-tab" data-bs-toggle="tab" data-bs-target="#activos" type="button" role="tab">
                Activas (<?= count($mercancias_activas) ?>)
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="deshabilitadas-tab" data-bs-toggle="tab" data-bs-target="#deshabilitadas" type="button" role="tab">
                Deshabilitadas (<?= count($mercancias_deshabilitadas) ?>)
            </button>
        </li>
    </ul>

    <!-- CONTENIDO DE LAS PESTAÑAS -->
    <div class="tab-content" id="mercanciaTabsContent">

        <!-- MERCANCÍAS ACTIVAS -->
        <div class="tab-pane fade show active" id="activos" role="tabpanel">
            <?php if (count($mercancias_activas) > 0): ?>
                <div class="table-responsive shadow-sm bg-white p-3 rounded">
                    <table class="table table-hover align-middle">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($mercancias_activas as $m): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($m['idMercancia']) ?></td>
                                <td><strong><?= htmlspecialchars($m['Nombre']) ?></strong></td>
                                <td><?= htmlspecialchars($m['Tipo']) ?></td>
                                <td><?= htmlspecialchars($m['Ubicacion']) ?></td>
                                <td><span class="badge bg-success"><?= htmlspecialchars($m['Estado']) ?></span></td>
                                <td>
                                    <button class="btn btn-info btn-sm text-white" onclick="verDetalles(<?= $m['idMercancia'] ?>)">👁️ Ver</button>
                                    <button class="btn btn-danger btn-sm" onclick="abrirModalDeshabilitar(<?= $m['idMercancia'] ?>, '<?= htmlspecialchars($m['Nombre']) ?>')">❌ Deshabilitar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center mt-3">No hay mercancías activas registradas.</div>
            <?php endif; ?>
        </div>

        <!-- MERCANCÍAS DESHABILITADAS -->
        <div class="tab-pane fade" id="deshabilitadas" role="tabpanel">
            <?php if (count($mercancias_deshabilitadas) > 0): ?>
                <div class="table-responsive shadow-sm bg-white p-3 rounded">
                    <table class="table table-hover align-middle">
                        <thead class="table-secondary">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Ubicación</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($mercancias_deshabilitadas as $m): ?>
                            <tr>
                                <td>#<?= htmlspecialchars($m['idMercancia']) ?></td>
                                <td><strong><?= htmlspecialchars($m['Nombre']) ?></strong></td>
                                <td><?= htmlspecialchars($m['Tipo']) ?></td>
                                <td><?= htmlspecialchars($m['Ubicacion']) ?></td>
                                <td><span class="badge bg-danger">Deshabilitado</span></td>
                                <td>
                                    <button class="btn btn-info btn-sm text-white" onclick="verDetalles(<?= $m['idMercancia'] ?>)">👁️ Ver</button>
                                    <button class="btn btn-success btn-sm" onclick="habilitarMercancia(<?= $m['idMercancia'] ?>, '<?= htmlspecialchars($m['Nombre']) ?>')">✅ Habilitar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-success text-center mt-3">No hay mercancías deshabilitadas actualmente.</div>
            <?php endif; ?>
        </div>

    </div>
</div>

<!-- MODAL DESHABILITAR -->
<div class="modal fade" id="modalDeshabilitar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">🚫 Deshabilitar Mercancía</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea deshabilitar la mercancía <strong id="nombreMercanciaModal"></strong>?</p>
                <div class="mb-3">
                    <label for="motivo" class="form-label">Motivo (opcional):</label>
                    <textarea id="motivo" class="form-control"></textarea>
                </div>
                <input type="hidden" id="idMercanciaActual">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" onclick="confirmarDeshabilitar()">Deshabilitar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL DETALLES -->
<div class="modal fade" id="modalDetalles" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">📋 Detalles de Mercancía</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detallesContent">
                Cargando detalles...
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function abrirModalDeshabilitar(id, nombre) {
    document.getElementById('idMercanciaActual').value = id;
    document.getElementById('nombreMercanciaModal').textContent = nombre;
    new bootstrap.Modal(document.getElementById('modalDeshabilitar')).show();
}

function confirmarDeshabilitar() {
    const id = document.getElementById('idMercanciaActual').value;
    const motivo = document.getElementById('motivo').value;
    // Aquí enviarías el fetch POST
    alert(`Mercancía #${id} deshabilitada (motivo: ${motivo || 'N/A'})`);
    bootstrap.Modal.getInstance(document.getElementById('modalDeshabilitar')).hide();
}

function verDetalles(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetalles'));
    document.getElementById('detallesContent').innerHTML = '<p>Cargando...</p>';
    modal.show();

    fetch(`?action=detalles&id=${id}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const m = data.data;
                document.getElementById('detallesContent').innerHTML = `
                    <ul class="list-group">
                        <li class="list-group-item"><strong>ID:</strong> #${m.idMercancia}</li>
                        <li class="list-group-item"><strong>Nombre:</strong> ${m.Nombre}</li>
                        <li class="list-group-item"><strong>Tipo:</strong> ${m.Tipo}</li>
                        <li class="list-group-item"><strong>Ubicación:</strong> ${m.Ubicacion}</li>
                        <li class="list-group-item"><strong>Estado:</strong> ${m.Estado}</li>
                        <li class="list-group-item"><strong>Cantidad:</strong> ${m.Cantidad_Total}</li>
                    </ul>`;
            } else {
                document.getElementById('detallesContent').innerHTML = '<p>Error al cargar los detalles.</p>';
            }
        });
}
</script>

</body>
</html>

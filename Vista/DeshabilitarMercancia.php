<?php
// inventario/index.php
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario de Insumos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="text-center mb-4">📦 Inventario de Insumos</h2>

    <!-- Insumos Activos -->
    <h4>Insumos Activos</h4>
    <button class="btn btn-primary mb-2" id="deshabilitar-lote-btn">Deshabilitar Seleccionados</button>
    <div class="table-responsive shadow-sm">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-primary">
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Stock Mínimo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="mercancias-activas">
                <?php foreach ($mercancias_activas as $m): ?>
                    <tr data-id="<?= $m['idMercancia'] ?>">
                        <td><input type="checkbox" class="chk-mercancia"></td>
                        <td><?= htmlspecialchars($m['Nombre']) ?></td>
                        <td><?= $m['Cantidad_Mercancia'] ?></td>
                        <td><?= $m['Stock_Minimo'] ?></td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="deshabilitar(<?= $m['idMercancia'] ?>)">Deshabilitar</button>
                            <button class="btn btn-info btn-sm text-white" onclick="verDetalles(<?= $m['idMercancia'] ?>)">👁️ Ver</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Insumos Deshabilitados -->
    <h4 class="mt-4">Insumos Deshabilitados</h4>
    <div class="table-responsive shadow-sm">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Nombre</th>
                    <th>Cantidad</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="mercancias-deshabilitadas">
                <?php foreach ($mercancias_deshabilitadas as $m): ?>
                    <tr data-id="<?= $m['idMercancia'] ?>">
                        <td><?= htmlspecialchars($m['Nombre']) ?></td>
                        <td><?= $m['Cantidad_Mercancia'] ?></td>
                        <td><span class="badge bg-danger">Deshabilitado</span></td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="habilitar(<?= $m['idMercancia'] ?>)">✅ Habilitar</button>
                            <button class="btn btn-info btn-sm text-white" onclick="verDetalles(<?= $m['idMercancia'] ?>)">👁️ Ver</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="../provisionalIndex.php" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Volver al Menú
        </a>
    </div>
</div>

<script>
    // Seleccionar/desmarcar todos los checkboxes
    document.getElementById('select-all').addEventListener('change', function() {
        let checked = this.checked;
        document.querySelectorAll('.chk-mercancia').forEach(chk => chk.checked = checked);
    });

    function deshabilitar(id) {
        fetch('?action=deshabilitar', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'idMercancia=' + id
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) location.reload();
        });
    }

    function habilitar(id) {
        fetch('?action=habilitar', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'idMercancia=' + id
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) location.reload();
        });
    }

    function verDetalles(id) {
        fetch('?action=detalles&id=' + id)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(JSON.stringify(data.data, null, 2));
            } else {
                alert(data.message);
            }
        });
    }

    document.getElementById('deshabilitar-lote-btn').addEventListener('click', function() {
        let selected = Array.from(document.querySelectorAll('.chk-mercancia:checked'))
                            .map(chk => chk.closest('tr').dataset.id);

        if (selected.length === 0) {
            alert('Seleccione al menos un insumo para deshabilitar.');
            return;
        }

        fetch('?action=deshabilitar-lote', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'ids[]=' + selected.join('&ids[]=')
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);
            if (data.success) location.reload();
        });
    });
</script>
</body>
</html>

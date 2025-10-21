<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Mercancías - Inventario</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 30px;
            text-align: center;
        }

        .header h1 {
            color: #667eea;
            font-size: 2.5em;
            margin-bottom: 10px;
        }

        .header p {
            color: #666;
            font-size: 1.1em;
        }

        .tabs {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .tab-btn {
            flex: 1;
            padding: 15px 30px;
            background: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .tab-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .tab-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .section {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }

        .section-header h2 {
            color: #333;
            font-size: 1.8em;
        }

        .badge {
            background: #667eea;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
        }

        .search-bar {
            margin-bottom: 25px;
            position: relative;
        }

        .search-bar input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 0.95em;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr {
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: #f8f9ff;
            transform: scale(1.01);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }

        .status-disponible {
            background: #d4edda;
            color: #155724;
        }

        .status-deshabilitado {
            background: #f8d7da;
            color: #721c24;
        }

        .status-agotado {
            background: #fff3cd;
            color: #856404;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-deshabilitar {
            background: #dc3545;
            color: white;
        }

        .btn-deshabilitar:hover {
            background: #c82333;
        }

        .btn-habilitar {
            background: #28a745;
            color: white;
        }

        .btn-habilitar:hover {
            background: #218838;
        }

        .btn-detalles {
            background: #17a2b8;
            color: white;
            margin-right: 5px;
        }

        .btn-detalles:hover {
            background: #138496;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #999;
        }

        .empty-state-icon {
            font-size: 4em;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 15px;
            max-width: 500px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }

        .modal-header h3 {
            color: #333;
            font-size: 1.5em;
        }

        .modal-body {
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1em;
            transition: all 0.3s ease;
        }

        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #667eea;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-cancelar {
            background: #6c757d;
            color: white;
        }

        .btn-cancelar:hover {
            background: #5a6268;
        }

        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        @media (max-width: 768px) {
            .header h1 {
                font-size: 1.8em;
            }

            .tabs {
                flex-direction: column;
            }

            table {
                font-size: 0.9em;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📦 Gestión de Mercancías</h1>
            <p>Administra el estado de tu inventario</p>
        </div>

        <!-- Alert Container -->
        <div id="alertContainer"></div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-btn active" onclick="switchTab('activas')">
                Mercancías Activas
            </button>
            <button class="tab-btn" onclick="switchTab('deshabilitadas')">
                Mercancías Deshabilitadas
            </button>
        </div>

        <!-- Tab: Mercancías Activas -->
        <div id="tab-activas" class="tab-content active">
            <div class="section">
                <div class="section-header">
                    <h2>Mercancías Activas</h2>
                    <span class="badge" id="badge-activas">
                        <?php echo count($mercancias_activas); ?> items
                    </span>
                </div>

                <div class="search-bar">
                    <input type="text" id="searchActivas" placeholder="Buscar mercancía por nombre, tipo o ubicación..." onkeyup="filtrarTabla('tableActivas', 'searchActivas')">
                    <span class="search-icon">🔍</span>
                </div>

                <div class="table-container">
                    <?php if (count($mercancias_activas) > 0): ?>
                        <table id="tableActivas">
                            <thead>
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
                                <?php foreach ($mercancias_activas as $mercancia): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($mercancia['idMercancia']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($mercancia['Nombre']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($mercancia['Tipo']); ?></td>
                                    <td><?php echo htmlspecialchars($mercancia['Ubicacion']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo strtolower($mercancia['Estado']); ?>">
                                            <?php echo htmlspecialchars($mercancia['Estado']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-detalles" onclick="verDetalles(<?php echo $mercancia['idMercancia']; ?>)">
                                            👁️ Ver
                                        </button>
                                        <button class="btn btn-deshabilitar" onclick="abrirModalDeshabilitar(<?php echo $mercancia['idMercancia']; ?>, '<?php echo htmlspecialchars($mercancia['Nombre']); ?>')">
                                            ❌ Deshabilitar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📭</div>
                            <h3>No hay mercancías activas</h3>
                            <p>Todas las mercancías están deshabilitadas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Tab: Mercancías Deshabilitadas -->
        <div id="tab-deshabilitadas" class="tab-content">
            <div class="section">
                <div class="section-header">
                    <h2>Mercancías Deshabilitadas</h2>
                    <span class="badge" id="badge-deshabilitadas">
                        <?php echo count($mercancias_deshabilitadas); ?> items
                    </span>
                </div>

                <div class="search-bar">
                    <input type="text" id="searchDeshabilitadas" placeholder="Buscar mercancía deshabilitada..." onkeyup="filtrarTabla('tableDeshabilitadas', 'searchDeshabilitadas')">
                    <span class="search-icon">🔍</span>
                </div>

                <div class="table-container">
                    <?php if (count($mercancias_deshabilitadas) > 0): ?>
                        <table id="tableDeshabilitadas">
                            <thead>
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
                                <?php foreach ($mercancias_deshabilitadas as $mercancia): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($mercancia['idMercancia']); ?></td>
                                    <td><strong><?php echo htmlspecialchars($mercancia['Nombre']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($mercancia['Tipo']); ?></td>
                                    <td><?php echo htmlspecialchars($mercancia['Ubicacion']); ?></td>
                                    <td>
                                        <span class="status-badge status-deshabilitado">
                                            Deshabilitado
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-detalles" onclick="verDetalles(<?php echo $mercancia['idMercancia']; ?>)">
                                            👁️ Ver
                                        </button>
                                        <button class="btn btn-habilitar" onclick="habilitarMercancia(<?php echo $mercancia['idMercancia']; ?>, '<?php echo htmlspecialchars($mercancia['Nombre']); ?>')">
                                            ✅ Habilitar
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">✨</div>
                            <h3>¡Excelente!</h3>
                            <p>No hay mercancías deshabilitadas</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deshabilitar -->
    <div id="modalDeshabilitar" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>🚫 Deshabilitar Mercancía</h3>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea deshabilitar la mercancía?</p>
                <p><strong id="nombreMercanciaModal"></strong></p>
                
                <div class="form-group">
                    <label for="motivo">Motivo de deshabilitación (opcional):</label>
                    <textarea id="motivo" placeholder="Ingrese el motivo por el cual se deshabilita esta mercancía..."></textarea>
                </div>

                <input type="hidden" id="idMercanciaActual">
            </div>
            <div class="modal-footer">
                <button class="btn btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button class="btn btn-deshabilitar" onclick="confirmarDeshabilitar()">Deshabilitar</button>
            </div>
        </div>
    </div>

    <!-- Modal Detalles -->
    <div id="modalDetalles" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📋 Detalles de Mercancía</h3>
            </div>
            <div class="modal-body" id="detallesContent">
                <p>Cargando detalles...</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-cancelar" onclick="cerrarModalDetalles()">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        // Cambiar entre tabs
        function switchTab(tab) {
            // Ocultar todos los tabs
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            
            // Remover clase active de todos los botones
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Mostrar tab seleccionado
            document.getElementById('tab-' + tab).classList.add('active');
            event.target.classList.add('active');
        }

        // Filtrar tabla
        function filtrarTabla(tableId, searchId) {
            const input = document.getElementById(searchId);
            const filter = input.value.toUpperCase();
            const table = document.getElementById(tableId);
            const tr = table.getElementsByTagName('tr');

            for (let i = 1; i < tr.length; i++) {
                let td = tr[i].getElementsByTagName('td');
                let found = false;
                
                for (let j = 0; j < td.length; j++) {
                    if (td[j]) {
                        if (td[j].textContent.toUpperCase().indexOf(filter) > -1) {
                            found = true;
                            break;
                        }
                    }
                }
                
                tr[i].style.display = found ? '' : 'none';
            }
        }

        // Abrir modal deshabilitar
        function abrirModalDeshabilitar(id, nombre) {
            document.getElementById('idMercanciaActual').value = id;
            document.getElementById('nombreMercanciaModal').textContent = nombre;
            document.getElementById('motivo').value = '';
            document.getElementById('modalDeshabilitar').classList.add('active');
        }

        // Cerrar modal
        function cerrarModal() {
            document.getElementById('modalDeshabilitar').classList.remove('active');
        }

        // Confirmar deshabilitar
        function confirmarDeshabilitar() {
            const id = document.getElementById('idMercanciaActual').value;
            const motivo = document.getElementById('motivo').value;
            
            const formData = new FormData();
            formData.append('idMercancia', id);
            formData.append('motivo', motivo);
            
            fetch('?action=deshabilitar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                cerrarModal();
                
                if (data.success) {
                    mostrarAlerta('Mercancía deshabilitada correctamente', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta(data.message, 'error');
                }
            })
            .catch(error => {
                cerrarModal();
                mostrarAlerta('Error al procesar la solicitud', 'error');
            });
        }

        // Habilitar mercancía
        function habilitarMercancia(id, nombre) {
            if (!confirm(`¿Está seguro que desea habilitar la mercancía "${nombre}"?`)) {
                return;
            }
            
            const formData = new FormData();
            formData.append('idMercancia', id);
            
            fetch('?action=habilitar', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarAlerta('Mercancía habilitada correctamente', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    mostrarAlerta(data.message, 'error');
                }
            })
            .catch(error => {
                mostrarAlerta('Error al procesar la solicitud', 'error');
            });
        }

        // Ver detalles
        function verDetalles(id) {
            document.getElementById('modalDetalles').classList.add('active');
            document.getElementById('detallesContent').innerHTML = '<p>Cargando detalles...</p>';
            
            fetch(`?action=detalles&id=${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const m = data.data;
                    document.getElementById('detallesContent').innerHTML = `
                        <div class="form-group">
                            <label>ID:</label>
                            <input type="text" value="#${m.idMercancia}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Nombre:</label>
                            <input type="text" value="${m.Nombre}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tipo:</label>
                            <input type="text" value="${m.Tipo}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Ubicación:</label>
                            <input type="text" value="${m.Ubicacion}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Estado:</label>
                            <input type="text" value="${m.Estado}" readonly>
                        </div>
                        <div class="form-group">
                            <label>Cantidad Total:</label>
                            <input type="text" value="${m.Cantidad_Total}" readonly>
                        </div>
                    `;
                } else {
                    document.getElementById('detallesContent').innerHTML = '<p>Error al cargar los detalles</p>';
                }
            })
            .catch(error => {
                document.getElementById('detallesContent').innerHTML = '<p>Error al cargar los detalles</p>';
            });
        }

        // Cerrar modal detalles
        function cerrarModalDetalles() {
            document.getElementById('modalDetalles').classList.remove('active');
        }

        // Cerrar modales al hacer click fuera
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.classList.remove('active');
            }
        }

        // Mostrar alertas
        function mostrarAlerta(mensaje, tipo) {
            const alertContainer = document.getElementById('alertContainer');
            const alert = document.createElement('div');
            alert.className = `alert alert-${tipo}`;
            alert.textContent = mensaje;
            
            alertContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
    </script>
</body>
</html>
<?php
require_once __DIR__ . "/../Controlador/ClienteControlador.php";
require_once __DIR__ . "/../Modelo/ClienteDAO.php";

$controlador = new ClienteControlador();
$clientes = $controlador->obtenerClientes();

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-warning py-3 shadow">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold fs-4" href="#"><i class="bi bi-cupcake"></i> Panadería P.mirador</a>
        <ul class="navbar-nav ms-auto">
            
            <li class="nav-item">
                <a class="nav-link text-dark fw-semibold" href="#">
                    <i class="bi bi-box-arrow-right"></i> Cerrar sesión
                </a>
            </li>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold"><i class="bi bi-search"></i> Consulta de Clientes</h2>
        <button class="btn btn-success">
            <a href="?pid=Vista/registrarCliente.php" class="text-decoration-none text-light">
                <i class="bi bi-person-plus"></i> Nuevo Cliente
            </a>
        </button>
    </div>
    
    <div class="input-group mb-3" id="filtro">
        <span class="input-group-text bg-warning"><i class="bi bi-funnel"></i></span>
        <input type="number" class="form-control" id="filtroDoc" placeholder="Buscar por documento">
        <input type="text" class="form-control" id="filtroNom" placeholder="Buscar por nombre">
        <input type="number" class="form-control" id="filtroTel" placeholder="Buscar por Telefono">
        <button class="btn btn-warning"><i class="bi bi-search"></i> Buscar</button>
    </div>

    <div id="ClientesTable">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-warning">
                <tr>
                    <th><i class="bi bi-hash"></i> ID</th>
                    <th><i class="bi bi-person-badge"></i> Nombre</th>
                    <th><i class="bi bi-person-badge-fill"></i> Apellido</th>
                    <th><i class="bi bi-telephone"></i> Teléfono</th>
                    <th><i class="bi bi-telephone"></i> Estado </th>
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
                            <?php echo "<td><div id='estado" . $c->getId() . "'>" . (($c->getEstado() == 1) ? ("<div class ='bg-success rounded-5 text-light ps-2'><i class='fa-solid fa-check'></i> Habilitado</div></div></td>") : ("<div class ='bg-danger rounded-5 text-light ps-2'><i class='fa-solid fa-xmark'></i> Deshabilitado</div></div></td>")); ?>
                            <td>
                                <button class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Ver</button>
                                <a href="Vista/editarCliente.php?id=<?= $c->getId() ?>" class="btn btn-sm btn-warning text-dark">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No hay clientes registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="alert alert-success mt-3 d-none" id="alertaConsulta">
        <i class="bi bi-check-circle-fill"></i> Consulta realizada con éxito.
    </div>
</div>

<script>
$("#filtroDoc, #filtroNom, #filtroTel").on("keyup", function () {
    let d = $("#filtroDoc").val();
    let n = $("#filtroNom").val().replace(" ", "%20");
    let t = $("#filtroTel").val();
    filtro = $("#filtro").val().replace(" ", "%20");
    if (d.length >= 2 || n.length >= 3 || t.length >= 3) {
        let url = "?pid=Vista/buscarClienteAjax.php&d=" + d + "&n=" + n + "&t=" + t;
        $("#ClientesTable").load(url);
    } else {
        let url = "?pid=Vista/buscarClienteAjax.php&d=0&n=0&t=0";
        $("#ClientesTable").load(url);
    }
});
</script>
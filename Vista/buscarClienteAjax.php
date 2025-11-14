<?php

$doc = $_GET["d"];
$nom = $_GET["n"];
$tel = $_GET["t"];
$nom = str_replace("%20", " ", $nom);
$nombre_partes = explode(" ", trim($nom), 2);
$nom = $nombre_partes[0];
$ape = isset($nombre_partes[1]) ? $nombre_partes[1] : "0";
if ($doc == 0 && $nom == 0 && $tel == 0 && $ape == 0) {
    $cliente = new ClienteControlador();
    $clientes = $cliente->obtenerClientes();
} else {
    $cliente = new ClienteControlador();
    $clientes = $cliente->BuscarClientes($nom, $ape, $doc, $tel);
}
if (count($clientes) == 0) {
    echo "<div class='alert alert-warning' role='alert'>
                                    No hay registros
                                    </div>";
} else {
    ?>
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
                            <button class="btn btn-sm btn-warning text-dark"><i class="bi bi-pencil"></i> Editar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No hay clientes registrados.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
<?php } ?>
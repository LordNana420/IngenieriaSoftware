<?php
require_once __DIR__ . "/../Modelo/ClienteDAO.php";
$dao = new ClienteDAO();
$clientes = $dao->listarClientes();
?>
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
            <a class="nav-link text-dark fw-semibold" href="?pid=Vista/DeshabilitarCliente.php">
              <i class="bi bi-people-fill"></i> Clientes
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link text-dark fw-semibold" href="?salir=true">
              <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
          </li>

        </ul>
      </div>
    </div>
</nav>




<div class="container mt-5">
    <h2 class="text-center mb-4">👥 Lista de Clientes</h2>

    <?php if (empty($clientes)): ?>
        <div class="alert alert-warning text-center">
            No hay clientes registrados.
        </div>
    <?php else: ?>

        <div class="table-responsive shadow-sm">
            <table class="table table-striped table-bordered align-middle">
                <thead class="table-success">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Teléfono</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td><?= htmlspecialchars($c->getId()) ?></td>
                            <td><?= htmlspecialchars($c->getNombre()) ?></td>
                            <td><?= htmlspecialchars($c->getApellido()) ?></td>
                            <td><?= htmlspecialchars($c->getTelefono()) ?></td>

                            <td>
    <button class="btn cambiarEstadoBtn"
            data-id="<?= $c->getId() ?>"
            data-estado="<?= $c->getEstado() ?>"
            style="width:120px;">

        <?php if ($c->getEstado() == 1): ?>
            <span class="badge bg-success p-2">Activo</span>
        <?php else: ?>
            <span class="badge bg-danger p-2">Inactivo</span>
        <?php endif; ?>

    </button>
</td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <?php endif; ?>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function () {

    $(".cambiarEstadoBtn").click(function () {

        let id = $(this).data("id");
        let estado = $(this).data("estado");
        let nuevoEstado = estado == 1 ? 2 : 1;
        let boton = $(this);
$.ajax({
   url: "/IngenieriaSoftware/IngenieriaSoftware/Controlador/CambiarEstadoClienteAjax.php",
    type: "POST",
    data: {
        idCliente: id,
        estado: nuevoEstado
    },
    success: function (respuesta) {
    console.log("RESPUESTA:", respuesta); 

    let res = respuesta; 
    
    if (res.exito) {
       
        boton.data("estado", res.nuevo_estado); 
        
        if (res.nuevo_estado == 1) {
            boton.html('<span class="badge bg-success p-2">Activo</span>');
        } else {
            boton.html('<span class="badge bg-danger p-2">Inactivo</span>');
        }
    } else {
        alert("Error: " + res.mensaje);
    }
},
    error: function(xhr) {
        console.log("ERROR AJAX:", xhr.responseText);
    }
});




    });

});
</script>

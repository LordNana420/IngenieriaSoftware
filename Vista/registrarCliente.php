<?php
require_once("../Controlador/ClienteControlador.php");
$controlador = new ClienteControlador();

$mensaje = "";
if (isset($_POST['registrar'])) {
    $cliente = new Cliente($_POST['id'], $_POST['nombre'], $_POST['apellido'], $_POST['telefono']);
    $resultado = $controlador->registrarCliente($cliente);

    if (is_array($resultado)) {
        $mensaje = $resultado['mensaje'];
        $exito=$resultado['exito'];
    } else {
        $mensaje = "Error inesperado al registrar el cliente.";
    }
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de clientes - PMirador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
  <div class="container mt-5 col-md-6">
    <div class="card shadow">
      <div class="card-header bg-warning text-dark fw-bold">Registrar Nuevo Cliente</div>
      <div class="card-body">
        <?php if ($mensaje && isset($_POST['registrar'])){
          if($exito){
            echo "<div class='border border-success bg-success-subtle rounded-5 text-center text-success-emphasis fw-bold'>". $mensaje ."</div>";
          }else{
            echo "<div class='border border-danger bg-danger-subtle rounded-5 text-center text-danger-emphasis fw-bold'>". $mensaje ."</div>";
          }
        } 
           ?>
        <form method="POST">
            <div class="mb-3">
            <label class="form-label">Numero de Cedula</label>
            <input type="number" name="id" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Teléfono</label>
            <input type="number" name="telefono" class="form-control" required>
          </div>
          <button type="submit" name="registrar" class="btn btn-warning w-100 fw-bold">Registrar</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>

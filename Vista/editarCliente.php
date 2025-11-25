<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../Modelo/Cliente.php");
require_once("../Modelo/ClienteDAO.php");


if (session_status() === PHP_SESSION_NONE) session_start();

// Validar ID del cliente
$id = $_GET["id"] ?? null;
if (!$id) {
    echo "<div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4>Error: cliente no especificado</h4>
                <p>No se proporcionó un ID de cliente válido.</p>
                <a href='?pid=" . ("Vista/SesionEmpleado.php") . "' class='btn btn-primary'>Volver</a>
            </div>
          </div>";
    exit();
}

$clienteDAO = new ClienteDAO();
$cliente = $clienteDAO->consultarPorId($id);

if (!$cliente) {
    echo "<div class='container mt-5'>
            <div class='alert alert-danger'>
                <h4>Error: cliente no encontrado</h4>
                <a href='Vista/SesionEmpleado.php' class='btn btn-primary'>Volver</a>

            </div>
          </div>";
    exit();
}

$mensaje = "";

if (isset($_POST["editar"])) {
    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $telefono = $_POST["telefono"];
    $estado = $_POST["estado"] ?? 1;

    // Crear objeto cliente con los nuevos datos
    $clienteEditado = new Cliente($id, $nombre, $apellido, $telefono, $estado);
    
    // Llamar al método editar del DAO
    $resultado = $clienteDAO->editar($clienteEditado);

    // Manejo del mensaje
    $mensaje = $resultado['mensaje'];
    if ($resultado['exito']) {
        $cliente = $clienteEditado; // actualizar los datos mostrados
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container">
    <div class="row mt-5 justify-content-center">
        <div class="col-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3><i class="fa fa-edit"></i> Editar Cliente</h3>
                </div>
                <div class="card-body">
                    <?php if ($mensaje != ""): ?>
                        <div class="alert <?= $resultado['exito'] ? 'alert-success' : 'alert-danger' ?>">
                            <?= htmlspecialchars($mensaje) ?>
                        </div>
                    <?php endif; ?>

                   <form method="post" action="editarCliente.php?id=<?= $id ?>">

                        <div class="mb-3">
                            <label>Nombre</label>
                            <input type="text" class="form-control" name="nombre" 
                                   value="<?= htmlspecialchars($cliente->getNombre()) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Apellido</label>
                            <input type="text" class="form-control" name="apellido" 
                                   value="<?= htmlspecialchars($cliente->getApellido()) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Teléfono</label>
                            <input type="text" class="form-control" name="telefono" 
                                   value="<?= htmlspecialchars($cliente->getTelefono()) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Estado</label>
                            <select class="form-control" name="estado" required>
                                <option value="1" <?= $cliente->getEstado() == 1 ? "selected" : "" ?>>Activo</option>
                                <option value="0" <?= $cliente->getEstado() == 0 ? "selected" : "" ?>>Inactivo</option>
                            </select>
                        </div>
                        <div class="mb-3 text-end">
                            <button type="submit" class="btn btn-success" name="editar">
                                <i class="fa fa-save"></i> Guardar Cambios
                            </button>
                            <a href="SesionEmpleado.php" class="btn btn-secondary">
        <i class="fa fa-arrow-left"></i> Volver
    </a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

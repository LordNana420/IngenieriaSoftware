<?php
// Mostrar errores (solo para depurar)
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json");

require_once __DIR__ . "/../Modelo/ClienteDAO.php";

// 🔍 DEPURAR: ver todo lo que llega por POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    file_put_contents(
        __DIR__ . "/log_ajax_cliente.txt",
        "POST recibido: " . json_encode($_POST) . "\n",
        FILE_APPEND
    );
}

// Validación
if (!isset($_POST["idCliente"]) || !isset($_POST["estado"])) {

    echo json_encode([
        "exito" => false,
        "mensaje" => "Faltan datos",
        "post_recibido" => $_POST  // 🔍 DEVUELVE LO QUE LLEGÓ
    ]);
    exit;
}

$id = intval($_POST["idCliente"]);
$estado = intval($_POST["estado"]);

$dao = new ClienteDAO();
$resultado = $dao->cambiarEstado($id, $estado);

if ($resultado) {
    echo json_encode([
        "exito" => true,
        "mensaje" => "Estado actualizado",
        "id" => $id,             // 🔍 DEPURACIÓN
        "nuevo_estado" => $estado
    ]);
} else {
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error al actualizar en BD",
        "id" => $id,              // 🔍 DEPURACIÓN
        "nuevo_estado" => $estado
    ]);
}

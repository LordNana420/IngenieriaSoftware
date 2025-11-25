<?php
// DAO de movimientos
require_once __DIR__ . "/../Modelo/MovimientoDAO.php";

// Ruta correcta de FPDF
$fpdfPath = __DIR__ . "/../Librerias/fpdf/fpdf.php";
if (!file_exists($fpdfPath)) {
    die("Error: No se encontró FPDF en $fpdfPath. Descárgalo desde https://www.fpdf.org/");
}
require_once $fpdfPath;

class ReporteMovimientosControlador
{
    private $dao;

    public function __construct()
    {
        $this->dao = new MovimientoDAO();
    }

    // Mostrar tabla normal en pantalla
    public function mostrarReporte()
    {
        $movimientos = $this->dao->consultarTodos();
        include __DIR__ . "/../Vista/ReporteMovimientos.php";
    }

    // Generar PDF
    public function generarPDF()
    {
        $movimientos = $this->dao->consultarTodos();

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 14);

        // Título
        $pdf->Cell(0, 10, utf8_decode("Reporte de Movimientos de Inventario"), 0, 1, 'C');
        $pdf->Ln(4);

        // Encabezados
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(20, 8, "ID", 1, 0, 'C');
        $pdf->Cell(30, 8, "Producto", 1, 0, 'C');
        $pdf->Cell(30, 8, "Tipo", 1, 0, 'C');
        $pdf->Cell(25, 8, "Cantidad", 1, 0, 'C');
        $pdf->Cell(40, 8, "Fecha", 1, 0, 'C');
        $pdf->Cell(45, 8, "Responsable", 1, 1, 'C');

        // Contenido
        $pdf->SetFont('Arial', '', 10);

        foreach ($movimientos as $m) {
            $pdf->Cell(20, 7, $m->getId(), 1, 0, 'C');
            $pdf->Cell(30, 7, utf8_decode($m->getIdProducto()), 1, 0, 'C');
            $pdf->Cell(30, 7, utf8_decode($m->getTipo()), 1, 0, 'C');
            $pdf->Cell(25, 7, $m->getCantidad(), 1, 0, 'C');
            $pdf->Cell(40, 7, utf8_decode($m->getFecha()), 1, 0, 'C');
            $pdf->Cell(45, 7, utf8_decode($m->getResponsable()), 1, 1, 'C');
        }

        // Mostrar PDF en navegador
        $pdf->Output('I', 'ReporteMovimientos.pdf');
    }
}

// Enrutamiento
$control = new ReporteMovimientosControlador();

if (isset($_GET['accion']) && $_GET['accion'] === 'pdf') {
    $control->generarPDF();
} else {
    $control->mostrarReporte();
}
?>

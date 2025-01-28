<?php
session_start();
if (empty($_SESSION['active'])) {
    header('location: ../');
    exit;
}

include "../../conexion.php";
require_once '../pdf/vendor/autoload.php';

use Dompdf\Dompdf;

// Consulta de configuración
$query_conf = mysqli_query($conection, "SELECT * FROM configuracion");
$result_conf = mysqli_num_rows($query_conf);
if ($result_conf > 0) {
    $configuracion = mysqli_fetch_assoc($query_conf);
    $moned = isset($configuracion['moneda']) ? $configuracion['moneda'] : '$';
} else {
    $moned = '$';
}

$usuario = $_SESSION['idUser'];

// Consulta principal
if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2) {
    $querySql = "SELECT f.nocompra, f.fecha, f.totalcompra, f.codproveedor, f.status,
                 u.nombre as vendedor, cl.proveedor as cliente
                 FROM compras f
                 INNER JOIN usuario u ON f.usuario = u.idusuario
                 INNER JOIN proveedor cl ON f.codproveedor = cl.codproveedor
                 WHERE f.status != 2
                 ORDER BY f.fecha DESC";
} else {
    $querySql = "SELECT f.nocompra, f.fecha, f.totalcompra, f.codproveedor, f.status,
                 u.nombre as vendedor, cl.proveedor as cliente
                 FROM compras f
                 INNER JOIN usuario u ON f.usuario = u.idusuario
                 INNER JOIN proveedor cl ON f.codproveedor = cl.codproveedor
                 WHERE f.status != 2 AND f.usuario = $usuario
                 ORDER BY f.fecha DESC";
}

// Búsqueda en tiempo real
if (isset($_REQUEST['busqueda'])) {
    $busqueda = mysqli_real_escape_string($conection, $_REQUEST['busqueda']);
    $querySql = "SELECT f.nocompra, f.fecha, f.totalcompra, f.codproveedor, f.status,
                 u.nombre as vendedor, cl.proveedor as cliente
                 FROM compras f
                 INNER JOIN usuario u ON f.usuario = u.idusuario
                 INNER JOIN proveedor cl ON f.codproveedor = cl.codproveedor
                 WHERE (
                     f.nocompra LIKE '%$busqueda%' OR
                     cl.proveedor LIKE '%$busqueda%' OR
                     u.nombre LIKE '%$busqueda%' OR
                     f.fecha LIKE '%$busqueda%'
                 ) AND f.status != 2
                 ORDER BY f.fecha DESC";
}

// Ejecutar la consulta
$query = mysqli_query($conection, $querySql);
if (!$query) {
    die("Error en la consulta SQL: " . mysqli_error($conection));
}
$result = mysqli_num_rows($query);

// Generar el reporte
ob_start();
include(dirname('__FILE__') . '/reportePdf_compra.php');
$html = ob_get_clean();

// Configuración de Dompdf
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream('reporte_compras.pdf', array('Attachment' => 0));
exit;
?>

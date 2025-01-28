<?php

session_start();
if (empty($_SESSION['active'])) {
    header('location: ../');
}

include "../../conexion.php";
require_once '../pdf/vendor/autoload.php';

use Dompdf\Dompdf;

if (empty($_REQUEST['cl']) || empty($_REQUEST['f'])) {
    echo "No es posible generar la factura.";
    exit;
}

$codCliente = (int)$_REQUEST['cl'];
$noFactura = (int)$_REQUEST['f'];
$anulada = '';

// Obtener configuración
$query_config = mysqli_query($conection, "SELECT * FROM configuracion");
$result_config = mysqli_num_rows($query_config);

if ($result_config > 0) {
    $configuracion = mysqli_fetch_assoc($query_config);
    $iva = (float)$configuracion['iva'];
    $moned = $configuracion['moneda'];
}

// Obtener datos de la factura
$query = mysqli_query($conection, "
    SELECT f.noventa, DATE_FORMAT(f.fecha, '%d/%m/%Y') as fecha, DATE_FORMAT(f.fecha,'%H:%i:%s') as hora,
           f.codcliente, f.status, f.descuento,
           v.nombre as vendedor,
           cl.nit, cl.nombre, cl.telefono, cl.direccion
    FROM venta f
    INNER JOIN usuario v ON f.usuario = v.idusuario
    INNER JOIN cliente cl ON f.codcliente = cl.idcliente
    WHERE f.noventa = $noFactura AND f.codcliente = $codCliente AND f.status != 10
");

$result = mysqli_num_rows($query);
if ($result == 0) {
    echo "No se encontraron datos para generar la factura.";
    exit;
}

$factura = mysqli_fetch_assoc($query);
$no_factura = $factura['noventa'];

if ($factura['status'] == 2) {
    $anulada = '<img class="anulada" src="img/anulado.png" alt="Anulada">';
}

// Obtener productos de la factura
$query_productos = mysqli_query($conection, "
    SELECT p.descripcion, dt.cantidad, dt.precio_venta, (dt.cantidad * dt.precio_venta) as precio_total
    FROM venta f
    INNER JOIN detalleventa dt ON f.noventa = dt.noventa
    INNER JOIN producto p ON dt.codproducto = p.codproducto
    WHERE f.noventa = $no_factura
");

$result_detalle = mysqli_num_rows($query_productos);

// Variables para cálculos
$subtotal = 0;
$impuesto = 0;
$total = 0;

if ($result_detalle > 0) {
    while ($row = mysqli_fetch_assoc($query_productos)) {
        $subtotal += (float)$row['precio_total'];
    }
}

$impuesto = round($subtotal * ($iva / 100), 2);
$total = round($subtotal + $impuesto, 2);

// Incluir archivo de diseño de factura
ob_start();
include(dirname('__FILE__') . '/factura.php');
$html = ob_get_clean();

// Generar PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('letter', 'portrait');
$dompdf->render();
$dompdf->stream('factura_' . $noFactura . '.pdf', array('Attachment' => 0));
exit;

?>

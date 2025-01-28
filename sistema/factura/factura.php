<?php
$subtotal = 0;
$iva = 0;
$impuesto = 0;
$tl_sniva = 0;
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Factura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php echo $anulada; ?>
<div id="page_pdf">
    <table id="factura_head">
        <tr>
            <td class="logo_factura">
                <div>
                    <img src="img/<?php echo htmlspecialchars($configuracion['foto']); ?>" alt="Logo Empresa">
                </div>
            </td>
            <td class="info_empresa">
                <div>
                    <span class="h2"><?php echo strtoupper(htmlspecialchars($configuracion['nombre'])); ?></span>
                    <p><?php echo htmlspecialchars($configuracion['razon_social']); ?></p>
                    <p><?php echo htmlspecialchars($configuracion['direccion']); ?></p>
                    <p>NIT: <?php echo htmlspecialchars($configuracion['nit']); ?></p>
                    <p>Teléfono: <?php echo htmlspecialchars($configuracion['telefono']); ?></p>
                    <p>Email: <?php echo htmlspecialchars($configuracion['email']); ?></p>
                </div>
            </td>
            <td class="info_factura">
                <div class="round">
                    <span class="h3">Factura</span>
                    <p>No. Factura: <strong><?php echo htmlspecialchars($factura['noventa']); ?></strong></p>
                    <p>Fecha: <?php echo htmlspecialchars($factura['fecha']); ?></p>
                    <p>Hora: <?php echo htmlspecialchars($factura['hora']); ?></p>
                    <p>Vendedor: <?php echo htmlspecialchars($factura['vendedor']); ?></p>
                </div>
            </td>
        </tr>
    </table>

    <table id="factura_cliente">
        <tr>
            <td class="info_cliente">
                <div class="round">
                    <span class="h3">Cliente</span>
                    <table class="datos_cliente">
                        <tr>
                            <td><label>Nit:</label><p><?php echo htmlspecialchars($factura['nit']); ?></p></td>
                            <td><label>Teléfono:</label> <p><?php echo htmlspecialchars($factura['telefono']); ?></p></td>
                        </tr>
                        <tr>
                            <td><label>Cliente:</label> <p><?php echo htmlspecialchars($factura['nombre']); ?></p></td>
                            <td><label>Dirección:</label> <p><?php echo htmlspecialchars($factura['direccion']); ?></p></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <table id="factura_detalle">
        <thead>
            <tr>
                <th width="50px">Cant.</th>
                <th class="textleft">Descripción</th>
                <th width="150px">Precio Unitario</th>
                <th width="150px">Precio Total</th>
            </tr>
        </thead>
        <tbody id="detalle_productos">
        <?php
        if ($result_detalle > 0) {
            while ($row = mysqli_fetch_assoc($query_productos)) {
                $precio_venta = number_format($row['precio_venta'], 2);
                $precio_total = number_format($row['precio_total'], 2);
                ?>
                <tr>
                    <td class="textcenter"><?php echo $row['cantidad']; ?></td>
                    <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                    <td><?php echo $moned . ' ' . $precio_venta; ?></td>
                    <td><?php echo $moned . ' ' . $precio_total; ?></td>
                </tr>
                <?php
                $subtotal += $row['precio_total'];
            }
        }
        $impuesto = round($subtotal * ($iva / 100), 2);
        $tl_sniva = round($subtotal - $impuesto, 2);
        $total = $tl_sniva + $impuesto;
        ?>
        </tbody>
        <tfoot id="detalle_totales">
		<tr>
    <td colspan="3" class="textright"><span>Subtotal</span></td>
    <td class="textright"><span><?php echo $moned . ' ' . number_format((float)$subtotal, 2); ?></span></td>
</tr>
<tr>
    <td colspan="3" class="textright"><span>Impuesto (<?php echo $iva; ?>%)</span></td>
    <td class="textright"><span><?php echo $moned . ' ' . number_format((float)$impuesto, 2); ?></span></td>
</tr>
<tr>
    <td colspan="3" class="textright"><span>Total</span></td>
    <td class="textright"><span><?php echo $moned . ' ' . number_format((float)$total, 2); ?></span></td>
</tr>

        </tfoot>
    </table>
    <div>
        <h4 class="label_gracias">¡Gracias por su compra!</h4>
        <p class="label_gracias">Revise su producto, no aceptamos devoluciones.</p>
    </div>
</div>
</body>
</html>

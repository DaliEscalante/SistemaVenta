<?php
$subtotal 	= 0;
$iva 	 	= 0;
$impuesto 	= 0;
$tl_sniva   = 0;
$total 		= 0;
//print_r($configuracion); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Venta</title>
	<link rel="stylesheet" href="styleticket.css">
</head>

<body>
	<?php echo $anulada; ?>
	<div id="">
		<br>
		<br>
		<table id="factura_head">
			<tr>
				<td class="logo_factura">
					<div style="position: relative; left: 20px;">
						<img src="img/<?php echo $configuracion['foto']; ?>" alt="Logo">
					</div>

				</td>
			</tr>
			<tr>
				<td class="info_empresa">

					<?php

					if ($result_config > 0) {
						$iva = $configuracion['iva'];
						$moned = $configuracion['moneda'];
					?>
						<div>
							<div style="height: 0; position: relative; text-align: center;">
								<span class="h2" style="position: absolute; top: -50px; left: 50%; transform: translateX(-40%); font-size: 24px; font-weight: bold;">
									<?php echo strtoupper($configuracion['nombre']); ?>
								</span>
							</div>

							<p style="padding-top: 200px; margin-left: -30px;">
							<p style="padding-top: 200px; margin-left: -50px;">
							<p style="padding-top: 200px;">
								<span style="margin-left: -0px; display: inline-block;">
									RUC: <?php echo $configuracion['nit']; ?>

								</span><br>
								<span style="margin-left: -0px; display: inline-block; margin-top: 8px;">
									Cel: <?php echo $configuracion['telefono']; ?>
								</span>
								<span style="margin-left: -225px; display: inline-block; margin-top: -9px;">
									<p><?php echo $configuracion['razon_social']; ?></p>
								</span>
							</p>


						</div>
					<?php
						if ($venta['status'] == 1) {
							$tipo_pago = 'Contado';
						} elseif ($venta['status'] == 3) {
							$tipo_pago = 'Crédito';
						} else {
							$tipo_pago = 'Anulado';
						}
					}

					if ($tipo_pago == 'Crédito') {
						date_default_timezone_set("America/Managua");
						$fecha = date('d-m-Y', strtotime($venta["fecha"]));
						$fecha_a_vencer = date('d-m-Y', strtotime($fecha . '+ 30 days'));
						$vence = '<p>&nbsp;&nbsp;Vencimiento: ' . $fecha_a_vencer . '</p>';
					} else {
						$vence = '';
					}

					?>
				</td>
			</tr>
			<tr>
				<td class="">

					<div style="padding-top: 100px;"> <!-- Ajusta el valor de '20px' según lo necesites -->
						<p style="margin-top: 54px;">Tipo de venta: <?php echo $tipo_pago; ?></p> <!-- Ajusta el valor de 50px según lo necesites -->
						<strong>No. Venta: <?php echo str_pad($venta['noventa'], 11, '0', STR_PAD_LEFT); ?> | Fecha: <?php echo $venta['fechaF']; ?></strong>
						<p style="margin-top: 54px;">Hora: <?php echo $venta['horaF']; ?></p> <!-- Ajusta el valor de 50px según sea necesario -->
						<p style="padding-top: 17px;">Vendedor: <?php echo $venta['vendedor']; ?></p> <!-- Ajusta el valor según sea necesario -->
						<strong>Cliente: <?php echo $venta['nombre']; ?></strong>
						<p style="margin-top: 54px;">Nit: <?php echo $venta['nit']; ?></p> <!-- Ajusta el valor de 50px según sea necesario -->
						<?php echo $vence; ?>


					</div>


				</td>
			</tr>
		</table>
		<table id="factura_detalle">
			<thead>
				<tr>
					<th colspan="3" class="textleft"> ------------------------------------------------------------------ Descripción</th>
				</tr>
				<tr>
					<th width="">Código</th>
					<th width="">Cantidad</th>
					<th class="" width="">Precio</th>
					<th class="" width="">Total</th>
				</tr>
				<tr>
					<th colspan="3" class="textleft"> ------------------------------------------------------------------ </th>
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
							<td colspan="3" width=""><?php echo $row['descripcion']; ?></td>
						</tr>
						<tr>
							<td width=""><?php echo $row['codigo']; ?></td>
							<td width=""><?php echo $row['cantidad']; ?></td>
							<td width=""><?php echo $precio_venta; ?></td>
							<td class=""><?php echo $precio_total; ?></td>
						</tr>
				<?php
						$precio_total = $row['precio_total'];
						$subtotal = round($subtotal + $precio_total, 2);
					}
				}

				$impuesto 	= round($subtotal * ($iva / 100), 2);
				$tl_sniva 	= round($subtotal - $impuesto, 2);
				$total 		= $tl_sniva + $impuesto;
				$tl_sniva1 = number_format($subtotal - $impuesto, 2);
				$impuesto1 = number_format($subtotal * ($iva / 100), 2);
				$descuento = number_format($venta['descuento'], 2);
				?>
			</tbody>
			<tfoot id="detalle_totales">
				<tr>
					<td colspan="3">
						<p>---------------------------------------------------------</p>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="" class=""><span>Sub total </span></td>
					<td class=""><span> <?php echo $moned . ' ' . number_format($total, 2); ?></span></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="" class=""><span>Descuento </span></td>
					<td class=""><span> <?php echo $moned . ' ' . $descuento; ?></span></td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td colspan="" class=""><span>TOTAL </span></td>
					<td class=""><span> <?php echo $moned . ' ' . number_format($total - $descuento, 2); ?></span></td>
				</tr>
			</tfoot>
		</table>
		<br>
		<br>
		<br>

		<div style="text-align: center; margin-top: 10px;"> <!-- Reducir el margen superior al mínimo -->
			<!-- Título del código de barras -->
			<p style="font-size: 18px; font-weight: bold; margin: 0 0 5px 0; position: relative; top: -60px;">Código de barras</p> <!-- Subir el título con "top: -10px;" -->

			<div style="display: inline-block; padding: -30px; border: 1px solid #ddd; border-radius: 5px; background-color: #fff; margin-bottom: 10px; position: relative; left: 50px;"> <!-- Mover todo el contenedor hacia la derecha -->
				<div style="position: relative; top: -10px;"> <!-- Subir solo el gráfico -->
					<?php
					require_once __DIR__ . '/../../vendor/autoload.php'; // Ajusta la ruta si es necesario
					use Picqer\Barcode\BarcodeGeneratorHTML;

					$generator = new BarcodeGeneratorHTML();
					$codigo_barra = isset($venta['noventa']) ? $venta['noventa'] : '000000000'; // Valor del código
					echo $generator->getBarcode($codigo_barra, $generator::TYPE_CODE_128);
					?>
				</div>
			</div>



			<!-- Texto del número de barra -->
			<p style="margin-top: 5px; font-size: 16px; font-weight: bold;">Número: <?php echo $codigo_barra; ?></p> <!-- Reducir margen superior del número -->
		</div>



		<div>
			<h4 class="label_gracias">¡Gracias por su compra!</h4>
			<p class="nota_devolucion">Revise su producto, no aceptamos devoluciones.</p>
		</div>

	</div>

</body>

</html>
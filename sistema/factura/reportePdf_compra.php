<?php
	$subtotal 	= 0;
	$iva 	 	= 0;
	$impuesto 	= 0;
	$tl_sniva   = 0;
	$total 		= 0;
 //print_r($configuracion); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        h2 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #007BFF;
            color: white;
            text-transform: uppercase;
        }
        .totales {
            margin-top: 20px;
            text-align: right;
            font-size: 14px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="logo">
        <img src="ruta_del_logo.png" alt="Logo" style="width: 150px; height: auto;">
    </div>
    <h2>Reporte de Compras</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Fecha / Hora</th>
                <th>Proveedor</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th>Total Compra</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalGeneral = 0;
            if ($result > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $estado = ($row['status'] == 1) ? "Pagada" : "Crédito";
                    echo "<tr>";
                    echo "<td>{$row['nocompra']}</td>";
                    echo "<td>{$row['fecha']}</td>";
                    echo "<td>{$row['cliente']}</td>";
                    echo "<td>{$row['vendedor']}</td>";
                    echo "<td>{$estado}</td>";
                    echo "<td>{$moned} " . number_format($row['totalcompra'], 2) . "</td>";
                    echo "</tr>";
                    $totalGeneral += $row['totalcompra'];
                }
            } else {
                echo "<tr><td colspan='6'>No hay datos disponibles</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="totales">
        Compras Totales: <?php echo $moned . " " . number_format($totalGeneral, 2); ?>
    </div>
</body>
</html>

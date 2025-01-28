<?php
include "../../conexion.php";

// Consulta para obtener datos
$query = mysqli_query($conection, "SELECT f.noventa, f.fecha, f.totalventa, u.nombre AS vendedor, cl.nombre AS cliente, f.status 
                                    FROM venta f
                                    INNER JOIN usuario u ON f.usuario = u.idusuario
                                    INNER JOIN cliente cl ON f.codcliente = cl.idcliente
                                    WHERE f.status != 3
                                    ORDER BY f.fecha DESC");

// Verifica si la consulta tiene resultados válidos
if (!$query) {
    die("Error en la consulta: " . mysqli_error($conection));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            margin: 20px;
        }

        h2 {
            text-align: center;
            font-size: 24px;
            color: #444;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007BFF;
            color: #fff;
            text-transform: uppercase;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .totales {
            text-align: right;
            font-weight: bold;
            font-size: 16px;
            margin-top: 20px;
        }

        .totales span {
            color: #007BFF;
        }

        .no-datos {
            text-align: center;
            font-size: 16px;
            font-style: italic;
            color: #999;
        }
    </style>
</head>
<body>
    <h2>Reporte de Ventas</h2>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Fecha / Hora</th>
                <th>Cliente</th>
                <th>Vendedor</th>
                <th>Estado</th>
                <th>Total Venta</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $totalGeneral = 0;

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {
                    $estado = ($row['status'] == 1) ? "Pagada" : "Pendiente";
                    echo "<tr>";
                    echo "<td>{$row['noventa']}</td>";
                    echo "<td>{$row['fecha']}</td>";
                    echo "<td>{$row['cliente']}</td>";
                    echo "<td>{$row['vendedor']}</td>";
                    echo "<td>{$estado}</td>";
                    echo "<td>$" . number_format($row['totalventa'], 2) . "</td>";
                    echo "</tr>";
                    $totalGeneral += $row['totalventa'];
                }
            } else {
                echo "<tr><td colspan='6' class='no-datos'>No hay datos disponibles</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <div class="totales">
        Ventas Totales: <span>$<?php echo number_format($totalGeneral, 2); ?></span>
    </div>
</body>
</html>

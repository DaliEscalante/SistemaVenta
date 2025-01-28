<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Estado de Resultados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }

        /* Título */
        .title {
            font-size: 28px;
            font-weight: bold;
            color: #007BFF;
            text-align: center;
            width: 100%;
            margin-top: 20px;
        }

        /* Tabla */
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
            font-size: 14px;
        }

        /* Totales */
        .totales {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <!-- Título del reporte -->
    <div class="title">Estado de Resultados</div>

    <!-- Tabla con datos del estado de resultados -->
    <table>
        <thead>
            <tr>
                <th style="width: 30%;">Descripción</th>
                <th style="width: 30%;">Monto</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Mostrar los datos obtenidos del query
            $total = 0;
            if ($resultados) {
                // Asegúrate de que la consulta retorna datos
                foreach ($resultados as $row) {
                    echo "<tr>";
                    echo "<td>{$row['descripcion']}</td>";
                    echo "<td>$" . number_format($row['monto'], 2) . "</td>";
                    echo "</tr>";
                    $total += $row['monto'];
                }
            } else {
                echo "<tr><td colspan='2'>No hay datos para el rango de fechas seleccionado.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Total -->
    <div class="totales">
        <p>Total: <span style="color: #007BFF;">$<?php echo number_format($total, 2); ?></span></p>
    </div>

</body>
</html>

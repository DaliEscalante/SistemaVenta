<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Compra</title>
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
            margin-top: -20px; /* Ajusta el margen para mover el título hacia arriba */
        }

        /* Tabla */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px; /* Separación de la tabla */
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

        .totales {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }

        .anulada {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.2;
        }
    </style>
</head>
<body>
    <?php echo $anulada; ?>

    <!-- Título "Detalle de Compra" -->
    <div class="title">Detalle de Compra</div>

    <!-- Tabla de Productos -->
    <table>
        <thead>
            <tr>
                <th style="width: 15%;">Cantidad</th>
                <th style="width: 45%;">Descripción</th>
                <th style="width: 20%;">Precio Unitario</th>
                <th style="width: 20%;">Precio Total</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            if ($result_detalle > 0) {
                while ($row = mysqli_fetch_assoc($query_productos)) {
                    $total += $row['precio_total'];
                    echo "<tr>";
                    echo "<td>{$row['cantidad']}</td>";
                    echo "<td>{$row['descripcion']}</td>";
                    echo "<td>$" . number_format($row['precio'], 2) . "</td>";
                    echo "<td>$" . number_format($row['precio_total'], 2) . "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No hay productos en esta compra.</td></tr>";
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


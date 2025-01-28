<?php
session_start();
include "../conexion.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php" ?>
    <title>Lista de productos</title>
</head>
<body>
    <?php include "includes/header.php" ?>
    <section id="container">

        <h1><i class="fas fa-cube"></i> Lista de productos</h1>
        <?php  
        if ($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2 ){ ?>
            <a href="#" class="btn_new btnNewProducto" id="nuevoProducto"><i class="fas fa-plus"></i> Registrar producto</a>
            <a href="descargarExel.php" class="btn_new " id=""><i class="far fa-file-excel"></i> Exportar a excel</a>
            <a href="stock0.php" class="btn_new " id=""><i class="far fa-file-excel"></i> Stock 0</a>
            <a href="#" class="btn_new " id="reporteProducto"><i class="fa fa-file-alt fa-w-12"></i> Reporte Producto</a>
        <?php } ?>
        <form class="form_search">
            <input type="text" name="busquedaProducto" id="busquedaProducto" placeholder="Buscar">
        </form>
        <div class="containerTable" id="listaProducto">
            <?php
            $query = mysqli_query($conection, "SELECT categoria, codigo, descripcion, existencia, costo, precio, proveedor
                                               FROM producto
                                               WHERE status = 1
                                               ORDER BY categoria, descripcion");

            $result = mysqli_num_rows($query);
            if ($result > 0) {
                $currentCategory = '';
                while ($row = mysqli_fetch_assoc($query)) {
                    if ($currentCategory != $row['categoria']) {
                        // Si cambia la categoría, muestra el encabezado de la nueva categoría
                        if ($currentCategory != '') {
                            echo "</table>"; // Cierra la tabla de la categoría anterior
                        }
                        $currentCategory = $row['categoria'];
                        echo "<h2>Categoría: {$currentCategory}</h2>";
                        echo "<table>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripción</th>
                                    <th>Existencia</th>
                                    <th>Costo</th>
                                    <th>Precio</th>
                                    <th>Proveedor</th>
                                    <th>Acciones</th>
                                </tr>";
                    }
                    // Mostrar los productos de la categoría actual
                    echo "<tr>
                            <td>{$row['codigo']}</td>
                            <td>{$row['descripcion']}</td>
                            <td>{$row['existencia']}</td>
                            <td>$" . number_format($row['costo'], 2) . "</td>
                            <td>$" . number_format($row['precio'], 2) . "</td>
                            <td>{$row['proveedor']}</td>
                            <td>
                                <a class='link_edit' href='#'>Editar</a> |
                                <a class='link_delete' href='#'>Eliminar</a>
                            </td>
                          </tr>";
                }
                echo "</table>"; // Cierra la última tabla
            } else {
                echo "<p>No se encontraron productos.</p>";
            }
            ?>
        </div>
    </section>
    <?php include "includes/footer.php" ?>
</body>
</html>

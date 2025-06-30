<?php
$pageStart = ($page_list > 0) ? (($registers_page * $page_list) - $registers_page) : 0;

$product_table = "";

$fields = "producto.producto_id, producto.producto_nombre, producto.producto_codigo, producto.producto_precio, producto.producto_stock, producto.categoria_id, categoria.categoria_nombre, usuario.usuario_nombre, usuario.usuario_apellido, producto.producto_foto, categoria.categoria_nombre, usuario.usuario_nombre, usuario.usuario_apellido";

if (isset($search) && $search != '') {
    $query_product_list = "SELECT $fields FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id WHERE producto.producto_codigo LIKE '%$search%' OR producto.producto_nombre LIKE '%$search%' ORDER BY producto.producto_nombre ASC LIMIT $pageStart, $registers_page";

    $query_total = "SELECT COUNT(producto_id) FROM producto WHERE producto_codigo LIKE '%$search%' OR producto_nombre LIKE '%$search%'";

} elseif ($category_id > 0) {
    $query_product_list = "SELECT $fields FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id WHERE producto.categoria_id = '$category_id' ORDER BY producto.producto_nombre ASC LIMIT $pageStart, $registers_page";

    $query_total = "SELECT COUNT(producto_id) FROM producto WHERE producto.categoria_id = '$category_id'";

} else {
    $query_product_list = "SELECT $fields FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id ORDER BY producto.producto_nombre ASC LIMIT $pageStart, $registers_page";

    $query_total = "SELECT COUNT(producto_id) FROM producto";
}

$connect = db_connection();
$query = $connect->prepare($query_product_list);
$query->execute();
$rows = $query->fetchAll();

$result = $connect->query($query_total);
$total_registers = (int) $result->fetchColumn();
$pages = ceil($total_registers / $registers_page);

$product_table .= '<div class="table-container">';

if (count($rows) >= 1) {
    $counter = $pageStart + 1;
    $initial_pager = $pageStart + 1;
    $product_table .= ' 
        <table class="table is-fullwidth is-hoverable is-striped ">
            <thead>
                <tr class="has-text-centered">
                    <th class="has-text-centered">#</th>
                    <th class="has-text-centered">Imagen</th>
                    <th class="has-text-centered">Nombre</th>
                    <th class="has-text-centered">Código</th>
                    <th>Precio</th>
                    <th class="has-text-centered">Stock</th>
                    <th class="has-text-centered">Categoría</th>
                    <th class="has-text-centered">Registrado por</th>
                    <th class="has-text-centered" colspan="3">Acciones</th>
                </tr>
            </thead>
            <tbody>
    ';

    foreach ($rows as $row) {
        $product_table .= '<tr class="has-text-left">';
        $product_table .= '<td>' . $counter . '</td>';
        $product_table .= '<td>';
        if (is_file("./img/product/" . $row['producto_foto'])) {
            $product_table .= '<figure class="image is-64x64 is-inline-block"><img src="./img/product/' . $row['producto_foto'] . '" alt="Producto"></figure>';
        } else {
            $product_table .= '<figure class="image is-64x64 is-inline-block"><img src="./img/img.jpg" alt="Sin imagen"></figure>';
        }
        $product_table .= '</td class="">';
        $product_table .= '<td>' . $row['producto_nombre'] . '</td>';
        $product_table .= '<td>' . $row['producto_codigo'] . '</td>';
        $product_table .= '<td>$' . $row['producto_precio'] . '</td>';
        $product_table .= '<td>' . $row['producto_stock'] . '</td>';
        $product_table .= '<td>' . $row['categoria_nombre'] . '</td>';
        $product_table .= '<td>' . $row['usuario_nombre'] . ' ' . $row['usuario_apellido'] . '</td>';
        $product_table .= '<td colspan="3">
            <div class="buttons is-centered">
                <a href="index.php?vista=product_img&product_id_up=' . $row['producto_id'] . '" class="button is-small is-rounded is-link is-light image-icon">
                    <span class="icon"><i class="fa-solid fa-image"></i></span>
                </a>
                <a href="index.php?vista=product_update&product_id_up=' . $row['producto_id'] . '" class="button is-small is-rounded is-info is-light edit-icon">
                    <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                </a>
                <a href="' . $url . $page_list . '&product_id_del=' . $row['producto_id'] . '" class="button is-small is-rounded is-danger is-light confirm-delete-product delete-icon">
                    <span class="icon"><i class="fa-solid fa-trash"></i></span>
                </a>
            </div>
        </td>';
        $product_table .= '</tr>';
        $counter++;
    }

    $product_table .= '</tbody></table>';
    $final_pager = $counter - 1;

} else {
    if ($total_registers >= 1) {
        $product_table .= '<p class="has-text-centered">
            <a href="' . $url . '1" class="button button-reload button-custom is-small mt-4 mb-4">
                Haga clic acá para recargar el listado
            </a>
        </p>';
    } else {
        $product_table .= '<p class="has-text-centered">No hay registros en el sistema</p>';
    }
}

$product_table .= '</div>';

if (count($rows) >= 1) {
    $product_table .= '<p class="has-text-right">Mostrando Productos <strong>' . $initial_pager . '</strong> al <strong>' . $final_pager . '</strong> de un <strong>total de ' . $total_registers . '</strong></p>';
}

$connect = null;
echo $product_table;

if (count($rows) >= 1) {
    echo paginador_tables($page_list, $pages, $url, 7);
}
?>
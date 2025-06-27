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
    //query de paginación de la tabla productos
    $query_product_list = "SELECT $fields FROM producto INNER JOIN categoria ON producto.categoria_id = categoria.categoria_id INNER JOIN usuario ON producto.usuario_id = usuario.usuario_id ORDER BY producto.producto_nombre ASC LIMIT $pageStart, $registers_page";

    $query_total = "SELECT COUNT(producto_id) FROM producto";

}

$connect = db_connection();
$query = $connect->prepare($query_product_list);
$query->execute();
$rows = $query->fetchAll();

$result = $connect->query($query_total);
$total_registers = (int) $result->fetchColumn(); //Extre el número totall de registros
$pages = ceil($total_registers / $registers_page);

if (count($rows) >= 1) {
    $counter = $pageStart + 1;
    $initial_pager = $pageStart + 1;
    foreach ($rows as $row) {
    $product_table .= '
        <div class="box">
            <article class="media">
                <figure class="media-left">
                    <p class="image is-96x96">';
                    if(is_file("./img/product/".$row['producto_foto'])){
                        $product_table .= '<img src="./img/product/'.$row['producto_foto'].'">';
                    } else {
                        $product_table .= '<img src="./img/img.jpg">';
                    }
    $product_table .= '</p>
                </figure>
                <div class="media-content">
                    <div class="content">
                        <p>
                            <strong>' . $counter . ' - ' . $row['producto_nombre'] . '</strong><br>
                            <small><strong>CÓDIGO:</strong> ' . $row['producto_codigo'] . '</small><br>
                            <small><strong>PRECIO:</strong> $' . $row['producto_precio'] . ' | 
                            <strong>STOCK:</strong> ' . $row['producto_stock'] . '</small><br>
                            <small><strong>CATEGORÍA:</strong> ' . $row['categoria_nombre'] . '</small><br>
                            <small><strong>REGISTRADO POR:</strong> ' . $row['usuario_nombre'] . ' ' . $row['usuario_apellido'] . '</small>
                        </p>
                    </div>
                    <div class="buttons are-small is-right">
                        <a href="index.php?vista=product_img&product_id_up='.$row['producto_id'].'" class="button fa-eye is-link is-light">
                            <span class="icon"><i class="fa-solid fa-image"></i></span>
                            <span>Imagen</span>
                        </a>
                        <a href="index.php?vista=product_update&product_id_up='.$row['producto_id'].'" class="button is-info is-light">
                            <span class="icon"><i class="fa-solid fa-pen-to-square"></i></span>
                            <span>Editar</span>
                        </a>
                        <a href="'.$url.$page_list.'&product_id_del='.$row['producto_id'].'" class="button is-danger is-light confirm-delete-category">
                            <span class="icon"><i class="fa-solid fa-trash"></i></span>
                            <span>Eliminar</span>
                        </a>
                    </div>
                </div>
            </article>
        </div>
    ';
    $counter++;
}
    $final_pager = $counter - 1;

} else {
    if ($total_registers >= 1) {
        $product_table .= '
            <p class="has-text-centered">
                <a href="' . $url . '1" class=" button-reload button button-custom is-small mt-4 mb-4">
                    Haga clic acá para recargar el listado
                </a>
            </p>
            ';
    } else {
        $product_table .= '<p class="has-text-centered"> No hay registros en el sistema </p>';
    }
}

if (count($rows) >= 1) {
    $product_table .= '
            <p class="has-text-right">Mostrando Productos <strong>' . $initial_pager . '</strong> al <strong>' . $final_pager . '</strong> de un <strong>total de ' . $total_registers . '</strong></p>
        ';

}

$connect = null;
echo $product_table;

if (count($rows) >= 1) {
    echo paginador_tables($page_list, $pages, $url, 7);
}

?>
<?php 
$pageStart = ($page_list > 0) ? (($registers_page * $page_list)-$registers_page) : 0;
$user_table = "";

if (isset($search) && $search != '') {
    $query_user_list = "SELECT * FROM categoria WHERE categoria_nombre LIKE '%$search%' OR categoria_ubicacion LIKE '%$search%' ORDER BY categoria_nombre ASC LIMIT $pageStart, $registers_page";

    $query_total = "SELECT COUNT(categoria_id) FROM categoria WHERE categoria_nombre LIKE '%$search%' OR categoria_ubicacion LIKE '%$search%'";

} else {    
    $query_user_list = "SELECT * FROM categoria ORDER BY categoria_nombre ASC LIMIT $pageStart, $registers_page";

    $query_total = "SELECT COUNT(categoria_id) FROM categoria";
}

$connect = db_connection();
$query = $connect->prepare($query_user_list);
$query->execute();
$rows = $query->fetchAll();

$result = $connect->query($query_total);   
$total_registers = (int) $result->fetchColumn();
$pages = ceil($total_registers/$registers_page); 

$user_table .= '
    <div class="table-container">
    <table class="table is-fullwidth is-hoverable is-striped tabla-reducida">
        <thead>
            <tr>
                <th class="has-text-centered" >#</th>
                <th>Nombre</th>
                <th>Ubicación</th>
                <th class="has-text-centered">Producto</th>
                <th class="has-text-centered" colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody>
';

if (count($rows) >= 1) {
    $counter = $pageStart + 1;
    $initial_pager = $pageStart + 1;
    foreach ($rows as $row) {
        $user_table .= '
            <tr>
                <td>' . $counter . '</td>
                <td>' . $row['categoria_nombre'] . '</td>
                <td>' . substr($row['categoria_ubicacion'], 0, 20) . '</td>
                <td class="has-text-centered">
                    <a href="index.php?vista=product_category&_id=' . $row['categoria_id'] . '" 
                    class="button is-small is-rounded is-link is-light icon-link">
                        <span class="icon"><i class="fa-solid fa-eye"></i></span>
                        <span class="text-view-product">Ver</span>
                    </a>
                </td>
                <td colspan="2">
                    <div class="buttons are-small is-centered">
                            <a href="index.php?vista=category_update&category_id_up=' . $row['categoria_id'] . '" 
                            class="button is-small is-rounded is-info is-light icon-link">
                                <span class="icon"><i class="fa-solid fa-pen"></i></span>
                                <span>Editar</span>
                            </a>
                            <a href="' . $url . $page_list . '&category_id_del=' . $row['categoria_id'] . '" 
                            class="button is-small is-rounded is-danger is-light confirm-delete-category icon-link">
                                <span class="icon"><i class="fa-solid fa-trash"></i></span>
                                <span>Eliminar</span>
                            </a>
                        </td>
                    </div>
            </tr>
        ';
        $counter++;
    }
    $final_pager = $counter - 1;

} else {
    $colspan = 6;
    if ($total_registers >= 1) {
        $user_table .= '
            <tr>
                <td colspan="' . $colspan . '" class="has-text-centered">
                    <a href="'.$url.'1" class="button button-reload button-custom is-small mt-4 mb-4">
                        Haga clic acá para recargar el listado
                    </a>
                </td>
            </tr>
        ';
    } else {
        $user_table .= '
            <tr>
                <td colspan="' . $colspan . '" class="has-text-centered">
                    No hay registros en el sistema
                </td>
            </tr>
        ';
    }
}

$user_table .= '
        </tbody>
    </table>
</div>
';

if (count($rows) >= 1) {
    $user_table .= '
        <p class="has-text-right">Mostrando Categorías <strong>'.$initial_pager.'</strong> al <strong>'.$final_pager.'</strong> de un <strong>total de '.$total_registers.'</strong></p>
    ';
}

$connect = null;
echo $user_table;

if (count($rows) >= 1) {
    echo paginador_tables($page_list, $pages, $url, 7);
}
?>
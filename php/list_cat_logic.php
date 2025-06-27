<?php 
    $pageStart = ($page_list > 0) ? (($registers_page * $page_list)-$registers_page) : 0;
    $user_table = "";

    if (isset($search) && $search != '') {
        $query_user_list = "SELECT * FROM categoria WHERE categoria_nombre LIKE '%$search%' OR categoria_ubicacion LIKE '%$search%' ORDER BY categoria_nombre ASC LIMIT $pageStart, $registers_page";

        $query_total = "SELECT COUNT(categoria_id) FROM categoria WHERE categoria_nombre LIKE '%$search%' OR categoria_ubicacion LIKE '%$search%'";

    } else {    
        //query de paginación de la tabla  usuarios
        $query_user_list = "SELECT * FROM categoria ORDER BY categoria_nombre ASC LIMIT $pageStart, $registers_page";

        $query_total = "SELECT COUNT(categoria_id) FROM categoria";

    }

    $connect = db_connection();
    $query = $connect->prepare($query_user_list);
    $query->execute();
    $rows= $query->fetchAll();

    $result = $connect->query($query_total);   
    $total_registers = (int) $result->fetchColumn(); //Extre el número totall de registros
    $pages = ceil($total_registers/$registers_page); 

    $user_table .= '
        <div class="table-container">
        <table class="table is-fullwidth is-hoverable is-striped has-text-centered">
            <thead>
                <tr class="has-text-centered">
                    <th class="has-text-centered">#</th>
                    <th class="has-text-centered">Nombre</th>
                    <th class="has-text-centered">Ubicación</th>
                    <th class="has-text-centered">Producto</th>
                    <th colspan="2" class="has-text-centered">Acciones</th>
                </tr>
            </thead>
            <tbody>
    ';

    if (count($rows) >= 1) {
        $counter = $pageStart + 1;
        $initial_pager = $pageStart + 1;
        foreach ($rows as $row) {
    $user_table .= '
        <tr class="has-text-centered">
            <td>' . $counter . '</td>
            <td>' . $row['categoria_nombre'] . '</td>
            <td>' . substr($row['categoria_ubicacion'], 0, 20) . '</td>
            
            <!-- Columna de PRODUCTO (solo botón de ver) -->
            <td>
                <div class="buttons is-centered">
                    <a href="index.php?vista=product_category&_id=' . $row['categoria_id'] . '" class="button is-small is-link is-light view-icon">
                        <i class="fa-solid fa-eye"></i>
                    </a>
                </div>
            </td>

            <!-- Columna de ACCIONES -->
            <td>
                <div class="buttons is-centered">
                    <a href="index.php?vista=category_update&category_id_up=' . $row['categoria_id'] . '" class="button is-small is-info is-light edit-icon">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <a href="' . $url . $page_list . '&category_id_del=' . $row['categoria_id'] . '" class="button is-small is-danger is-light confirm-delete-category delete-icon">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
            </td>
        </tr>
    ';
    $counter++;
}


        $final_pager = $counter - 1;

    } else {
        if ($total_registers >= 1) {
            $user_table .= '
                <tr class="has-text-centered " >
                    <td colspan="7">
                        <a href="'.$url.'1" class=" button-reload button button-custom is-small mt-4 mb-4">
                            Haga clic acá para recargar el listado
                        </a>
                    </td>
                </tr>
            ';
        } else {
            $user_table .= '
                <tr class="has-text-centered" >
                    <td colspan="7">
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
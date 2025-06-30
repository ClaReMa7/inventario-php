<?php
    $pageStart = ($page_list > 0) ? (($registers_page * $page_list)-$registers_page) : 0;
    $user_table = "";

    if (isset($search) && $search != '') {
        $query_user_list = "SELECT * FROM usuario WHERE  ((usuario_id != '".$_SESSION['id']."') AND (usuario_nombre LIKE '%$search%' OR usuario_apellido LIKE '%$search%' OR usuario_usuario LIKE '%$search%' OR usuario_email LIKE '%$search%')) ORDER BY usuario_nombre ASC LIMIT $pageStart, $registers_page";
        $query_total = "SELECT COUNT(usuario_id) FROM usuario WHERE ((usuario_id != '".$_SESSION['id']."') AND (usuario_nombre LIKE '%$search%' OR usuario_apellido LIKE '%$search%' OR usuario_usuario LIKE '%$search%' OR usuario_email LIKE '%$search%'))";
    } else {
        $query_user_list = "SELECT * FROM usuario WHERE  usuario_id != '".$_SESSION['id']."' ORDER BY usuario_nombre ASC LIMIT $pageStart, $registers_page";
        $query_total = "SELECT COUNT(usuario_id) FROM usuario WHERE  usuario_id != '".$_SESSION['id']."' ";
    }

    $connect = db_connection();
    $query = $connect->prepare($query_user_list);
    $query->execute();
    $rows= $query->fetchAll();

    $result = $connect->query($query_total);   
    $total_registers = (int) $result->fetchColumn(); 
    $pages = ceil($total_registers/$registers_page); 

    $user_table .= '
        <div class="table-container">
        <table class="table is-fullwidth is-hoverable is-striped  tabla-reducida">
            <thead>
                <tr>
                    <th ">#</th>
                    <th ">Nombres</th>
                    <th ">Apellidos</th>
                    <th ">Usuario</th>
                    <th ">Email</th>
                    <th class="has-text-centered" colspan="2">Acciones</th>
                </tr>
            </thead>
            <tbody>';

    if (count($rows) >= 1) {
        $counter = $pageStart + 1;
        $initial_pager = $pageStart + 1;
        foreach ($rows as $row) {
            $user_table .= '
                <tr>
                    <td>' . $counter . '</td>
                    <td>' . $row['usuario_nombre'] . '</td>
                    <td>' . $row['usuario_apellido'] . '</td>
                    <td>' . $row['usuario_usuario'] . '</td>
                    <td>' . $row['usuario_email'] . '</td>
                    <td colspan="2">
                        <div class="buttons are-small is-centered">
                            <a href="index.php?vista=user_update&user_id_up=' . $row['usuario_id'] . '" 
                                class="button is-rounded is-info is-light icon-link ">
                                <span class="icon"><i class="fa-solid fa-user-pen"></i></span>
                                <span>Editar</span>
                            </a>
                            <a href="' . $url_list . $page_list . '&user_id_del=' . $row['usuario_id'] . '" 
                                class="button is-rounded is-danger is-light confirm-delete-user icon-link">
                                <span class="icon"><i class="fa-solid fa-trash"></i></span>
                                <span>Eliminar</span>
                            </a>
                        </div>
                    </td>
                </tr>';
            $counter++;
        }
        $final_pager = $counter - 1;
    } else {
        $colspan = 7;
        $user_table .= ($total_registers >= 1) ? '
            <tr><td colspan="' . $colspan . '"><a href="'.$url_list.'1" class="button is-small is-link is-light mt-4 mb-4">Recargar listado</a></td></tr>'
            : '<tr><td colspan="' . $colspan . '">No hay registros en el sistema</td></tr>';
    }

    $user_table .= '</tbody></table></div>';

    if (count($rows) >= 1) {
        $user_table .= '<p class="has-text-right">Mostrando usuarios <strong>'.$initial_pager.'</strong> al <strong>'.$final_pager.'</strong> de un <strong>total de '.$total_registers.'</strong></p>';
    }

    $connect = null;
    echo $user_table;

    if (count($rows) >= 1) {
        echo paginador_tables($page_list, $pages, $url_list, 7);
    }

?>

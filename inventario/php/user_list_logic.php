<?php 
    $pageStart = ($page_list > 0) ? (($registers_page * $page_list)-$registers_page) : 0;
    $user_table = "";

    if (isset($search) && $search != '') {
        $query_user_list = "SELECT * FROM usuario WHERE  ((usuario_id != '".$_SESSION['id']."') AND (usuario_nombre LIKE '%$search%' OR usuario_apellido LIKE '%$search%' OR usuario_usuario LIKE '%$search%' OR usuario_email LIKE '%$search%')) ORDER BY usuario_nombre ASC LIMIT $pageStart, $registers_page";

        $query_total = "SELECT COUNT(usuario_id) FROM usuario WHERE ((usuario_id != '".$_SESSION['id']."') AND (usuario_nombre LIKE '%$search%' OR usuario_apellido LIKE '%$search%' OR usuario_usuario LIKE '%$search%' OR usuario_email LIKE '%$search%'))";

    } else {    
        //query de paginación de la tabla  usuarios
        $query_user_list = "SELECT * FROM usuario WHERE  usuario_id != '".$_SESSION['id']."' ORDER BY usuario_nombre ASC LIMIT $pageStart, $registers_page";

        $query_total = "SELECT COUNT(usuario_id) FROM usuario WHERE  usuario_id != '".$_SESSION['id']."' ";

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
                    <th class="has-text-centered">Nombres</th>
                    <th class="has-text-centered">Apellidos</th>
                    <th class="has-text-centered">Usuario</th>
                    <th class="has-text-centered">Email</th>
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
                <tr class="has-text-centered" >
					<td>'.$counter.'</td>
                    <td>'.$row['usuario_nombre'].'</td>
                    <td>'.$row['usuario_apellido'].'</td>
                    <td>'.$row['usuario_usuario'].'</td>
                    <td>'.$row['usuario_email'].'</td>
                    <td>
                        <a href="index.php?vista=user_update&user_id_up='.$row['usuario_id'].'" class="has-text-info icon-link edit-icon" title="Editar"><i class="fa-solid fa-user-pen"></i></a>
                    </td>
                    <td>
                        <a href="'.$url_list.$page_list.'&user_id_del='.$row['usuario_id'].'" class=" has-text-danger icon-link delete-icon" title="Eliminar"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
            ';
            $counter++;
        }
        $final_pager = $counter - 1;

    } else {
        if ($total_registers >= 1) {
            $user_table .= '
                <tr class="has-text-centered" >
                    <td colspan="7">
                        <a href="'.$url_list.'1" class="button is-link is-rounded is-small mt-4 mb-4">
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
            <p class="has-text-right">Mostrando usuarios <strong>'.$initial_pager.'</strong> al <strong>'.$final_pager.'</strong> de un <strong>total de '.$total_registers.'</strong></p>
        ';

    }

    $connect = null;
    echo $user_table;

    if (count($rows) >= 1) {
        echo paginador_tables($page_list, $pages, $url_list, 7);
    }

?>
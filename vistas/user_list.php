<div class="container is-fluid mb-6">
    <div class="has-text-left">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Usuario</h1>
        <h2 class="subtitle is-5 has-text-grey">Lista de usuarios</h2>
        <div class="divider-aligned-left "></div>
    </div>
</div>

<div class="container pb-6 pt-6">

    <?php 
        require_once './php/main.php';

        // Mostrar mensaje si existe
        if (isset($_SESSION['mensaje']) && isset($_SESSION['mensaje']['tipo'])) {
            $tipo = $_SESSION['mensaje']['tipo'];
            $titulo = $_SESSION['mensaje']['titulo'];
            $contenido = $_SESSION['mensaje']['contenido'];

            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification'.$tipo.' is-light has-text-centered">
                            <strong>'.strtoupper($titulo).'</strong><br>
                            '.$contenido.'
                        </div> 
                    </div>
                </div>
                ';
                unset($_SESSION['mensaje']); // Limpiamos el mensaje después de mostrarlo
        }

        // Eliminar usuario
        if (isset($_GET['user_id_del'])) {
            require_once "./php/user_delete.php";

        }
        // Paginación de la tabla
        if (!isset($_GET['page'])) {
            $page_list = 1; 

        } else {
            $page_list = (int) $_GET['page'];
            // Validamos num de pagina si es menor a 1 mostramos la pagina 1
            if ($page_list <= 1) {
                $page_list = 1;
            }
        }

        $page_list = limpiar_cadena($page_list);
        $url_list = "index.php?vista=user_list&page=";
        $registers_page = 10;
        $search = "";

        require_once './php/user_list_logic.php';
    ?>


</div>
<div class="container is-fluid mb-6">
    <h1 class="title">Categorías</h1>
    <h2 class="subtitle">Lista de categoría</h2>
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
                        <div class="notification '.$tipo.' is-info is-light has-text-centered">
                            <strong>'.strtoupper($titulo).'</strong><br>
                            '.$contenido.'
                        </div>
                    </div>
                </div>
                ';
                unset($_SESSION['mensaje']); // Limpiamos el mensaje después de mostrarlo
        }

         // Eliminar categoria
        if (isset($_GET['category_id_del'])) {
            require_once "./php/category_delete.php";

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
        $url = "index.php?vista=category_list&page=";
        $registers_page = 10;
        $search = "";

        require_once './php/list_cat_logic.php';
?>

</div>
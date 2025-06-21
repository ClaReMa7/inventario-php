<div class="container is-fluid mb-6">
    <h1 class="title">Categorías</h1>
    <h2 class="subtitle">Lista de categoría</h2>
</div>

<div class="container pb-6 pt-6">

<?php 
        require_once './php/main.php';


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
        $url_list = "index.php?vista=category_list&page=";
        $registers_page = 10;
        $search = "";

        require_once './php/list_cat_logic.php';
?>

</div>
<div class="container is-fluid mb-6">
    <div class="has-text-left">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Productos</h1>
        <h2 class="subtitle is-5 has-text-grey">Buscar producto</h2>
        <div class="divider-aligned-left "></div>
    </div>
</div>

<div class="container pb-6 pt-6">
    <?php 

        require_once "./php/main.php";

        if (isset($_POST['modulo_buscador'])) {
            require_once "./php/search.php";
        }

        if (!isset($_SESSION['search_producto']) && empty($_SESSION['search_producto'])) {
    ?>

    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="producto">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button button-custom btn-search is-rounded" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php 
        } else { 
    ?>

    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="producto"> 
                <input type="hidden" name="eliminar_buscador" value="producto">
                <p>Estas buscando <strong>"<?php echo $_SESSION['search_producto'];?>"</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>

    <?php

             // Eliminar producto
        if (isset($_GET['product_id_del'])) {
            require_once "./php/product_delete.php";

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
        
        $category_id = (isset($_GET['category_id'])) ? $_GET['category_id'] : 0;

        $page_list = limpiar_cadena($page_list);
        $url = "index.php?vista=product_search&page=";
        $registers_page = 10;
        $search = $_SESSION['search_producto'];

        require_once './php/list_product_logic.php';
        } 
    ?>
</div>
<div class="container is-fluid mb-6">
    <h1 class="title">Categorías</h1>
    <h2 class="subtitle">Buscar categoría</h2>
</div>

<div class="container pb-6 pt-6">
    <?php 

        require_once "./php/main.php";

        if (isset($_POST['modulo_buscador'])) {
            require_once "./php/search.php";
        }

        if (!isset($_SESSION['search_categoria']) && empty($_SESSION['search_categoria'])) {
    ?>

    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="categoria">
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button button-custom btn-search" type="submit" >Buscar</button>
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
                <input type="hidden" name="modulo_buscador" value="categoria"> 
                <input type="hidden" name="eliminar_buscador" value="categoria">
                <p>Estas buscando <strong>"<?php echo $_SESSION['search_categoria']?>"</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>

    <?php 
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
        $url = "index.php?vista=category_search&page=";
        $registers_page = 10;
        $search = $_SESSION['search_categoria'];

        require_once './php/list_cat_logic.php';
        } 
    ?>
    
</div>
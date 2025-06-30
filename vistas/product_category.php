<div class="container is-fluid mb-6">
    <div class="divider-container">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Productos</h1>
        <h2 class="subtitle is-5 has-text-grey">Lista de productos por categoría</h2>
        <div class="divider-aligned-left " ></div>
    </div>
</div>

<div class="container pb-6 pt-4">
    <?php require_once "./php/main.php"; ?>
    
    <div class="columns is-variable is-5">
        <!-- Columna de Categorías -->
        <div class="column is-2">
            <div class="card">
                <div class="card-content">
                    <h2 class="title is-5 has-text-centered has-text-weight-semibold mb-4">Categorías</h2>
                    
                    <div class="menu">
                        <?php
                            $categories = db_connection();
                            $categories = $categories->query("SELECT * FROM categoria ORDER BY categoria_nombre");
                            
                            if($categories->rowCount() > 0){
                                $categories = $categories->fetchAll();
                                
                                echo '<ul class="menu-list">';
                                foreach($categories as $row){
                                    $active = (isset($_GET['categoria_id']) && $_GET['categoria_id'] == $row['categoria_id']) ? 'is-active' : '';
                                    echo '<li>';
                                    echo '<a href="index.php?vista=product_category&categoria_id='.$row['categoria_id'].'" class="'.$active.'">';
                                    echo '<span class="icon"><i class="fas fa-folder"></i></span>';
                                    echo '<span>'.$row['categoria_nombre'].'</span>';
                                    echo '</a>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo '<div class="notification is-light has-text-centered">No hay categorías registradas</div>';
                            }
                            $categories = null;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Columna de Productos -->
        <div class="column is-10">
            <div class="box">
                <?php
                    $category_id = (isset($_GET['categoria_id'])) ? $_GET['categoria_id'] : 0;
                    
                    /*== Verificando category ==*/
                    $check_category = db_connection();
                    $check_category = $check_category->prepare("SELECT * FROM categoria WHERE categoria_id = :id");
                    $check_category->execute([':id' => $category_id]);
                    
                    if($check_category->rowCount() > 0){
                        $check_category = $check_category->fetch();
                        
                        echo '<div class="has-text-centered mb-6">';
                        echo '<h2 class="title is-3 has-text-weight-semibold">'.$check_category['categoria_nombre'].'</h2>';
                        echo '<p class="subtitle is-6 has-text-grey">'.$check_category['categoria_ubicacion'].'</p>';
                        echo '</div>';
                        
                        require_once "./php/main.php";
                        
                        # Eliminar producto #
                        if(isset($_GET['product_id_del'])){
                            require_once "./php/product_delete.php";
                        }
                        
                        if(!isset($_GET['page'])){
                            $page_list = 1;
                        } else {
                            $page_list = (int) $_GET['page'];
                            if($page_list <= 1){
                                $page_list = 1;
                            }
                        }
                        
                        $page_list = limpiar_cadena($page_list);
                        $url = "index.php?vista=product_category&categoria_id=$category_id&page=";
                        $registers_page = 10;
                        $search = "";
                        
                        # Paginador producto #
                        require_once "./php/list_product_logic.php";
                        
                    } else {
                        echo '<div class="has-text-centered">';
                        echo '<span class="icon is-large"><i class="fas fa-folder-open fa-3x has-text-grey-light"></i></span>';
                        echo '<h2 class="title is-4 has-text-grey mt-4">Seleccione una categoría para empezar</h2>';
                        echo '</div>';
                    }
                    $check_category = null;
                ?>
            </div>
        </div>
    </div>
</div>

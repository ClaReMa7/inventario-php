<?php 
    $search_module = limpiar_cadena($_POST['modulo_buscador']);

    $modules = ["usuario", "categoria", "producto"];

    if (in_array($search_module, $modules)) {
        
        $modules_url = [
            "usuario" => "user_search",
            "categoria" => "category_search",
            "producto" => "product_search"
        ];
        
        $modules_url = $modules_url[$search_module];

        $search_module = "search_" . $search_module;

        //iniciar búsqueda
        if (isset($_POST['txt_buscador'])) {
            $txt = limpiar_cadena($_POST['txt_buscador']);

            if ($txt == "") {
                echo '
                    <div class="columns is-centered">
                        <div class="column is-half">
                            <div class="notification is-danger is-light has-text-centered">
                            <strong>¡Ocurrió un error inesperado!</strong><br>
                                Introduce un término de búsqueda.
                            </div>
                        </div>
                    </div>';
                    
            } else {
                if (verficar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}", $txt)) {
                    echo '
                    <div class="columns is-centered">
                        <div class="column is-half">
                            <div class="notification is-danger is-light has-text-centered">
                            <strong>¡Ocurrió un error inesperado!</strong><br>
                                El termino de busqueda no coincide con el formato solicitado.
                            </div>
                        </div>
                    </div>';
                } else {
        
                    $_SESSION[$search_module] = $txt;
                    header("Location: index.php?vista=$modules_url", true, 303);  // Redirigir a la página de búsqueda
                    exit();          
                }
            }
        } 

        //Eliminar búsqueda
        if (isset($_POST['eliminar_buscador'])) {
            unset($_SESSION[$search_module]);
            header("Location: index.php?vista=$modules_url", true, 303);
            exit();    

        }

        } else {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrió un error inesperado!</strong><br>
                            No podemos procesar la petición.
                        </div>
                    </div>
                </div>';
        }
?>
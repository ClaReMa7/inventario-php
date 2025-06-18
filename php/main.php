<?php
    //datos de conexion
    $dbHost = 'localhost';
    $dbName = 'inventario';
    $dbUser = 'root';
    $dbPass = '';

    #Conexion a la BBDD
    function db_connection() {
        global $dbHost, $dbName, $dbUser, $dbPass;
        
        try {
        $pdo = new PDO("mysql:host={$dbHost};dbname={$dbName};charset=utf8",
            $dbUser,
            $dbPass);
        return $pdo;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
    }

    #Verificar datos del formulario
    function verficar_datos($filter, $string) {
        return !preg_match("/^".$filter."$/", $string);
    }
    
    #Función para evitar inyeccion SQL
    function limpiar_cadena($string) {
        //Elimina espacios al principio y al final
        $string = trim($string);
        // Elimina caracteres de escape innecesarios
        $string = stripslashes($string);
        //Convierte caracteres especiales en entidades HTML
        $string = htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        // Por si quedó algun espacio en blanco
        $string = trim($string);
        return $string;
    }

    # Función renombrar imagen
    function renombrar_imagen($name_img) {
        $name_img = str_ireplace(" ", "_",$name_img);
        $name_img = str_ireplace("/", "_",$name_img);
        $name_img = str_ireplace("#", "_",$name_img);
        $name_img = str_ireplace("-", "_",$name_img);
        $name_img = str_ireplace("$", "_",$name_img);
        $name_img = str_ireplace(".", "_",$name_img);
        $name_img = str_ireplace(",", "_",$name_img);
        $name_img = $name_img."_".rand(0, 100);
        return $name_img;
    }

    # Función paginacion tables 
    function paginador_tables($page_list, $pages, $url_list, $buttons) {
        $table = '<nav class="pagination is-rounded is-centered" role="navigation" aria-label="pagination">';

        // Condicional paginación anterior
        if ($page_list <= 1) {
            $table .=
            '<a href="#" class="pagination-previous is-disabled" disabled >Anterior</a>
            <ul class="pagination-list">';
        }else {
            $table .=
            '<a href="'.$url_list.($page_list-1).'" class="pagination-previous">Anterior</a>
            <ul class="pagination-list">
                <li><a href="'.$url_list.'1" class="pagination-link">1</a></li>
                <li><span class="pagination-ellipsis">&hellip;</span></li>
                ';
        }

        // Bucle paginación
        $ci = 0;
        for ($i = $page_list; $i <= $pages; $i++) {
            if ($ci >= $buttons) {
                break;

            }
            if ($page_list == $i) {
                $table .= '<li><a href="'.$url_list.$i.'" class="pagination-link is-current ">'.$i.'</a></li>';

            }else {
                $table .= '<li><a href="'.$url_list.$i.'" class="pagination-link">'.$i.'</a></li>';
            }
            $ci++;
        }

        // Condicional paginación posterior
        if ($page_list == $pages) {
            $table .= '
            </ul>
            <a href="#" class="pagination-next is-disabled" disabled >Siguiente</a>
            ';
        }else {
            $table .= '
                <li><span class="pagination-ellipsis">&hellip;</span></li>
                <li><a href="'.$url_list.$pages.'" class="pagination-link">'.$pages.'</a></li>
            </ul>
            <a href="'.$url_list.($page_list+1).'" class="pagination-next" >Siguiente</a>
            ';
        }

        $table .= '</nav>';
        return $table;

    }

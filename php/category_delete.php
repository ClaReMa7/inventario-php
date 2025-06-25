<?php

    $category_id_del = limpiar_cadena($_GET['category_id_del']);

    // Verificando categoría 
    $check_category = db_connection();
    $check_category = $check_category->prepare("SELECT categoria_id FROM categoria WHERE categoria_id = :category_id");
    $check_category->execute([':category_id' => $category_id_del]);

    if ($check_category->rowCount() == 1) {
        //Verificando productos asociados a la categoría
        $check_products = db_connection();
        $check_products = $check_products->prepare("SELECT categoria_id FROM producto WHERE categoria_id = :category_id LIMIT 1");
        $check_products->execute([':category_id' => $category_id_del]);

        if ($check_products->rowCount() == 0) {

            $delete_category = db_connection();
            $delete_category = $delete_category->prepare("DELETE FROM categoria WHERE categoria_id = :category_id");
            
            $delete_category->execute([':category_id' => $category_id_del]);

            if ($delete_category->rowCount() == 1) {
                $_SESSION['mensaje'] = [
                'tipo' => 'is-info',
                'titulo' => '¡Categoria eliminada!',
                'contenido' => 'La CATEGORIA se eliminó correctamente.'
                ];

            } else {
                $_SESSION['mensaje'] = [
                'tipo' => 'is-danger',
                'titulo' => '¡Error al eliminar!',
                'contenido' => 'No hemos podido eliminar la categoria, por favor intenta nuevamente.'
            ];
            }
            $delete_category= null;

        } else {
            $_SESSION['mensaje'] = [
            'tipo' => 'is-danger',
            'titulo' => '¡Error al eliminar!',
            'contenido' => 'La CATEGORIA que intentas eliminar tiene productos asignados, no puedes eliminarla.'
            ];

        }
        $check_products = null;

    } else {
        $_SESSION['mensaje'] = [
        'tipo' => 'is-danger',
        'titulo' => '¡Error al eliminar!',
        'contenido' => 'La categoría que intentas eliminar no existe.'
        ];
    }
    $delete_category = null;

    // Redireccionando al listado de usuarios
    header("Location: index.php?vista=category_list");
    exit();

    
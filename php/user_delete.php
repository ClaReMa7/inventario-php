<?php

    $user_id_del = limpiar_cadena($_GET['user_id_del']);

    //Verificando usuario
    $check_user = db_connection();
    $check_user = $check_user->prepare("SELECT usuario_id FROM usuario WHERE usuario_id = :user_id");
    $check_user->execute([':user_id' => $user_id_del]);

    if ($check_user->rowCount() == 1) {

        //Verificando productos del usuario
        $check_products = db_connection();
        $check_products = $check_products->prepare("SELECT usuario_id FROM producto WHERE usuario_id = :user_id LIMIT 1");
        $check_products->execute([':user_id' => $user_id_del]);

        if ($check_products->rowCount() == 0) {

            $delete_user = db_connection();
            $delete_user = $delete_user->prepare("DELETE FROM usuario WHERE usuario_id = :user_id");
            
            $delete_user->execute([':user_id' => $user_id_del]);

            if ($delete_user->rowCount() == 1) {
                $_SESSION['mensaje'] = [
                'tipo' => 'is-info',
                'titulo' => '¡Usuario eliminado!',
                'contenido' => 'El usuario se eliminó correctamente.'
                ];

            } else {
                $_SESSION['mensaje'] = [
                'tipo' => 'is-danger',
                'titulo' => '¡Error al eliminar!',
                'contenido' => 'No hemos podido eliminar el usuario, por favor intenta nuevamente.'
            ];
            }
            $delete_user = null;

        } else {
            $_SESSION['mensaje'] = [
            'tipo' => 'is-danger',
            'titulo' => '¡Error al eliminar!',
            'contenido' => 'El usuario que intentas eliminar tiene productos asignados, no puedes eliminarlo.'
            ];

        }
        //$chaeck_products = null;

    } else {
        $_SESSION['mensaje'] = [
        'tipo' => 'is-danger',
        'titulo' => '¡Error al eliminar!',
        'contenido' => 'El usuario que intentas eliminar no existe.'
        ];
    }
    //$check_user = null;

    // Redireccionando al listado de usuarios
    header("Location: index.php?vista=user_list");
    exit();
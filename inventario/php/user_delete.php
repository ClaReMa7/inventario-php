<?php

    $user_id_del = limpiar_cadena($_GET['user_id_del']);

    //Verificando usuario
    $check_user = db_connection();
    $check_user = $check_user->prepare("SELECT usuario_id FROM usuario WHERE usuario_id = $user_id_del");

    if ($check_user->rowCount() == 1) {

    } else {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                        El usuario que intentas eliminar no existe.
                    </div>
                </div>
            </div>';
        exit();
    }

    $check_user = null;
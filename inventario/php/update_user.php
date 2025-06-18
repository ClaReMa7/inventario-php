<?php
    require_once '../includes/session_start.php';
    require_once 'main.php';

    $id = limpiar_cadena($_POST['usuario_id']);

    // Verificar el usuario
    $check_user = db_connection();
    $check_user = $check_user->prepare("SELECT * FROM usuario WHERE usuario_id = :id");
    $check_user->execute([':id' => $id]);

    if ($check_user -> rowCount() <= 0) {
        echo '
        <div class="alert alert-danger" role="alert">
            <strong>Error:</strong> El usuario no existe o ha sido eliminado.
        ';
        exit();

    } else {
        $datos = $check_user -> fetch();

    }
    $check_user = null;

    $admin_user = limpiar_cadena($_POST['administrador_usuario']);
    $admin_key = limpiar_cadena($_POST['administrador_clave']);

    // Validación campos obligatorios
    if ($admin_user  == "" || $admin_key == "") {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                        No has llenado todos los campos que son obligatorios (Usuario y Contraseña).
                    </div>
                </div>
            </div>
        ';
        
        exit();
    }

    // Verificando integridad de los datos
    if (verficar_datos("[a-zA-Z0-9]{4,20}", $admin_user) || verficar_datos("[a-zA-Z0-9$@.-]{7,100}", $admin_key)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El Usuario y/o Contraseña no cumple con el formato solicitado.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    // verificando usuario_administrador
    $check_admin = db_connection();
    $check_admin = $check_admin->prepare("SELECT usuario_usuario, usuario_clave FROM usuario WHERE usuario_usuario = :user_admin OR usuario_id = :id_user");
    $check_admin->execute([':user_admin' => $admin_user, ':id_user' => $_SESSION['id']]);

    if ($check_admin -> rowCount() == 1) {
        $check_admin = $check_admin -> fetch();

        if ($check_admin['usuario_usuario'] != $admin_user || !password_verify($admin_key, $check_admin['usuario_clave'])) {
            echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El Usuario y/o Contraseña del administrador incorrecta.
                    </div>
                </div>
            </div>
            ';
            exit();
        }

    } else {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El usuario ADMINISTRADOR no existe.
                    </div>
                </div>
            </div>
        ';
    }

    $check_admin = null;

    // Almacenando datos del formulario
    $full_name = limpiar_cadena($_POST["user_name"]);
    $lastname = limpiar_cadena($_POST["user_lastname"]);

    $user_name = limpiar_cadena($_POST["user"]);
    $email = limpiar_cadena($_POST["user_email"]);

    $paswdord = limpiar_cadena($_POST["user_passw"]);
    $password_repeat = limpiar_cadena($_POST["user_passw_repeat"]);

    // Validación campos obligatorios
    if ($full_name == "" || $lastname == "" || $user_name == "" || $email == "") {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrió un error inesperado!</strong><br>
                        No has llenado todos los campos que son obligatorios.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    // Verificando integridad de los datos
    if (verficar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $full_name) || verficar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $lastname)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El NOMBRE y APELLIDOS no cumplen con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    if (verficar_datos("[a-zA-Z0-9]{4,20}", $user_name)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El NOMBRE DE USUARIO no cumple con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

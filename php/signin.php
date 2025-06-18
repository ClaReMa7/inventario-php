<?php
    // Sólo corre si viene del POST
if (!isset($_POST['login_usuario'], $_POST['login_clave'])) {
    return;
}

    // Almacenando datos del formulario
    $user = limpiar_cadena($_POST["login_usuario"]);
    $password = limpiar_cadena($_POST["login_clave"]);

    // verificación campos obligatorios
    if ($user == "" || $password == "") {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        No has llenado todos los campos que son obligatorios.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    // Verificando integridad de los datos
    if (verficar_datos("[a-zA-Z0-9]{4,20}", $user)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        La USUARIO no cumple con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    if (verficar_datos("[a-zA-Z0-9$@.\-]{7,100}", $password)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        La CONTRASEÑA no cumple con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    $connect_login = db_connection();
    $query = $connect_login->prepare("SELECT * FROM usuario WHERE usuario_usuario = :user");
    $query->execute(['user' => $user]);

    if ($query->rowCount() == 1) {
        $datos = $query->fetch(); // Usar un nombre distinto

        if ($datos['usuario_usuario'] == $user && password_verify($password, $datos['usuario_clave'])) {

            $_SESSION['id'] = $datos['usuario_id'];
            $_SESSION['name'] = $datos['usuario_nombre'];
            $_SESSION['lastname'] = $datos['usuario_apellido'];
            $_SESSION['user'] = $datos['usuario_usuario'];
            
            if (headers_sent()) {
                echo "<script> window.location.href='index.php?vista=home'; </script>";
            } else {
                header("Location: index.php?vista=home");
            }

            exit(); 
        }
    }

    echo '
        <div class="columns is-centered">
            <div class="column is-">
                <div class="notification is-danger is-light has-text-centered">
                <strong>¡Ocurrido un error inesperado!</strong><br>
                    NOMBRE DE USUARIO O CONTRASEÑA incorrecto.
                </div>
            </div>
        </div>
    ';

    $connect_login = null;
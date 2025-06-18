<?php
require_once '../includes/session_start.php';
require_once 'main.php';

$id = limpiar_cadena($_POST['usuario_id']);

// Verificar el usuario
$check_user = db_connection();
$check_user = $check_user->prepare("SELECT * FROM usuario WHERE usuario_id = :id");
$check_user->execute([':id' => $id]);

if ($check_user->rowCount() <= 0) {
    echo '
        <div class="alert alert-danger" role="alert">
            <strong>Error:</strong> El usuario no existe o ha sido eliminado.
        ';
    exit();

} else {
    $datos = $check_user->fetch();

}
$check_user = null;

$admin_user = limpiar_cadena($_POST['user_admin']);
$admin_key = limpiar_cadena($_POST['key_admin']);

// Validación campos obligatorios
if ($admin_user == "" || $admin_key == "") {
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

if ($check_admin->rowCount() == 1) {
    $check_admin = $check_admin->fetch();

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

$password = limpiar_cadena($_POST["user_passw"]);
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

// Verificando que a actualizar sea valido
if ($email != "" && $email != $datos['usuario_email']) {

    // Verificando que el email sea valido
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $connect_email = db_connection();
        //prerparamos la consulta SQL para evitar inyecciones, usando un marcador (:email) en lugar de insertar el email directamente
        $querie_email = $connect_email->prepare("SELECT usuario_email FROM usuario WHERE usuario_email =  :email");
        $querie_email->execute(['email' => $email]); //Ejecutamos la consulta pasando el valor del email como parámetro

        // Verificando email 
        if ($querie_email->rowCount() > 0) {
            echo '
                    <div class="columns is-centered">
                        <div class="column is-half">
                            <div class="notification is-danger is-light has-text-centered">
                            <strong>¡Ocurrido un error inesperado!</strong><br>
                                El EMAIL ingresado ya se encuentra registrado, por favor ingrese otro.
                            </div>
                        </div>
                    </div>
                ';
            exit();
        }
        $querie_email = null; // Libera recursos de la consulta preparada 
        $connect_email = null; //Cierre la conexión

    } else {
        //mensaje de error si el email no es valido
        echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            El EMAIL ingresado no tiene un formato valido.
                        </div>
                    </div>
                </div>
            ';
    }

}

//Verificación del usuario
if ($user_name != $datos['usuario_usuario']) {
    $connect_usuario = db_connection();
    $querie_usuario = $connect_usuario->prepare("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = :user");
    $querie_usuario->execute(['user' => $user_name]);

    if ($querie_usuario->rowCount() > 0) {
        echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            El nombre de usuario ingresado ya se encuentra registrado, por favor ingrese otro.
                        </div>
                    </div>
                </div>
            ';
        header("Refresh; url=index.php?vista=user_new");
        exit();
    }
    $querie_usuario = null;
    $connect_usuario = null;
}

// Verificar contraseñas
if ($password != "" || $password_repeat != "") {
    if (verficar_datos("[a-zA-Z0-9$@.\-]{7,100}", $password) || verficar_datos("[a-zA-Z0-9$@.\-]{7,100}", $password_repeat)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        Las Contraseñas no cumplen con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    } else {
        if ($password != $password_repeat) {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            Las contraseñas no coinciden.
                        </div>
                    </div>
                </div>
            ';
            header("Refresh: 2; url=index.php?vista=user_update&user_id_up=$datos[user_id]");
            exit();
        } else {
            // Encriptando la contraseña
            $password = password_hash(
                $password,
                PASSWORD_BCRYPT,
                ["cost" => 10]
            );
        }
    }
} else {
    $password = $datos['usuario_clave'];
}

# Actualizar datos del usuario #
$update_user_data = db_connection();
$update_user_data = $update_user_data->prepare("UPDATE usuario SET 
    usuario_nombre = :nombre, 
    usuario_apellido = :apellido, 
    usuario_usuario = :usuario, 
    usuario_email = :email, 
    usuario_clave = :clave 
    WHERE usuario_id = :id");
$marker =([
    ':nombre' => $full_name,
    ':apellido' => $lastname,
    ':usuario' => $user_name,
    ':email' => $email,
    ':clave' => $password,
    ':id' => $id
]);

if ($update_user_data->execute($marker)) {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-info is-light has-text-centered">
                <strong>¡USUARIO ACTUALIZADO!</strong><br>
                    Los datos del usuario se han actualizado correctamente.
                </div>
            </div>
        </div>
    ';
    header("Refresh: 2; url=index.php?vista=user_update&user_id_up=$datos[usuario_id]");
} else {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-danger is-light has-text-centered">
                <strong>¡Ocurrido un error inesperado!</strong><br>
                    No se pudo actualizar los datos del usuario, por favor intente nuevamente.
                </div>
            </div>
        </div>
    ';

}
$update_user_data = null;

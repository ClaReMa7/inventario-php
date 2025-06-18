<?php
    require "main.php";

    // Almacenando datos del formulario
    $full_name = limpiar_cadena($_POST["user_name"]);
    $lastname = limpiar_cadena($_POST["user_lastname"]);

    $user_name = limpiar_cadena($_POST["user"]);
    $email = limpiar_cadena($_POST["user_email"]);

    $password = limpiar_cadena($_POST["user_passw"]);
    $password_repeat = limpiar_cadena($_POST["user_passw_repeat"]);

    // Validación campos obligatorios
    if ($full_name == "" || $lastname == "" || $user_name == "" || $email == "" || $password == "" || $password_repeat == "") {
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
    }

    // Verificando email
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $connect_email = db_connection();
        //prerparamos la consulta SQL para evitar inyecciones, usando un marcador (:email) en lugar de insertar el email directamente
        $querie_email = $connect_email->prepare("SELECT usuario_email FROM usuario WHERE usuario_email =  :email");
        $querie_email->execute(['email' => $email]); //Ejecutamos la consulta pasando el valor del email como parámetro
        
        //Vericamos si el email ya se encuentra registrado
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
        
    }else {
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

        //Verificación del usuario
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

        // Verificar contraseñas iguales
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
            header("Refresh: 2; url=index.php?vista=user_new");
            exit();
        } else {
            // Encriptando la contraseña
            $password = password_hash($password, PASSWORD_BCRYPT, 
        ["cost" => 10]);
        }

        // Guardando datos 
        try {
        $save_user = db_connection();
        $sql = "INSERT INTO usuario(usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, usuario_email) VALUES(:usuario_nombre, :usuario_apellido, :usuario_usuario, :usuario_clave, :usuario_email)"; 
        $query = $save_user->prepare($sql);

        $marker = [
            ":usuario_nombre" => $full_name,
            ":usuario_apellido" => $lastname,
            ":usuario_usuario" => $user_name,
            ":usuario_clave" => $password,
            ":usuario_email" => $email
        ];
        $query->execute($marker);

        if ($query->rowCount() == 1) {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-info is-light has-text-centered">
                        <strong>¡USUARIO REGISTRADO!</strong><br>
                            El usuario se ha registrado correctamente.
                        </div>
                    </div>
                </div>
            ';
            header("Refresh: 2; url=index.php?vista=user_new");
            exit();

        } else {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            El usuario no se ha registrado correctamente. Por favor intente nuevamente.
                        </div>
                    </div>
                </div>
            ';

        }
        $save_user = null;
        } catch (PDOException $e) {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>Error de base de datos:</strong><br>
                            ' . $e->getMessage() . '
                        </div>
                    </div>
                </div>
            ';
        }
        


    

    
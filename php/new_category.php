<?php
    require_once 'main.php';

    # Almacenando datos del formulario
    $name_category = limpiar_cadena($_POST["categoria_nombre"]);
    $ubication = limpiar_cadena($_POST["categoria_ubicacion"]);

    // Validación campos obligatorios
    if ($name_category == "") {
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
    if (verficar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}", $name_category)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El NOMBRE no cumple con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    } else if ($ubication != "") {
        if (verficar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}", $ubication)) {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            La UBICACIÓN no cumple con el formato requerido.
                        </div>
                    </div>
                </div>
            ';
            exit();
        }
    }

    //Validación nombre de categoría
        $connect_category = db_connection();
        $check_category_name = $connect_category->prepare("SELECT categoria_nombre FROM categoria WHERE categoria_nombre = :neme_category");
        $check_category_name->execute([':neme_category' => $name_category]);
        
        if ($check_category_name->rowCount() > 0) {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                        El NOMBRE de la categoría ingresado ya se encuentra registrado, por favor ingrese otro.
                        </div>
                    </div>
                </div>
            ';
            header("Refresh; url=index.php?vista=user_new");
            exit();
        }
        $check_category_name = null;
        $connect_category = null; 

        // Guardar datos en la base de datos
        try {
        $save_category = db_connection();
        $query_category = $save_category->prepare("INSERT INTO categoria (categoria_nombre, categoria_ubicacion) VALUES (:name_category, :ubication_category)"); 

        $marker = [
            ":name_category" => $name_category,
            ":ubication_category" => $ubication
        ];

        $query_category->execute($marker);

        // Verificar si se guardó correctamente
        if ($query_category->rowCount() == 1) {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-info is-light has-text-centered">
                        <strong>¡CATEGORIA REGISTRADA!</strong><br>
                            La categoria se ha registrado correctamente.
                        </div>
                    </div>
                </div>
            ';
            header("Refresh: 2; url=index.php?vista=category_new");
            exit();

        } else {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            No se ha registrado la categoria. Por favor intente nuevamente.
                        </div>
                    </div>
                </div>
            ';

        }
        $save_category = null;
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


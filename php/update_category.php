<?php
    require_once 'main.php';

    $id = limpiar_cadena($_POST['categoria_id']);

    // Verificar la categoria
    $check_categoria = db_connection();
    $check_categoria = $check_categoria->prepare("SELECT * FROM categoria WHERE categoria_id = :id");
    $check_categoria->execute([':id' => $id]);

    if ($check_categoria->rowCount() <= 0) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="alert alert-danger" role="alert">
                        <strong>¡Ocurrido un error inesperado!:</strong> La CATEGORIA no existe o ha sido eliminada.
                    </div>
                </div>
            </div>
            ';
        exit();

    } else {
        $datos = $check_categoria->fetch();

    }
    $check_categoria = null;

    # Almacenando datos del formulario
    $name_category = limpiar_cadena($_POST["categoria_nombre"]);
    $ubication = limpiar_cadena($_POST["categoria_ubicacion"]);

    # Validación campos obligatorios
    if ($name_category == "") {
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
    if ($name_category != $datos['categoria_nombre']) {

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
            exit();
        }
        $check_category_name = null;
        $connect_category = null; 
    }

    
    # Actualizar datos #
    $update_cat = db_connection();
    $update_cat = $update_cat->prepare("UPDATE categoria SET 
        categoria_nombre = :name_cat, 
        categoria_ubicacion = :ubicacion
        WHERE categoria_id = :id");

        $marker =([
        ':name_cat' => $name_category,
        ':ubicacion' => $ubication,
        ':id' => $id
    ]);;

    if ($update_cat ->execute($marker)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-info is-light has-text-centered">
                        <strong>¡Actualización exitosa!</strong><br>
                        Los datos de la categoría se han actualizado correctamente.
                    </div>
                </div>
            </div>
        ';
        
    } else {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                        No se ha podido actualizar los datos de la categoría, por favor intente nuevamente.
                    </div>
                </div>
            </div>
        ';
    }
    
    $update_cat = null;




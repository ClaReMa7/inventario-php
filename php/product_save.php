<?php

    require_once '../includes/session_start.php';
    require_once 'main.php';

    // Almacenando datos
    $code = limpiar_cadena($_POST["producto_codigo"]);
    $product_name = limpiar_cadena($_POST["producto_nombre"]);

    $cost = limpiar_cadena($_POST["producto_precio"]);
    $stock = limpiar_cadena($_POST["producto_stock"]);
    $category = limpiar_cadena($_POST["producto_categoria"]);

    // Validación campos obligatorios
    if ($code == "" || $product_name == "" || $cost == "" || $stock == "" || $category == "") {
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
    if (verficar_datos("[a-zA-Z0-9- ]{1,70}", $code)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El CÓDIGO no cumple con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    } 

    if (verficar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $product_name)) {
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
    }

    if (verficar_datos("[0-9.]{1,25}", $cost)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El PRECIO no cumple con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    if (verficar_datos("[0-9]{1,25}", $stock)) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El STOCK no cumple con el formato requerido.
                    </div>
                </div>
            </div>
        ';
        exit();
    }

    // Verificando código de barra
    $check_code = db_connection();
    $check_code = $check_code->prepare("SELECT producto_codigo FROM producto WHERE producto_codigo = :code");
    $check_code->execute([':code' => $code]);
    $check_code->execute();
    if ($check_code->rowCount() > 0) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El CÓDIGO de barra ingresado ya se encuentra registrad, por favor ingrese otro.
                    </div>
                </div>
            </div>
        ';
        exit();
    }
    $check_code = null; // Libera recursos de la consulta preparada 

    // Verificando nombre del producto
    $check_name = db_connection();
    $check_name = $check_name->prepare("SELECT producto_nombre FROM producto WHERE producto_nombre = :name_product");
    $check_name->execute([':name_product' => $product_name]);
    
    if ($check_name->rowCount() > 0) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        El NOMBRE ingresado ya se encuentra registrado, por favor ingrese otro.
                    </div>
                </div>
            </div>
        ';
        exit();
    }
    $check_name = null; // Libera recursos de la consulta preparada

    // Verificando categoría
    $check_category = db_connection();
    $check_category = $check_category->prepare("SELECT categoria_id FROM categoria WHERE categoria_id = :category");
    $check_category->execute(['category' => $category]);
    
    if ($check_category->rowCount() == 0) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrido un error inesperado!</strong><br>
                        La CATEGORIA ingresada no se encuentra registrada..
                    </div>
                </div>
            </div>
        ';
        exit();
    }
    $check_category = null; // Libera recursos de la consulta preparada


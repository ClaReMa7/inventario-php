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
                        Tosdos los campos son obligatorios.
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

    // Directorio de imagenes
    $img_dir = "../img/product";

    // Comprobar si se seleccionó una imagen
    if ($_FILES['producto_foto']['name'] !=""  && $_FILES['producto_foto']['size'] > 0) {

        // creando el directorio si no existe
        if (!file_exists($img_dir)) {
            if (!mkdir($img_dir, 0777)) {
                echo '
                    <div class="columns is-centered">
                        <div class="column is-half">
                            <div class="notification is-danger is-light has-text-centered">
                            <strong>¡Ocurrido un error inesperado!</strong><br>
                                No se pudo crear el directorio de imágenes.
                            </div>
                        </div>
                    </div>
                ';
                exit();
            }
        }
        // Verificando  formato de imagen
        if (mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png") {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            El formato de la imagen no es válido, solo se permiten imágenes en formato JPG o PNG.
                        </div>
                    </div>
                </div>
            ';
            exit();

        }

        // Verificando tamaño de imagen
        if (($_FILES['producto_foto']['size']/1024) > 3072) { // Tamaño en KB
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            La imagen supera el tamaño máximo permitido de 3MB.
                        </div>
                    </div>
                </div>                                                                                  
            ';  
            exit();
        }
        // Extensión de la imagen
        switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
            case 'image/jpeg':
                $ext_img = ".jpg";
                break;
            case 'image/png':
                $ext_img = ".png";
                break;

        }

        chmod($img_dir, 0777); // Permisos del directorio de escritura y lectura
        $img_name = renombrar_imagen(  $product_name);
        $image = $img_name.$ext_img;

        // moviendo la imagen al directorio 
        if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir."/".$image)) {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            No se pudo mover la imagen al directorio.
                        </div>
                    </div>
                </div>
            ';
            exit();
        } 

    } else {
        $image = "";
    }

    // Guardando datos del producto
    try {
        $save_product = db_connection();
        $save_product = $save_product->prepare("INSERT INTO producto(producto_codigo, producto_nombre, producto_precio, producto_stock, producto_foto, categoria_id, usuario_id) VALUES(:codigo, :nombre, :precio, :stock, :foto, :id_cat, :usuario)"); 

        $marker = [
            ":codigo" => $code,
            ":nombre" => $product_name,
            ":precio" => $cost,
            ":stock" => $stock,
            ":foto" => $image,
            ":id_cat" => $category,
            ":usuario" => $_SESSION['id']

        ];
        $save_product->execute($marker);

        if ($save_product->rowCount() == 1) {
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-info is-light has-text-centered">
                        <strong>¡PRODUCTO REGISTRADO!</strong><br>
                            El producto se ha registrado correctamente.
                        </div>
                    </div>
                </div>
            ';
            header("Refresh: 2; url=index.php?vista=product_new");
            exit();

        } else {
            if (is_file($img_dir.$image)) {
                chmod($img_dir.$image, 0777); 
                unlink($img_dir.$image); // Eliminar imagen si no se guardó el producto
            }
            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrido un error inesperado!</strong><br>
                            No se pudo registrar el producto.
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
        


    


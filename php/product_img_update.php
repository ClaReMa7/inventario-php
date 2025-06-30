<?php
require_once "main.php";

/*== Almacenando datos ==*/
$product_id = limpiar_cadena($_POST['img_up_id']);

/*== Verificando producto ==*/
$check_product = db_connection();
$check_product = $check_product->prepare("SELECT * FROM producto WHERE producto_id = :id");
$check_product->execute([':id' => $product_id]);

if ($check_product->rowCount() == 1) {
    $datos = $check_product->fetch();
} else {
    echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        La imagen del PRODUCTO que intenta actualizar no existe
                    </div>
                </div>
            </div>  
        ';
    exit();
}
$check_product = null;


/*== Comprobando si se ha seleccionado una imagen ==*/
if ($_FILES['producto_foto']['name'] == "" || $_FILES['producto_foto']['size'] == 0) {
    echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        No ha seleccionado ninguna imagen o foto
                    </div>  
                </div>
            </div>
        ';
    exit();
}


/* Directorios de imagenes */
$img_dir = '../img/product/';


/* Creando directorio de imagenes */
if (!file_exists($img_dir)) {
    if (!mkdir($img_dir, 0777)) {
        echo '
                <div class="columns is-centered">    
                    <div class="column is-half">
                        <div class="notification is-danger is-light has-text-centered">
                            <strong>¡Ocurrio un error inesperado!</strong><br>
                            Error al crear el directorio de imagenes
                        </div>  
                    </div>
                </div>
            ';
        exit();
    }
}


/* Cambiando permisos al directorio */
chmod($img_dir, 0777);


/* Comprobando formato de las imagenes */
if (mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png") {
    echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        La imagen que ha seleccionado es de un formato que no está permitido
                    </div>  
                </div>
            </div>
        ';
    exit();
}


/* Comprobando que la imagen no supere el peso permitido */
if (($_FILES['producto_foto']['size'] / 1024) > 3072) {
    echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        La imagen que ha seleccionado supera el límite de peso permitido
                    </div>  
                </div>  
            </div>
        ';
    exit();
}


/* extencion de las imagenes */
switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
    case 'image/jpeg':
        $ext_img = ".jpg";
        break;
    case 'image/png':
        $ext_img= ".png";
        break;
}

/* Nombre de la imagen */
$img_nombre = renombrar_imagen($datos['producto_nombre']);

/* Nombre final de la imagen */
$foto = $img_nombre . $ext_img;

/* Moviendo imagen al directorio */
if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir . $foto)) {
    echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        No podemos subir la imagen al sistema en este momento, por favor intente nuevamente
                    </div>  
                </div>
            </div>
        ';
    exit();
}


/* Eliminando la imagen anterior */
if (is_file($img_dir . $datos['producto_foto']) && $datos['producto_foto'] != $foto) {

    chmod($img_dir . $datos['producto_foto'], 0777);
    unlink($img_dir . $datos['producto_foto']);
}


/*== Actualizando datos ==*/
$update_product = db_connection();;
$update_product = $update_product->prepare("UPDATE producto SET producto_foto=:foto WHERE producto_id=:id");

$marker = [
    ":foto" => $foto,
    ":id" => $product_id
];

if ($update_product->execute($marker)) {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-info is-light has-text-centered">
                    <strong>¡IMAGEN O FOTO ACTUALIZADA!</strong><br>
                    La imagen del producto ha sido actualizada exitosamente, pulse Aceptar para recargar los cambios.

                    <p class="has-text-centered pt-5 pb-5">
                        <a href="index.php?vista=product_img&product_id_up=' . $product_id . '" class="button is-link is-rounded">Aceptar</a>
                    </p">
                </div>  
            </div>
        </div>
        ';
} else {

    if (is_file($img_dir . $foto)) {
        chmod($img_dir . $foto, 0777);
        unlink($img_dir . $foto);
    }

    echo '
            <div class="columns is-centered">
                <div class="column is-half">    
                    <div class="notification is-warning is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        No podemos subir la imagen al sistema en este momento, por favor intente nuevamente
                    </div>  
                </div>  
            </div>
        ';
}
$update_product = null;
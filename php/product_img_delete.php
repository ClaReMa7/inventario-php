<?php
require_once "main.php";

/*== Almacenando datos ==*/
$product_id = limpiar_cadena($_POST['img_del_id']);

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
                        La imagen del PRODUCTO que intenta eliminar no existe
                    </div>
                </div>
            </div>
        ';
    exit();
}
$check_product = null;


/* Directorios de imagenes */
$img_dir = '../img/product/';

/* Cambiando permisos al directorio */
chmod($img_dir, 0777);


/* Eliminando la imagen */
if (is_file($img_dir . $datos['producto_foto'])) {

    chmod($img_dir . $datos['producto_foto'], 0777);

    if (!unlink($img_dir . $datos['producto_foto'])) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        Error al intentar eliminar la imagen del producto, por favor intente nuevamente
                    </div>
                </div>
            </div>
	        ';
        exit();
    }
}


/*== Actualizando datos ==*/
$update_product = db_connection();
$update_product = $update_product->prepare("UPDATE producto SET producto_foto=:foto WHERE producto_id=:id");

$marker = [
    ":foto" => "",
    ":id" => $product_id
];

if ($update_product->execute($marker)) {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-info is-light has-text-centered">
                    <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
                    La imagen del producto ha sido eliminada exitosamente, pulse Aceptar para recargar los cambios.

                    <p class="has-text-centered pt-5 pb-5">
                        <a href="index.php?vista=product_img&product_id_up=' . $product_id . '" class="button is-info is-rounded">Aceptar</a>
                    </p">
                </div>
            </div>
        </div>
        ';
} else {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-warning is-light has-text-centered">
                    <strong>¡IMAGEN O FOTO ELIMINADA!</strong><br>
                    Ocurrieron algunos inconvenientes, sin embargo la imagen del producto ha sido eliminada, pulse Aceptar para recargar los cambios.

                    <p class="has-text-centered pt-5 pb-5">
                        <a href="index.php?vista=product_img&product_id_up=' . $product_id . '" class="button is-warning is-rounded">Aceptar</a>
                    </p">
                </div>
            </div>
        </div>
        ';
}
$update_product = null;
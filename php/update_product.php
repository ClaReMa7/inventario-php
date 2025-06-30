<?php
require_once "main.php";

/*== Almacenando id ==*/
$id = limpiar_cadena($_POST['producto_id']);


/*== Verificando producto ==*/
$check_producto = db_connection();
$check_producto = $check_producto->prepare("SELECT * FROM producto WHERE producto_id = :id");
$check_producto->execute([':id' => $id]);

if ($check_producto->rowCount() <= 0) {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El producto no existe en el sistema
                </div>
            </div>
        </div>
        ';
    exit();
} else {
    $datos = $check_producto->fetch();
}
$check_producto = null;


/*== Almacenando datos ==*/
$codigo = limpiar_cadena($_POST['producto_codigo']);
$nombre = limpiar_cadena($_POST['producto_nombre']);

$precio = limpiar_cadena($_POST['producto_precio']);
$stock = limpiar_cadena($_POST['producto_stock']);
$categoria = limpiar_cadena($_POST['producto_categoria']);


/*== Verificando campos obligatorios ==*/
if ($codigo == "" || $nombre == "" || $precio == "" || $stock == "" || $categoria == "") {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No has llenado todos los campos que son obligatorios
                </div>
            </div>
        </div>
        ';
    exit();
}


/*== Verificando integridad de los datos ==*/
if (verficar_datos("[a-zA-Z0-9- ]{1,70}", $codigo)) {
    echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El CODIGO de BARRAS no coincide con el formato solicitado
                    </div>
                </div>
            </div>
        ';
    exit();
}

if (verficar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $nombre)) {
    echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El NOMBRE no coincide con el formato solicitado
                    </div>
                </div>
            </div>
        ';
    exit();
}

if (verficar_datos("[0-9.]{1,25}", $precio)) {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El PRECIO no coincide con el formato solicitado
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
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    El STOCK no coincide con el formato solicitado
                </div>
            </div>
        </div>
        ';
    exit();
}


/*== Verificando codigo ==*/
if ($codigo != $datos['producto_codigo']) {
    $check_codigo = db_connection();
    $check_codigo = $check_codigo->prepare("SELECT producto_codigo FROM producto WHERE producto_codigo=:codigo");
    $check_codigo->execute([':codigo' => $codigo]);

    if ($check_codigo->rowCount() > 0) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El CODIGO de BARRAS ingresado ya se encuentra registrado, por favor elija otro
                    </div>
                </div>
            </div>
            ';
        exit();
    }
    $check_codigo = null;   
}


/*== Verificando nombre ==*/
if ($nombre != $datos['producto_nombre']) {
    $check_nombre = db_connection();
    $check_nombre = $check_nombre->prepare("SELECT producto_nombre FROM producto WHERE producto_nombre=:nombre");
    $check_nombre->execute([':nombre' => $nombre]);

    if ($check_nombre->rowCount() > 0) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
                    </div>
                </div>
            </div>
        ';
        exit();
    }
    $check_nombre = null;
}


/*== Verificando categoria ==*/
if ($categoria != $datos['categoria_id']) {
    $check_categoria = db_connection();
    $check_categoria = $check_categoria->prepare("SELECT categoria_id FROM categoria WHERE categoria_id=:categoria");
    $check_categoria->execute([':categoria' => $categoria]);

    if ($check_categoria->rowCount() <= 0) {
        echo '
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="notification is-danger is-light has-text-centered">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        La categoría seleccionada no existe
                    </div>
                </div>
            </div>

        ';
        exit();
    }
    $check_categoria = null;
}


/*== Actualizando datos ==*/
$update_product = db_connection();
$update_product = $update_product->prepare("UPDATE producto SET producto_codigo=:codigo,producto_nombre=:nombre,producto_precio=:precio,producto_stock=:stock,categoria_id=:categoria WHERE producto_id=:id");

$marker = [
    ":codigo" => $codigo,
    ":nombre" => $nombre,
    ":precio" => $precio,
    ":stock" => $stock,
    ":categoria" => $categoria,
    ":id" => $id
];


if ($update_product->execute($marker)) {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-info is-light has-text-centered">
                    <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
                    El producto se actualizo con exito
                </div>
            </div>
        </div>
        ';
} else {
    echo '
        <div class="columns is-centered">
            <div class="column is-half">
                <div class="notification is-danger is-light has-text-centered">
                    <strong>¡Ocurrio un error inesperado!</strong><br>
                    No se pudo actualizar el producto, por favor intente nuevamente
                </div>
            </div>
        </div>
        ';
}
$update_product = null;
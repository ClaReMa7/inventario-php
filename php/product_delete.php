<?php
/*== Almacenando datos ==*/
$product_id_del = limpiar_cadena($_GET['product_id_del']);

/*== Verificando producto ==*/
$check_producto = db_connection();
$check_producto = $check_producto->prepare("SELECT * FROM producto WHERE producto_id = :id");
$check_producto->execute([':id' => $product_id_del]);

if ($check_producto->rowCount() == 1) {

    $datos = $check_producto->fetch();

    $delete_producto = db_connection();
    $delete_producto = $delete_producto->prepare("DELETE FROM producto WHERE producto_id=:id");

    $delete_producto->execute([":id" => $product_id_del]);

    if ($delete_producto->rowCount() == 1) {

        if (is_file("./img/product/" . $datos['producto_foto'])) {
            chmod("./img/product/" . $datos['producto_foto'], 0777);
            unlink("./img/product/" . $datos['producto_foto']);
        }

        $_SESSION['mensaje'] = [
                'tipo' => 'is-info',
                'titulo' => '¡Producto Eliminado!',
                'contenido' => 'El PRODUCTO se eliminó correctamente.'
                ];
    } else {
        $_SESSION['mensaje'] = [
                'tipo' => 'is-danger',
                'titulo' => '¡Error al Eliminar!',
                'contenido' => 'No hemos podido eliminar el producto, por favor intenta nuevamente.'
        ];
    }
    
    } else {
    $_SESSION['mensaje'] = [
                'tipo' => 'is-danger',
                'titulo' => '¡Error al Eliminar!',
                'contenido' => 'El PRODUCTO que intenta eliminar no existe.'
            ];
}

// Limpiar conexiones
if (isset($delete_producto)) $delete_producto = null;
$check_producto = null;


// Redirigir al listado
header("Location: index.php?vista=product_list");
exit();


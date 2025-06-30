<div class="container is-fluid mb-4">
    <div class="has-text-left">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Productos</h1>
        <h2 class="subtitle is-5 has-text-grey">Actualizar producto</h2>
        <div class="divider-aligned-left "></div>
    </div>
</div>

<div class="container pb-6 pt-6">
    <?php
    include "./includes/btn_back.php";

    require_once "./php/main.php";

    $id = (isset($_GET['product_id_up'])) ? $_GET['product_id_up'] : 0;
    $id = limpiar_cadena($id);

    /*== Verificando producto ==*/
    $check_product = db_connection();
    $check_product = $check_product->prepare("SELECT * FROM producto WHERE producto_id = :id");
    $check_product->execute([':id' => $id]);

    if ($check_product->rowCount() > 0) {
        $datos = $check_product->fetch();
        ?>

        <div class="form-rest mb-6 mt-6"></div>

        <h2 class="title has-text-centered"><?php echo $datos['producto_nombre']; ?></h2>

        <form action="./php/update_product.php" method="POST" class="FormularioAjax" autocomplete="off">

            <input type="hidden" name="producto_id" value="<?php echo $datos['producto_id']; ?>" required>

            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Código de barra</label>
                        <input class="input" type="text" name="producto_codigo" pattern="[a-zA-Z0-9- ]{1,70}" maxlength="70"
                            required value="<?php echo $datos['producto_codigo']; ?>">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Nombre</label>
                        <input class="input" type="text" name="producto_nombre"
                            pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,50}" maxlength="50" required
                            value="<?php echo $datos['producto_nombre']; ?>">
                    </div>
                </div>
            </div>
            <div class="columns">
                <div class="column">
                    <div class="control">
                        <label>Precio</label>
                        <input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25"
                            required value="<?php echo $datos['producto_precio']; ?>">
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <label>Stock</label>
                        <input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25" required
                            value="<?php echo $datos['producto_stock']; ?>">
                    </div>
                </div>
                <div class="column">
                    <label>Categoría</label><br>
                    <div class="select is-rounded border-none">
                        <select name="producto_categoria">
                            <?php
                            $categorias = db_connection();
                            $categorias = $categorias->query("SELECT * FROM categoria");
                            if ($categorias->rowCount() > 0) {
                                $categorias = $categorias->fetchAll();
                                foreach ($categorias as $row) {
                                    if ($datos['categoria_id'] == $row['categoria_id']) {
                                        echo '<option value="' . $row['categoria_id'] . '" selected="" >' . $row['categoria_nombre'] . ' (Actual)</option>';
                                    } else {
                                        echo '<option value="' . $row['categoria_id'] . '" >' . $row['categoria_nombre'] . '</option>';
                                    }
                                }
                            }
                            $categorias = null;
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <p class="has-text-centered">
                <button type="submit" class="button button-custom is-primary is-rounded">Actualizar</button>
            </p>
        </form>
    <?php
    } else {
        include "./includes/error_alert.php";
    }
    $check_producto = null;
    ?>
</div>
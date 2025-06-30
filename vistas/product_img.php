<div class="container is-fluid mb-4">
    <div class="divider-container">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Productos</h1>
        <h2 class="subtitle is-5 has-text-grey">Actualizar imagen de producto</h2>
        <div class="divider-aligned-left "></div>
    </div>
</div>

<div class="container pb-6 pt-6">
    <?php include "./includes/btn_back.php"; ?>
    <?php require_once "./php/main.php"; ?>

    <?php
    $id = (isset($_GET['product_id_up'])) ? $_GET['product_id_up'] : 0;
    
    /*== Verificando producto ==*/
    $check_product = db_connection();
    $check_product = $check_product->prepare("SELECT * FROM producto WHERE producto_id = :id");
    $check_product->execute([':id' => $id]);
    
    if ($check_product->rowCount() > 0) {
        $datos = $check_product->fetch();
    ?>
    
    <div class="form-rest "></div>
    
    <div class="columns is-vcentered">
        <!-- Columna de la imagen actual -->
        <div class="column is-two-fifths">
            <div class="card">
                <div class="card-content">
                    <h3 class="title is-5 has-text-centered mb-4">Imagen Actual</h3>
                    
                    <?php if (is_file("./img/product/" . $datos['producto_foto'])) { ?>
                        <figure class="image is-4by3 mb-5">
                            <img src="./img/product/<?php echo $datos['producto_foto']; ?>" style="object-fit: contain; border-radius: 6px; border: 1px solid #eee;">
                        </figure>
                        <form class="FormularioAjax" action="./php/product_img_delete.php" method="POST" autocomplete="off">
                            <input type="hidden" name="img_del_id" value="<?php echo $datos['producto_id']; ?>">
                            <div class="has-text-centered">
                                <button type="submit" class="button btn_img_delete is-danger is-light is-rounded ">
                                    <span class="icon"><i class="fas fa-trash"></i></span>
                                    <span>Eliminar</span>
                                </button>
                            </div>
                        </form>
                    <?php } else { ?>
                        <figure class="image is-4by3 mb-5">
                            <img src="./img/img.jpg" style="object-fit: contain; border-radius: 6px; border: 1px solid #eee;">
                        </figure>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <!-- Columna del formulario de actualización -->
        <div class="column">
            <div class="card">
                <div class="card-content">
                    <h4 class="title is-4 has-text-centered mb-5"><?php echo $datos['producto_nombre']; ?></h4>
                    
                    <form class="FormularioAjax" action="./php/product_img_update.php" method="POST" enctype="multipart/form-data" autocomplete="off">
                        <input type="hidden" name="img_up_id" value="<?php echo $datos['producto_id']; ?>">
                        
                        <div class="field">
                            <label class="label has-text-centered mb-4">Seleccionar nueva imagen</label>
                            <div class="file has-name is-centered is-boxed">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">Buscar archivo...</span>
                                    </span>
                                    <span class="file-name">Formatos: JPG, JPEG, PNG (MAX 3MB)</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="has-text-centered mt-6">
                            <button type="submit" class="button button-custom is-primary  is-rounded ">
                                <span class="icon"><i class="fas fa-save"></i></span>
                                <span>Actualizar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <?php
    } else {
        include "./includes/error_alert.php";
    }
    $check_product = null;
    ?>
</div>

<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #f5f5f5;
        height: 100%;
    }
    .image.is-4by3 {
        padding-top: 75%;
        position: relative;
    }
    .image.is-4by3 img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    .file.is-boxed .file-cta {
        flex-direction: column;
        height: auto;
        padding: 1.5em;
    }
    .file.is-boxed .file-icon {
        margin-bottom: 0.5em;
    }
</style>
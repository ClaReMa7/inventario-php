<div class="container is-fluid mb-6">
    <div class="has-text-left">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Productos</h1>
        <h2 class="subtitle is-5 has-text-grey">Nuevo producto</h2>
        <div class="divider-aligned-left "></div>
    </div>
</div>

<div class="container pb-6 pt-6">
    <?php
    require_once './php/main.php';

    // Mostrar mensaje si existe
        if (isset($_SESSION['mensaje']) && isset($_SESSION['mensaje']['tipo'])) {
            $tipo = $_SESSION['mensaje']['tipo'];
            $titulo = $_SESSION['mensaje']['titulo'];
            $contenido = $_SESSION['mensaje']['contenido'];

            echo '
                <div class="columns is-centered">
                    <div class="column is-half">
                        <div class="notification '.$tipo.' is-light has-text-centered">
                            <strong>'.strtoupper($titulo).'</strong><br>
                            '.$contenido.'
                        </div>
                    </div>
                </div>
                ';
                unset($_SESSION['mensaje']); // Limpiamos el mensaje después de mostrarlo
        }
    ?>

    <div class="form-rest mb-6 mt-6"></div>

    <form action="./php/product_save.php" method="POST" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data">
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Código de barra</label>
                    <input class="input" type="text" name="producto_codigo" pattern="[\-a-zA-Z0-9 ]{1,70}" maxlength="70"
                        required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Nombre</label>
                    <input class="input" type="text" name="producto_nombre"
                        pattern="[\-a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,50}" maxlength="50" required>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Precio</label>
                    <input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25"
                        required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Stock</label>
                    <input class="input" type="text" name="producto_stock" pattern="[0-9]{1,25}" maxlength="25"
                        required>
                </div>
            </div>
            <div class="column">
                <label>Categoría</label><br>
                <div class="select is-rounded">
                    <select name="producto_categoria">
                        <option class="option" value="" selected="">Seleccione una opción</option>
                        <?php 
                            $categories = db_connection();
                            $categories = $categories->prepare("SELECT * FROM categoria");
                            $categories->execute();
                            if ($categories->rowCount() > 0) {
                                $categories = $categories->fetchAll();
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category['categoria_id'] . '">' . $category['categoria_nombre'] . '</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="columns">
            <div class="column">
                <label>Foto o imagen del producto</label><br>
                <div class="file is-small has-name">
                    <label class="file-label">
                        <input class="file-input" type="file" name="producto_foto" accept=".jpg, .png, .jpeg">
                        <span class="file-cta">
                            <span class="file-label">Imagen</span>
                        </span>
                        <span class="file-name">JPG, JPEG, PNG. (MAX 3MB)</span>
                    </label>
                </div>
            </div>
        </div>
        <p class="has-text-centered">
            <button type="submit" class="button is-info is-rounded">Guardar</button>
        </p>
    </form>
</div>
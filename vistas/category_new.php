<div class="container is-fluid mb-6">
    <div class="has-text-left">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Categorías</h1>
        <h2 class="subtitle is-5 has-text-grey">Nueva categoría</h2>
        <div class="divider-aligned-left "></div>
    </div>
</div>

<div class="container pb-6 pt-6">

    <div class="form-rest mb-6 mt-6"></div>

    <form action="./php/new_category.php" method="POST" class="FormularioAjax" autocomplete="off">
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label class="label" >Nombre</label>
                    <input class="input" type="text" name="categoria_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}"
                        maxlength="50" required>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label class="label" >Ubicación</label>
                    <input class="input" type="text" name="categoria_ubicacion"
                        pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}" maxlength="150">
                </div>
            </div>
        </div>
        <p class="has-text-centered">
            <button type="submit" class="button button-custom is-info is-rounded">Guardar</button>
        </p>
    </form>
</div>
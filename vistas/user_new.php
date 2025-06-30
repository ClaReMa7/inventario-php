<!-- Formulario de Registro -->
<div class="container is-fluid mb-6">
    <div class="has-text-left">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Usuario</h1>
        <h2 class="subtitle is-5 has-text-grey">Nuevo usuario</h2>
        <div class="divider-aligned-left "></div>
    </div>
</div>

<div class="form-rest mb-6 mt-6"></div>

    <form action="./php/user_save.php" method="POST" class="FormularioAjax form-register box" autocomplete="off">
        <div class="columns is-multiline">

        <!-- Columna izquierda -->
            <div class="column is-half">
                <div class="field">
                    <label class="label">Nombres</label>
                    <div class="control">
                        <input class="input" type="text" name="user_name" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                    </div>
                </div>
            </div>  

            <div class="column is-half">
                <div class="field">
                    <label class="label">Apellidos</label>
                    <div class="control">
                        <input class="input" type="text" name="user_lastname" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
                    </div>
                </div>
            </div>

            <div class="column is-half">
                <div class="field">
                    <label class="label">Usuario</label>
                    <div class="control has-icons-right">
                        <input class="input" type="text" name="user" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required >
                        <span class="icon is-small is-right">
                            <i class="fas fa-user"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            
            <div class="column is-half">
                <div class="field">
                    <label class="label">Correo Electrónico</label>
                    <div class="control has-icons-right">
                        <input class="input" type="email" name="user_email" maxlength="70" required>
                        <span class="icon is-small is-right">
                            <i class="fas fa-envelope"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="column is-half">
                <div class="field">
                    <label class="label">Contraseña</label>
                    <div class="control has-icons-right">
                        <input class="input" type="password" name="user_passw" pattern="[a-zA-Z0-9$@.\-]{7,100}" maxlength="100" required>
                        <span class="icon is-small is-right">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="column is-half">
                <div class="field">
                    <label class="label">Repetir Contraseña</label>
                    <div class="control has-icons-right">
                        <input class="input" type="password" name="user_passw_repeat" pattern="[a-zA-Z0-9$@.\-]{7,100}" maxlength="100" required>
                        <span class="icon is-small is-right">
                            <i class="fas fa-lock"></i>
                        </span>
                    </div>
                </div>
            </div>

    </div>

    <!-- Botones -->
    <div class="column is-full has-text-centered">
            <button type="submit" class="button button-custom button-save is-primary is-rounded">Guardar</button>

            <button class="button button-custom button-cancel is-rounded" type="reset">Cancelar</button>
    </div>
</form>
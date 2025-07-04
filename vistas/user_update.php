<?php
require_once './php/main.php';

$id = isset($_GET['user_id_up']) ? $_GET['user_id_up'] : 0;
$id = limpiar_cadena($id);

?>
<div class="container is-fluid mb-6">
	<?php if ($id == $_SESSION['id']) { ?>
		<h1 class="title">Mi cuenta</h1>
		<h2 class="subtitle">Actualizar datos de cuenta</h2>
	<?php } else { ?>
		<div class="container is-fluid mb-4">
    <div class="has-text-left">
        <h1 class="title is-2 has-text-weight-bold has-text-info">Usuario</h1>
        <h2 class="subtitle is-5 has-text-grey">Actualizar usuario</h2>
        <div class="divider-aligned-left "></div>
    </div>
</div>
	<?php } ?>
</div>

<div class="container pb-6 pt-6">
	<?php
	include './includes/btn_back.php';

	$check_user = db_connection();
	$check_user = $check_user->prepare("SELECT * FROM usuario WHERE usuario_id = :id");
	$check_user->execute([':id' => $id]);

	if ($check_user->rowCount() > 0) {
		$dates = $check_user->fetch();
		?>

		<div class="form-rest mb-6 mt-6"></div>

		<form action="./php/update_user.php" method="POST" class="FormularioAjax" autocomplete="off">

			<input type="hidden" value="<?php echo $dates['usuario_id']; ?>" name="usuario_id" required>

			<div class="columns">
				<div class="column">
					<div class="control">
						<label>Nombres</label>
						<input class="input" type="text" name="user_name" value="<?php echo $dates['usuario_nombre']; ?>"
							pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Apellidos</label>
						<input class="input" type="text" name="user_lastname" value="<?php echo $dates['usuario_apellido']; ?>"
							pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required>
					</div>
				</div>
			</div>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label>Usuario</label>
						<input class="input" type="text" name="user" value="<?php echo $dates['usuario_usuario']; ?>"
							pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required>
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Email</label>
						<input class="input" type="email" name="user_email" value="<?php echo $dates['usuario_email']; ?>"
							maxlength="70">
					</div>
				</div>
			</div>
			<br><br>
			<p class="has-text-centered">
				SI desea actualizar la clave de este usuario por favor llene los 2 campos. Si NO desea actualizar la clave
				deje los campos vacíos.
			</p>
			<br>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label>Clave</label>
						<input class="input" type="password" name="user_passw" pattern="[a-zA-Z0-9$@.-]{7,100}"
							maxlength="100">
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Repetir clave</label>
						<input class="input" type="password" name="user_passw_repeat" pattern="[a-zA-Z0-9$@.-]{7,100}"
							maxlength="100">
					</div>
				</div>
			</div>
			<br><br><br>
			<p class="has-text-centered">
				Para poder actualizar los datos de este usuario por favor ingrese su USUARIO y CLAVE con la que ha iniciado
				sesión
			</p>
			<div class="columns">
				<div class="column">
					<div class="control">
						<label>Usuario</label>
						<input class="input" type="text" name="user_admin" pattern="[a-zA-Z0-9]{4,20}" maxlength="20"
							required>
					</div>
				</div>
				<div class="column">
					<div class="control">
						<label>Clave</label>
						<input class="input" type="password" name="key_admin" pattern="[a-zA-Z0-9$@.-]{7,100}"
							maxlength="100" required>
					</div>
				</div>
			</div>
			<p class="has-text-centered">
				<button type="submit" class="button is-primary is-rounded button-custom">Actualizar</button>
			</p>
		</form>
	<?php
	} else {
		include './includes/error_alert.php';

	}
	$check_user = null;
	?>
</div>
<div class="content-tabs cambiar-clave">
	<fieldset>
		<div class="form-item">
			<label for="new_passwd">Contrase&ntilde;a actual:</label>
			<input type="password" value="" maxlength="32" name="passwd" id="passwd" class="form-control" autocomplete="off"/>
		</div>
		<div class="form-item">
			<label for="passwd">Contrase&ntilde;a nueva:</label>
			<input type="password" value="" maxlength="32" name="new_passwd" id="new_passwd" class="form-control"/>
		</div>
		<div class="form-item">
			<label for="confirm_passwd">Repetir Contrase&ntilde;a:</label>
			<input type="password" value="" maxlength="32" name="confirm_passwd" id="confirm_passwd" class="form-control"/>
		</div>
	</fieldset>
	<div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="mBtn btnOk">
	</div>
	<div class="clearfix"></div>
</div>
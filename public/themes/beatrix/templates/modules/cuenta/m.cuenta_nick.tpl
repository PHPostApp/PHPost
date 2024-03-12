<div class="content-tabs cambiar-nick">
	<fieldset>
		{if $tsUser->info.user_name_changes > 0}
			<div class="alert alert-warning">Hola {$tsUser->nick}, le recordamos que dispone de {$tsUser->info.user_name_changes} cambios este a&ntilde;o. Recuerde que si su cambio no es aprobado, no se le devolver&aacute; la disponibilidad de otro cambio.</div>
		  	<div class="form-item">
				<label for="new_nick">Nombre de usuario</label>
				<input type="text" value="{$tsUser->nick}" maxlength="15" name="new_nick" id="new_nick" class="form-control"/>
		  	</div>
			<div class="form-item">
				<label for="password">Contrase&ntilde;a actual:</label>
				<input type="password" maxlength="32" name="password" id="password" class="form-control"/>
		  	</div>
			<div class="form-item">
				<label for="pemail">Recibir respuesta en</label>
				<input type="text" value="{$tsUser->info.user_email}" maxlength="35" name="pemail" id="pemail" class="form-control">
		  	</div>
	 	</fieldset>
	 	<div class="buttons">
		  	<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="mBtn btnOk">
	 	</div>
	{else}
		<div class="alert alert-warning">Hola {$tsUser->nick}, lamentamos informarle de su nula disponibilidad de cambios, contacte con la administraci&oacute;n o espere un a&ntilde;o.</div>
	{/if}
	<div class="clearfix"></div>
</div>
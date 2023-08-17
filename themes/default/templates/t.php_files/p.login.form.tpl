	<span id="login_cargando"></span>
	<span id="login_error">
		<p style="display:block;color:red;margin:0;margin-bottom:.6rem;text-align:center;"></p>
	</span>
	
	<div class="formulario">
		<div class="form-line">
			<label>Usuario</label>
			<input type="text" id="nickname" placeholder="JohnDoe" name="nick" maxlength="64">
		</div>
		<div class="form-line">
			<label>Contraseña</label>
			<input type="password" class="ilogin" placeholder="mypassword" id="password" name="pass" maxlength="64">
		</div>
		<div class="form-line form-checkbox">
			<label for="rem">
				<input type="checkbox" id="rem" name="rem" value="true" checked/> 
				<span>Recordar usuario</span>
			</label>
		</div>
	</div>
	<div class="login_footer">
		<a href="javascript:multiOptions('password');">&#191;Olvidaste tu contrase&#241;a?</a><br/>
		<a href="javascript:multiOptions('validation');">&#191;No lleg&oacute; el correo de validaci&oacute;n?</a><br/>
		<span style="cursor:pointer;color:green;font-weight: bold;" onclick="registro_load_form(); return false">Registrate Ahora!</span>
	</div>

{includeAsset file="login.js"}
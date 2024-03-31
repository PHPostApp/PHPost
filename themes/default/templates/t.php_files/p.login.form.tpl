	<span id="login_cargando"></span>
	<span id="login_error">
		<p style="display:block;color:red;margin:0;margin-bottom:.6rem;text-align:center;"></p>
	</span>
	
	<div class="formulario">
		{if !empty({$tsConfig.gh_client_id})}
		<div class="form-line" style="text-align: center;">
			<a style="background: #000;display: inline-block;padding: .3rem 1rem;color: #FFF;border-radius: .3rem;margin-bottom: .7rem;" href="{$tsConfig.oauthGithub}">Inicio de sesión con GitHub</a>
			<a style="background: #000;display: inline-block;padding: .3rem 1rem;color: #FFF;border-radius: .3rem;margin-bottom: .7rem;" href="{$tsConfig.oauthDiscord}">Iniciar sesión con Discord</a>
		</div>
		{/if}
		<div class="form-line">
			<label>Usuario o Email</label>
			<input type="text" id="nickname" autocomplete="OFF" placeholder="JohnDoe o johndoe@servermail.com" name="nick" maxlength="64">
		</div>
		<div class="form-line">
			<label>Contraseña</label>
			<input type="password" class="ilogin" autocomplete="OFF" placeholder="mypassword" id="password" name="pass" maxlength="64">
		</div>
		<div class="form-line form-checkbox">
			<input type="hidden" name="response" id="response" class="g-recaptcha">
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

<script src="{$tsConfig.js}/login.js"></script>
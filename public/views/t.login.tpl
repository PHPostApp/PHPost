{include "access_header.tpl"}	
	<div class="formulario d-flex justify-content-center align-items-start flex-column p-3 px-5">

		<span id="login_cargando"></span>

		<span id="login_error">
			<p style="display:block;color:red;margin:0;margin-bottom:.6rem;text-align:center;"></p>
		</span>

		<div class="mensajeAviso">
			<span>Obteniendo código de reCAPTCHA...</span>
		</div>

		<h2 class="text-center py-1">Inicia sesión</h2>

		<div class="mb-3 position-relative">
			<label for="nickname">Usuario o Email</label>
			<input type="text" id="nickname" class="form-control" autocomplete="OFF" placeholder="JohnDoe o johndoe@servermail.com" name="nick" maxlength="64">
		</div>
		<div class="mb-3 position-relative">
			<label for="password">Contraseña</label>
			<input type="password" class="form-control" autocomplete="OFF" placeholder="mypassword" id="password" name="pass" maxlength="64">
		</div>
		<div class="form-check form-switch">
			<input type="hidden" name="response" id="response" class="g-recaptcha">
			<input class="form-check-input" type="checkbox" name="rem" id="rem" tabindex="6" value="true" checked >
	  		<label class="form-check-label" for="rem">Recordar mi sesión</label>
		</div>

		<div class="py-3 d-flex justify-content-center align-items-center">
			<input type="button" onclick="login.iniciarSesion()" class="btn btn-primary" value="Iniciar sesión">
		</div>

		<div class="my-3 position-relative text-center">
			<a class="link-warning link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="javascript:login.multiOptions('password');">&#191;Olvidaste tu contrase&#241;a?</a>
			<a class="link-warning link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="javascript:login.multiOptions('validation');">&#191;No lleg&oacute; el correo de validaci&oacute;n?</a>
			<a class="my-3 d-block link-success link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover" href="{$tsConfig.url}/registro">Registrate Ahora!</a>
		</div>
		{if $OAuth}
		<span class="d-block mb-2 text-center fs-4">Continuar con...</span>
		<div class="form-line" style="text-align: center;">
			{foreach $OAuth key=i item=social}
				<a class="btn btn-social btn-{$i} btn-block mb-3" href="{$social}">
					<span><iconify-icon icon="fa6-brands:{$i}"></iconify-icon></span> {$i|ucfirst}
				</a>
			{/foreach}
		</div>
		{/if}
	</div>

<script>
$(() => {
	const publicKey = '{$tsConfig.pkey}';

	function loadScript(url) {
	   return new Promise((resolve, reject) => $.getScript(url, resolve));
	}

	loadScript('https://www.google.com/recaptcha/api.js?render=' + publicKey)
   .then(() => {
     	grecaptcha.ready(() => {
         grecaptcha.execute(publicKey, { action: 'submit' }).then(token => {
            response.value = token;
            $(".mensajeAviso").hide()
         });
     });
   })
   .then(() => loadScript('{$tsConfig.public}/js/login.js'))
   .catch(error => console.error('Error cargando scripts:', error));
})
</script>
{include "access_footer.tpl"}
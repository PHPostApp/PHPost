{include "access_header.tpl"}
	{if $tsAbierto === 1}
		<form id="RegistroForm" action="javascript:registro.crearCuenta()" method="POST" autocomplete="OFF" class="d-flex justify-content-start align-items-start flex-column p-3">
			<div class="mensajeAviso">
				<span>Obteniendo código de reCAPTCHA...</span>
			</div>

			<h2 class="text-center py-1">Crea tu cuenta</h2>

			<div class="mb-3 chequear position-relative">
				<label class="form-label" for="nick">Ingresa tu usuario*</label>
				<input name="nick" type="text" id="nick" tabindex="1" placeholder="Ingrese un nombre de usuario &uacute;nico" class="form-control" required /> 
				<div class="help fst-italic fs-6"></div>
				<div id="password-help-block" class="form-text">Puede contener {if $tsConfig.c_upperkey === '1'}<em>Mayúsculas</em>, {/if}<em>Minúsculas</em>, <em>Números</em></div>
			</div>

			<div class="mb-3 chequear position-relative">
				<label class="form-label" for="password">Contrase&ntilde;a deseada*</label>
				<input name="password" type="text" id="password" tabindex="2" placeholder="Ingresa una contrase&ntilde;a segura" class="form-control" required /> 
				<div class="help fst-italic fs-6"></div>
				<div id="password-strength"><span></span> <em>Nivel</em></div>
			</div>

			<div class="mb-3 chequear position-relative">
				<label class="form-label" for="email">E-mail*</label>
				<input name="email" type="text" id="email" tabindex="4" placeholder="Ingresa tu direcci&oacute;n de email" class="form-control" required /> 
				<div class="help fst-italic fs-6"></div>
			</div>

			<div class="mb-3 chequear position-relative">
				<label>Fecha de Nacimiento*</label>
				<input type="hidden" id="max" value="{$tsMaxY}">
				<input type="hidden" id="end" value="{$tsEndY}">
				<input name="nacimiento" type="date" id="nacimiento" tabindex="5" min="{$tsMaxY}-12-31" max="{$tsEndY}-12-31" class="form-control" required /> 
				<div class="help fst-italic fs-6"></div>
			</div>

			<div class="form-check form-switch chequear">
				<input type="hidden" name="response" id="response" class="g-recaptcha">
	  			<input class="form-check-input" type="checkbox" id="terminos" tabindex="6" title="Acepta los T&eacute;rminos y Condiciones?" required>
	  			<label class="form-check-label" for="terminos">Acepto los <a href="{$tsConfig.url}/pages/terminos-y-condiciones/" target="_blank">T&eacute;rminos de uso</a>*</label>
			</div>
			<small>Todos los campos con * son requerido</small>

			<div class="py-3 d-flex justify-content-center align-items-center">
				<input type="submit" class="btn btn-primary" value="Crear cuenta">
			</div>

			<hr>
			<div class="form-line" style="text-align: center;">
				{foreach $tsConfig.oauth key=i item=social}
					<a class="btn btn-social btn-{if $i == 'gmail'}google{else}{$i}{/if} btn-block mb-3" href="{$social}">
						<span><iconify-icon icon="fa6-brands:{if $i == 'gmail'}google{else}{$i}{/if}"></iconify-icon></span> Continuar con {$i}
					</a>
				{/foreach}
			</div>
		</form>
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
			            // Supongo que 'response' está definido en registro.js
			            response.value = token;
			            $(".mensajeAviso").hide()
							avanzar = true;
			         });
			     });
			   })
			   .then(() => loadScript('{$tsConfig.public}/js/registro.js'))
			   .catch(error => console.error('Error cargando scripts:', error));
			})
		</script>
	{else}
		<div style="height: 100vh;" class="d-flex justify-content-center align-items-center flex-column">
			<h2>{$tsConfig.titulo}</h2>
			<p class="lead">El formulario para nuevos usuarios se encuentra cerrado!</p>
		</div>
	{/if}
{include "access_footer.tpl"}
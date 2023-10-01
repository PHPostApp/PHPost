const login = (() => {
	'use strict';

	// Global dentro de la función anónima
	function findOut(element, encode = false) {
		let field = $('.formulario ' + element);
		if( empty(field.val()) ) {
			field.focus();
			return false;
		}
		field.on('keyup', () => $('#login_error p').html('').hide() )
		return encode ? encodeURIComponent(field.val()) : field;
	}

	function cargando(status = false) {
		const loading = $("#login_cargando");
		if(status) {
			loading
			.css({width: '100%',display: 'grid',placeItems: 'center',marginBottom: '.3rem'})
			.html('<img src="'+global_data.img+'/loading_bar.gif" />');
		} else loading.removeAttr('style').html('')
	}


	function iniciarSesion() {
		const codigoRecaptcha = $("#response").val();
		let params = [
			'nick=' + findOut('#nickname', true),
			'pass=' + findOut('#password', true),
			'rem=' + $('#rem').is(':checked'),
			'response=' + codigoRecaptcha
		].join('&');
		cargando(true)
		$('#login_error p').html('').hide();
		$('#loading').fadeIn(250);
		$.post(global_data.url + '/login-user.php', params, h => {
			console.log(params)
			switch(h.charAt(0)){
				case '0':
					$('#login_error p').html(h.substring(3)).show();
					findOut('#nickname').focus();
					cargando()
				break;
				case '1':
					if (h.substring(3)=='Home') location.href='/';
					else if (h.substr(3) == 'Cuenta') location.href = '/cuenta/';
					else location.reload();
					$('#loading').fadeOut(350);
				break;
			};
		})
		.fail(() => $('#login_error p').html('Error al intentar procesar lo solicitado').show())
		.done(() => cargando())
	}

	function multiOptions(who = '', status = false) {
		// Creamos la plantilla que usará
		const template = `<div id="AFormInputs">
			<div class="form-line">
				<label for="r_email">Correo electr&oacute;nico:</label>
				<input type="text" ame="r_email" placeholder="example@gmail.com" id="r_email" maxlength="35"/>
			</div>
		</div>`;
		if(!status) {
			let data = {
				'password': 'Recuperar Contrase&ntilde;a',
				'validation': 'Reenviar validaci&oacute;n'
			}
			mydialog.faster({
				title: data[who],
				body: template,
				buttons: {
					ok: {text: 'Continuar', action: `javascript:multiOptions(true, '${who}')` },
					fail: {text: 'Cancelar', action: 'close' }
				}
			})
		}
	}
	return {
		iniciarSesion: iniciarSesion,
		multiOptions: multiOptions,
		cargando: cargando
	}
})();

///login.iniciarSesion();
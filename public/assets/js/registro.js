// Evitamos el colapso con otras funciones
const registro = (() => {
	'use strict';

	// Global dentro de la función anónima
	var actionContinue = false;

	// Comprobamos con patrones
	var expresiones = {
		nick: /^[a-zA-Z0-9\_\-]{4,20}$/,
		password: /^.{4,32}$/,
		email: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
	   nacimiento: /^\d{4}-\d{2}-\d{2}$/
	}

	// Estados de los campos
	var approved = { nick: false, password: false, email: false, nacimiento: false }

	// Funciones
	function __getURL(page) {
		const filePHP = 'registro-' + page + '.php';
		return `${global_data.url}/${filePHP}?ajax=true`
	}
	function __setMessage(element, message, type) {
		const status = [
			'text-danger', 
			'text-success', 
			'text-secondary', 
			'text-primary'
		];
		let findElement = $(element).parent().find('.help')
		findElement.removeClass('text-danger text-success text-secondary text-primary')
		findElement.addClass(status[parseInt(type)])
		findElement.html(message);
		return (parseInt(type) === 1) ? true : false;
	}
	function __field(element_name, response) {
	   let element_id = '#' + element_name;
	   let element_value = $(element_id).val();
	   let match = expresiones[element_name].test(element_value);
	   let parse_int = parseInt(response.charAt(0));
	   return match ? __setMessage(element_id, response.substring(3), parse_int) : false;
	}
	function __check_strength(password) {
	   var strength = 0;
	   // Comprobar la longitud de la contraseña
	   if (password.length > 6) strength += 1;
	   // Verifique si hay casos mixtos
	   if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
	   // Comprobar los números
	   if (password.match(/\d/)) strength += 1;
	   // Comprobar caracteres especiales
	   if (password.match(/[^a-zA-Z\d]/)) strength += 1;
	   // Actualice el texto y el color según la seguridad de la contraseña
	   let color = {1: 'darkred', 2: 'red', 3: 'orange', 4: 'green' }
	   let text = {1: 'Fácil', 2: 'Medio', 3: 'Difícil', 4: 'Extremadamente difícil' }
	   // Limpiamos el css
	   $('#password-strength span').removeAttr('style')
	  	$('#password-strength span').css({ backgroundColor: color[strength] })
	  	$('#password-strength em').html(text[strength]);
	}

	function verify(element) {
		const element_name = element.target.name
		const element_id = '#' + element.target.id;
		let element_value = $(element_id).val();
		// Realizamos las comprobaciones con la Base de datos
		// Comprobamos nick y/o email
		if(element_name === 'nick' || element_name === 'email') {
			// Mayor a 4 caracteres
			if(element_name === 'nick' && element_value.length <= 4)
				__setMessage(element_id, `Debes ser mayor a 4 caracteres`, 3);
			// Menor a 20 caracteres
			else if(element_name === 'nick' && element_value.length >= 20)
				__setMessage(element_id, `Debes ser menor a 20 caracteres`, 3);
			// Comprobando
			else 
				__setMessage(element_id, `Comprobando ${element_name}...`, 2);
			// Tipo de dato para enviar como parametro
			let data = (element_name === 'nick') ? { nick: element_value } : { email: element_value };
			// Enviamos...
			let getCheck = __getURL('check-' + element_name);
			$.post(getCheck, data, response => approved[element_name] = __field(element_name, response))
		// Comprobamos contraseña
		} else if (element_name === 'password') {
			__setMessage(element_id, `Comprobando constraseña...`, 2);
			__check_strength(element_value);
			let message = (element_value === $('#nick').val()) ? '0: No puede ser igual al Nick' : '1: Ok!';
			approved[element_name] = __field(element_name, message)
		// Comprobamos nacimiento
		} else if (element_name === 'nacimiento') {
		   const birthdate = new Date(element_value);
		   const birth = birthdate.getFullYear(); 
		   const today = new Date();
		   // Comprobaciones condicionales
		   if (birth >= today.getFullYear()) {
		      __setMessage(element_id, `La fecha no puede ser en el futuro`, 3);
		   } else if (birth < parseInt($('#max').val())) {
		      __setMessage(element_id, `No puedes ser tan viejo!`, 3);
		   } else if (today.getFullYear() - birth < 16) {
		      __setMessage(element_id, `Debes ser mayor de 16 años`, 3);
		   } else {		      
		      // Si todas las condiciones se cumplen, se aprueba la fecha
		     	approved[element_name] = __field(element_name, '1: Fecha válida!')
		   }
		}
	}

	function areAllTrue(objects) {
	  	for (var properties in objects) {
	    	if (!objects[properties]) return false;
	  	}
	  	return true;
	}

	// Obtenemos todos los elementos del formulario
	let allFields = $('#RegistroForm .chequear input');
	allFields.map( (id, field) => $(field).on('blur keyup', verify) )

	function crearCuenta() {
		//e.preventDefault();
		// Obtenemos el codigo recapcha
		const codigoRecaptcha = $("#response").val();
		let checked = $("#terminos").prop('checked');
		if(checked) {
			if(areAllTrue(approved)) {
				$('#loading').fadeIn(250);
				let formulario = $("#RegistroForm").serialize();
				$(".mensajeAviso").css({ display: 'grid' })
				$(".mensajeAviso span").html('Espere, creando su cuenta...');
				formulario += '&terminos=' + checked;
				$.post(__getURL('nuevo'), formulario, h => {
					switch(h.charAt(0)){
		            case '0':
		               $('#loading').fadeOut(350);
							mydialog.alert('Error', h.substring(3))
							$(".mensajeAviso").css({ display: 'none' })
		            break;
		            case '1':
		            case '2':
		               $('#loading').fadeOut(350);
				         mydialog.faster({
				         	title: 'Vamos',
				         	body: h.substring(3),
				         	buttons: {
				         		ok: {text: 'Aceptar', action: 'registro.redireccionar('+h.charAt(0)+')' }
				         	}
				         });
		            break;
		         }
				})
			}
		} else __setMessage("#terminos", 'Debes aceptar los Términos y Condiciones antes de continuar', 3);
	}

	function redireccionar(type = 0) {
	   location.href = global_data.url + '/' + (parseInt(type) === 2 ? 'cuenta/' : '');
	}

	return {
    	crearCuenta: crearCuenta, // Hace pública la función crearCuenta
    	redireccionar: redireccionar
  	};
})();
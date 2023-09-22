var avanzar = false;

getURL = page => `${global_data.url}/registro-${page}.php?ajax=true`

// Comprobamos con patrones
var expresiones = {
	nick: /^[a-zA-Z0-9\_\-]{4,20}$/,
	password: /^.{4,32}$/,
	email: /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/,
   nacimiento: /^\d{4}-\d{2}-\d{2}$/
}

// Verificamos
var Approved = {
	nick: false,
	password: false,
	email: false,
	nacimiento: false
}

helper_msg = (id, msg, type) => {
	var status = ['error', 'ok', 'loading', 'info'];
	$(id).parent().find('.help').removeClass('error ok loading info').addClass(status[parseInt(type)]).html(msg);
	return (parseInt(type) === 1) ? true : false;
}

campos = (nameEl, response) => {
   let texto = $('#' + nameEl).val();
   let trfa = expresiones[(nameEl === 'password2' ? 'password' : nameEl)].test(texto);
   let number = parseInt(response.charAt(0));
   return trfa ? helper_msg('#' + nameEl, response.substring(3), number) : false;
}

checkStrength = (password, nameEl) => {
   var strength = 0;
   // Comprobar la longitud de la contraseña
   if (password.length > 8) strength += 1;
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
   $('#'+nameEl+'-strength span').removeAttr('style')
  	$('#'+nameEl+'-strength span').css({ backgroundColor: color[strength],  })
  	$('#'+nameEl+'-strength em').html(text[strength]);
  
}

var verificar = element => {
	const nameElem = element.target.name
	const idElem = `#${nameElem}`;
	let valElem = $(idElem).val();
	// Realizamos las comprobaciones con la Base de datos
	switch (nameElem) {
		case 'nick':
		case 'email':
			if(nameElem === 'nick' && valElem.length <= 4) {
				helper_msg(idElem, `Debes ser mayor a 4 caracteres`, 3)
			} else if(nameElem === 'nick' && valElem.length >= 20) {
				helper_msg(idElem, `Debes ser menor a 20 caracteres`, 3)
			} else helper_msg(idElem, `Comprobando ${nameElem}...`, 2)

			typeData = (nameElem === 'nick') ? {nick:valElem} : {email:valElem};
			$.post(getURL('check-' + nameElem), typeData, h => Approved[nameElem] = campos(nameElem, h))
		break;
		case 'password':
		case 'password2':
			helper_msg(idElem, `Comprobando constraseña...`, 2);
			const p1 = $("#password").val();
			const p2 = $("#password2").val();
			const nick = $("#nick").val();
			if(nameElem === 'password') {
				checkStrength(p1, nameElem)
				message = (p1 === nick || p2 === nick) ? '0: No puede ser igual al Nick' : '1: Ok!';
			} else {
				checkStrength(p2, nameElem)
            message = (p1 !== p2) ? '0: Tus contraseñas deben ser iguales' : '1: Ok!';
			}
			Approved[nameElem] = campos(nameElem, message)
		break;
		case 'nacimiento':
		   const fnac = new Date(valElem);
		   const nacimiento = fnac.getFullYear();
		   const hoy = new Date();
		   // Comprobaciones condicionales
		   if (nacimiento >= hoy.getFullYear()) {
		      helper_msg(idElem, `La fecha no puede ser en el futuro`, 3);
		   } else if (nacimiento < parseInt($('#max').val())) {
		      helper_msg(idElem, `No puedes ser tan viejo!`, 3);
		   } else if (hoy.getFullYear() - nacimiento < 16) {
		      helper_msg(idElem, `Debes ser mayor de 16 años`, 3);
		   } else {		      
		      // Si todas las condiciones se cumplen, se aprueba la fecha
		     	Approved[nameElem] = campos(nameElem, '1: Fecha válida!')
		   }
		break;
	}
}

var allFields = [].slice.call(document.querySelectorAll("#RegistroForm .form-line input"))
allFields.map( field => {
	field.addEventListener('blur', verificar)
	field.addEventListener('keyup', verificar)
})

function sonTodosVerdaderos(obj) {
  	for (var prop in obj) {
    	if (!obj[prop]) return false;
  	}
  	return true;
}

crearCuenta = () => {
	if(avanzar) {
		const codigoRecaptcha = $("#response").val();
		if(sonTodosVerdaderos(Approved) && $("#terminos").prop('checked')) {
			$('#loading').fadeIn(250);
			const formulario = $("#RegistroForm").serialize()
			$(".mensajeAviso").css({ display: 'grid' })
			$(".mensajeAviso span").html('Espere, creando su cuenta...')
			$.post(getURL('nuevo'), formulario, h => {
				switch(h.charAt(0)){
	            case '0':
	               $('#loading').fadeOut(350);
						mydialog.alert('Error', h.substring(3))
						$(".mensajeAviso").hide()
	            break;
	            case '1':
	            case '2':
	               $('#loading').fadeOut(350);
			         mydialog.faster({
			         	title: 'Vamos',
			         	body: h.substring(3),
			         	buttons: {
			         		ok: {text: 'Aceptar', action: 'redireccionar('+h.charAt(0)+')' }
			         	}
			         });
	            break;
	         }
			})
		}
	}
}
function redireccionar(type = 0) {
   location.href = global_data.url + '/' + (parseInt(type) === 2 ? 'cuenta/' : '');
}
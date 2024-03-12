function input_fake(x) {
	$('.input-hide-'+x).hide();
	$('.input-hidden-'+x).show().focus();
}
explicacion = () => {
	mydialog.faster({
		title: 'Tu usuario de discord',
		body: 'En tu nombre de usuario hacer clic y se desplegará un panel y allí "Copias el ID del usuario"<br> <img src="https://i.imgur.com/mIdStwU.png" class="image">',
		buttons: {
			ok: { text: 'Listo', action: 'close' }
		}
	})
}

let check = true;
$('#vg0').on('change', function() {
   check = $(this).prop('checked');
   for (let x = 1; x <= 4; x++) $('#vg' + x).prop('checked', check);
});


function desactivate(few) {
	if(!few) {
      mydialog.faster({
      	title: 'Desactivar Cuenta',
      	body: '&#191;Seguro que quiere desativar su cuenta?',
      	buttons: {
      		ok: { text: 'Desactivar', action: 'desactivate(true)' },
      		fail: { text: 'No', action: 'close' }
      	}
      });
	} else {
		var pass = $('#passi');
      $('#loading').fadeIn(250); 
		$.post(`${global_data.url}/cuenta.php?action=desactivate`, 'validar=ajaxcontinue', a => {
		   mydialog.alert((a.charAt(0) == '0' ? 'Opps!' : 'Hecho'), (a.charAt(0) == '0' ? 'No se pudo desactivar' : 'Cuenta desactivada'), true);
		   mydialog.center();
         $('#loading').fadeOut(250);
      });
   }
}

var cuenta = {
	alerta: (alerta) => {
		$("#alerta_guarda").show().html(alerta)
		$(".alert-cuenta").scrollTo(0, 0)
		// Despues de 5s quitamos el alerta
		setTimeout(() => $("#alerta_guarda").hide().html(''), 5000)
	},
	chgpais: () => {
		// Campo pais
		const pais_code = $("select[name=pais]").val();
		const estado = $("select[name=estado]");
		if(empty(pais_code)) estado.addClass('disabled').attr('disabled', 'disabled').val('');
		else {
			//Obtengo las estados
			$(estado).html('');
         $('#loading').fadeIn(250); 
         $.get(global_data.url + '/registro-geo.php', { pais_code }, h => {
         	if(h.charAt(0) === '1') estado.append(h.substring(3)).removeAttr('disabled').val('').focus();
         	$('#loading').fadeOut(250); 
         })
      }
	},
	guardar_datos: () => {
		$('#loading').slideDown(250);
		$.post(global_data.url + '/cuenta-guardar.php', $("form[name=editarcuenta]").serialize(), response => {
			console.log(response)
			cuenta.alerta(response.error)
		}, 'json')
	}
}
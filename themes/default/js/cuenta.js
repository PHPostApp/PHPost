function input_fake(x) {
	$('.input-hide-'+x).hide();
	$('.input-hidden-'+x).show().focus();
}

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
		$("#alerta_guarda").html(`<div style="background:#FFFFCC;text-align:center;margin-bottom: 10px;"><p style="display: block;font-size: 16px;padding: 10px 0;">${alerta}</p></div>`)
		$(".alert-cuenta").scrollTo(0, 0)
		// Despues de 5s quitamos el alerta
		setTimeout(() => $("#alerta_guarda").html(''), 5000)
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
		$.post(global_data.url + '/cuenta-guardar.php', $("form[name=editarcuenta]").serialize(), response => cuenta.alerta(response.error), 'json')
	}
}
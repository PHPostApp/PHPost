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

function cambiarFile(other = true){
   const input = $('input[name=desktop]')[0];
   if(input.files && input.files[0]) {
   	if(other) {
	   	let name_file = decodeURIComponent(input.files[0].name);
	      document.querySelector(".drop-message").innerHTML = name_file;
   	}
      avatar.subir('desktop')
   } 
}
$('.avatar-cambiar .mBtn').on('click', e => {
	if(!empty(e.target.id)) {
		let name = e.target.id;
		$("#input_add")[(name === 'changePC' ? 'hide' : 'block')]();
		if(name === 'changePC') $('#drop-region input').click();
		else $(".verify").on('click', e => avatar.subir('url'));
	}
})
$('.avatar-cambiar .mBtn.btnDelete').on('click', () => mydialog.alert('No puedes subir avatar por URL', 'Esta deshabilitado desde la administración...'));

getCache = () => {
	let date_ = new Date();
   let fecha = date_.getDate();
   let cache = Math.floor(Math.random() * (date_.getFullYear() - fecha)) + fecha;
   return cache;
}

var avatar = {
	uid: false,
	key: false,
   ext: false,
	informacion: '',
	url: '',
	current: false,
	success: false,
	total: 2,
	fetching: async (url, data) => {
		const uploader = await fetch(global_data.url + '/upload-' + url + '.php', {
			method: 'POST',
			body: data
		})
		const response = await uploader.json();
		return response;
	},
	subir: async (type = 'desktop') => {
		$(".avatar-loading").show();
		const myInput = $(`input[name=${type}]`)
		//
		const datoUrl = new FormData();
		datoUrl.append('url', (type === 'url') ? myInput.value : myInput[0].files[0]);
		const Response = await avatar.fetching('avatar', datoUrl)
		if(!empty(Response)) avatar.subida_exitosa(Response);
	},
	subida_exitosa: rsp => {
		if (rsp.error == 'success') avatar.success = true;
		else if (rsp.msg) {
         avatar.key = rsp.key;
         avatar.ext = rsp.ext;
         avatar.cortar(rsp.msg);
		} else cuenta.alerta(rsp.error, 0);
		$(".avatar-loading").hide();
	},
	cortar: img => {
		newImageUpload = img + '?t=' + getCache();
		mydialog.show(true);
		mydialog.title("Cortar avatar");
		mydialog.body(`<img class="avatar-cortar" src="${newImageUpload}" />`);
		mydialog.buttons(true, true, 'Cortar', `avatar.guardar()`, true, true, true, 'Cancelar', 'close', true, false);
		mydialog.center();
		$("#avatar-img, #avatar-menu").attr("src", newImageUpload).on('load', () => {
			var croppr = new Croppr('.avatar-cortar', {
			   aspectRatio: 1, // Mantemos el tamanio cuadrado 1:1
			   // Tamano por defecto
    			maxSize: {width: 120, height: 120}, 
    			// Tengo que arreglar esto para la vista previa!
    			/**onUpdate: value => {
					var rx = sizeImg / value.width;
					var ry = sizeImg / value.height;
					$('.avatar-big').css({
						width: Math.round(rx * $('.croppr-imageClipped').width()) + 'px',
						height: Math.round(ry * $('.croppr-imageClipped').height()) + 'px',
						marginLeft: '-' + Math.round(rx * value.x) + 'px',
						marginTop: '-' + Math.round(ry * value.y) + 'px'
					});
				},**/
    			// Enviamos las coordenadas para cortar la imagen
    			// Tiene la funcion onCropEnd ya que es como va a quedar
    			onCropEnd: data => avatar.informacion = data,
			});
		});
	},
	recargar: () => $("#avatar-img, #avatar-menu").attr("src", avatar.current + '?t=' + getCache()),
	guardar: async () => {
		if (empty(avatar.informacion)) cuenta.alerta('Debes seleccionar una parte de la foto', 0);
		else {
			const allcoord = {
				key: avatar.key,
				ext: avatar.ext,
				x: avatar.informacion.x,
				y: avatar.informacion.y,
				w: avatar.informacion.width,
				h: avatar.informacion.height
			};
			const coordenadas = new FormData();
			for (const prop in allcoord) coordenadas.append(prop, allcoord[prop]);
			const resultado = await avatar.fetching('crop', coordenadas)
			if(resultado.error === "success") {
				mydialog.body("Tu avatar se ha creado correctamente...");
			   mydialog.buttons(true, true, 'Aceptar', 'close', true, true, false);
			   avatar.recargar();
			}
		}
	}
}
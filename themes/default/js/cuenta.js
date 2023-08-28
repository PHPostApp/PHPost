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

var AlertFloat = new function() {
	this.prefix = 'alertfloat__color--',
	this.element = '#alert_float',
	this.elclass = '',
	this._title = '<em>Debes agregar el titulo</em>',
	this._body = '<em>Debes agregar el contenido</em>',
	this._type = 'default',
	this.show = objects => {
		let title = empty(objects.title) ? this._title : objects.title;
		let body = empty(objects.body) ? this._body : objects.body;
		let type = empty(objects.type) ? this._type : objects.type;
		
		$('#brandday').after(`
			<div id="${this.element}" class="alertfloat ${this.prefix}${type}">
				<h3 class="alertfloat--title">${title}</h3>
				<p class="alertfloat--content">${body}<p>
			</div>
		`)
	}
}

console.log(AlertFloat.show({title:'titulo', body:'el contenido del mismo', type:'danger'}))
var cuenta = {
	ciudad_id: '',
	ciudad_text: '',
	no_requerido: new Array(),

	alert: (secc, title, body) => {
		$('div.alert-cuenta.cuenta-'+secc).html('<h2>'+title+'</h2>');
		$('div.alert-cuenta.cuenta-'+secc).slideDown(100);
	},

	alert_close: secc => {
		$('div.alert-cuenta.cuenta-'+secc).html('');
		$('div.alert-cuenta.cuenta-'+secc).slideUp(100);
	},

	chgtab: obj => {
		$('div.tabbed-d > div.floatL > ul.menu-tab > li.active').removeClass('active');
		$(obj).parent().addClass('active');
		var active = $(obj).html().toLowerCase().replace(' ', '-');
		$('div.content-tabs').hide();
		$('div.content-tabs.'+active).show();
	},

	chgsec: obj => {
		$('div.content-tabs.perfil > h3').removeClass('active');
		$('div.content-tabs.perfil > fieldset').slideUp(100);
		if ($(obj).next().css('display') == 'none') {
			$(obj).addClass('active');
			$(obj).next().slideDown(100).addClass('active');
		}
	},

	chgpais: function(){
		var pais = $('form[name=editarcuenta] select[name=pais]').val();
		var el_estado = $('form[name=editarcuenta] .content-tabs.cuenta select[name=estado]');

		//No se selecciono ningun pais.
		if(empty(pais)){
			$('form[name=editarcuenta] select[name=estado]').addClass('disabled').attr('disabled', 'disabled').val('');
		}else{
			//Obtengo las estados
			$(el_estado).html('');
            $('#loading').fadeIn(250); 
			$.ajax({
				type: 'GET',
				url: global_data.url + '/registro-geo.php',
				data: 'pais_code=' + pais,
				success: function(h){
					switch(h.charAt(0)){
						case '0': //Error
							break;
						case '1': //OK
							cuenta.no_requerido['estado'] = false;
							$(el_estado).append(h.substring(3)).removeAttr('disabled').val('').focus();
							break;
					}
                    $('#loading').fadeOut(250); 
				},
				error: function(){

				}
			});
		}
	},
	

	error: function(obj, str){
		var container = $(obj).next();
		if($(container).hasClass('errorstr')){
			$(container).show();
			$(container).html(str);
		}
	},

	next: function (isprofile) {
		if (typeof isprofile == 'undefined') var isprofile = false;
		if (isprofile) $('div.content-tabs.perfil > h3.active').next().next().click();
		else $('div.tabbed-d > div.floatL > ul.menu-tab > li.active').next().children().click();
	},

	save: function (secc, next) {

		$('.ac_input, .cuenta-save-'+secc).removeClass('input-incorrect');

		if (typeof next == 'undefined') var next = false;
		params = Array();
		params.push('save='+secc);

		$('.cuenta-save-'+secc).each(function(){
			if (($(this).attr('type') != 'checkbox' && $(this).attr('type') != 'radio') || $(this).attr('checked')) params.push($(this).attr('name')+'='+encodeURIComponent($(this).val()));
		});

		var cuenta_url = global_data.url + '/cuenta.php?action=save&ajax=true';

        $('#loading').slideDown(250); 
		$.ajax({
			type: 'post', 
			url: cuenta_url, 
			data: params.join('&'), 
			dataType: 'json',
			success: function (r) {
				//$('#prueba').html(r.html);
				if (r.error) {
					if (r.field) $('input[name='+r.field+']').focus().addClass('input-incorrect');
					cuenta.alert(secc, r.error)
				}
				else {
					if (next) cuenta.next(secc > 1 && secc < 5);
					cuenta.alert(secc, 'Los cambios fueron aceptados y ser&aacute;n aplicados.');
					if(r.porc != null) {
						$('#porc-completado-label').html('Perfil completo al ' + r.porc + '%');
						$('#porc-completado-barra').css('width', r.porc + '%');
					}
				}
				window.location.hash = 'alert-cuenta';
                $('#loading').slideUp(250); 
			}
		});
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
		} else cuenta.enviar_alerta(rsp.error, 0);
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
	recargar: () => $("#avatar-img, #avatar-menu").attr("src", avatar.url.replace('$1', avatar.uid) + '?t=' + getCache()),
	guardar: async () => {
		if (empty(avatar.informacion)) cuenta.enviar_alerta('Debes seleccionar una parte de la foto', 0);
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
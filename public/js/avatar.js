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

$(document).ready(() => {
	$('.avatar-cambiar .mBtn').on('click', e => {
		if(!empty(e.target.id)) {
			let name = e.target.id;
			$("#input_add")[(name === 'changePC' ? 'hide' : 'block')]();
			if(name === 'changePC') $('#drop-region input').click();
			else $(".verify").on('click', e => avatar.subir('url'));
		}
	})
	$('.avatar-cambiar .mBtn.btnDelete').on('click', () => mydialog.alert('No puedes subir avatar por URL', 'Esta deshabilitado desde la administración...'));
})
getCache = () => {
	let date_ = new Date();
   let fecha = date_.getDate();
   let cache = Math.floor(Math.random() * (date_.getFullYear() - fecha)) + fecha;
   return cache;
}

var avatar = {
	size: 120,
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
			   // Minimo de 160px x  160px
    			startSize: [avatar.size, avatar.size, 'px'], 
    			minSize: [avatar.size, avatar.size, 'px'], 
    			// Enviamos las coordenadas para cortar la imagen
    			// Tiene la funcion onCropEnd ya que es como va a quedar
    			onCropEnd: data => avatar.informacion = data,
            onCropMove: avatar.vistaPrevia
			});
		});
	},
	vistaPrevia: function (coords) {
		let rx = avatar.size / coords.width;
		let ry = avatar.size / coords.height;
		$('#avatar-img').css({
			width: Math.round(rx * avatar.imgCrop[0].width) + 'px',
			height: Math.round(ry * avatar.imgCrop[0].height) + 'px',
			marginLeft: '-' + Math.ceil(rx * coords.x) + 'px',
			marginTop: '-' + Math.round(ry * coords.y) + 'px'
		});
	},
	recargar: () => $("#avatar-img, #avatar-menu").attr("src", avatar.current + '_120?t=' + getCache()),
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
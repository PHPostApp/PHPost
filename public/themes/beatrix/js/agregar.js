const titulo = $('#titulo');
const contenido = $('#cuerpo');
const etiquetas = $('#tags');
const ext = $('#ext');
const key = $('#key');
const categoria = $('#category');
// PARA PORTADA
const portadas = $('input[name=myportada]');
const portada_pc = $('#portada');
const portada_url = $('#url');
var portada_tipo = '';
// ACCIONES
var borrador_setTimeout;
var borrador_ult_guardado = '';
var borrador_is_enabled = true;
var confirm = true;
var tags = false;

// ------ INICIO FUNCIONES GLOBALES
const post = {
	message: (field, message) => {
		let status = empty(message);
		let clase = (status ? 'remove' : 'add') + 'Class';
		let inout = status ? '' : 'in';
		if(field.parent().children()[0].localName === 'textarea') {
			field = $('.wysibb.theme-beatrix');
		}
		field.parent()[clase]('has-validation').children('.feedback').html(message)[clase](inout+ 'valid-feedback');
		field[clase]('is-invalid');
	},
	titleTags: (obj, type) => {
		const doe = (type === 'title') ? 'search' : 'generador';
		$.post(global_data.url + '/posts-genbus.php?do=' + doe, obj, response => {
	   	if(type === 'title') {
	   		$('#repost').html(response);
	   	} else {
	   		etiquetas.val(response.trim());
	   		tags = true;
	   	}
	   });
	}
}

// ------ FIN DE FUNCIONES GLOBALES

// --------- TITULO
countUpperCase = texto => {
	let min = 0;
	let may = 0;
	let error = '';
	for (let i = 0; i < texto.length; i++) {
    	const letra = texto.charAt(i);
    	if (letra === letra.toLowerCase() && letra !== letra.toUpperCase()) min++;
    	else if (letra === letra.toUpperCase() && letra !== letra.toLowerCase()) may++;
  	}
  	if(may == (texto.length - 1)) error = 'El título no puede ser todo mayúscula.';
  	if(texto.length < 10) error = 'El título es demasiado corto.';
  	if(texto.length < 1) error = 'El título no puede estar vacío.';
  	post.message(titulo, error)
}
titulo.on('keyup', key => countUpperCase(titulo.val()))

// --------- INICIAMOS EL WYSIBB 
contenido.css({ height: 500 }).wysibb();

// --------- GENERAR PORTADA
portadas.map( (index, field) => {
	$(field).on('click', () => {
		let val = $(field).attr('value');
		let hide = (val === 'pc') ? 'url' : 'pc';
		portada_tipo = val;
		$('.typeselect-' + hide).hide();
		$('.typeselect-' + val).show();
	})
})
portada_url.on('keyup', () => {
	if(!empty(portada_url.val())) {
		$('.visor-portada > .spinner-border').show();
   	$('.visor-portada img').attr({src: portada_url.val()});
   	setTimeout(() => $('.visor-portada > .spinner-border').hide(), 1000);
	}
});
portada_pc.on('change', () => {
	const input = document.getElementById('portada').files[0];
   let formData = new FormData($("form[name=newpost]")[0]);
   formData.append('portada', input);
	$('.visor-portada > .spinner-border').show();
   $('#fichero').html(input.name);
   // SUBIR IMAGEN 
   $.ajax({
      url: `${global_data.url}/upload-portada.php`,
      type: 'POST',
      data: formData,
      processData: false, // No procesar datos
      contentType: false, // No establecer el tipo de contenido
      dataType: 'json',
      success: function(response) {
			$('.visor-portada img').attr('src', '');
      	$('input[name=key]').attr('value', response.key)
      	$('input[name=ext]').attr('value', response.ext)
     		$('.visor-portada img').attr({src: response.msg});
      	$('.visor-portada > .spinner-border').hide();
      }
   });
})

// --------- FORMULARIO POST
newpost = () => {
	let formData = new FormData($("form[name=newpost]")[0]); 
	// Eliminamos manualmente el campo "body" y "portada" al FormData
	formData.delete("body");
	//
	if(!empty(ext.val())) formData.delete("ext");
	if(!empty(key.val())) formData.delete("key");

	// Agregar manualmente el campo "body" al FormData
	formData.append('body', encodeURIComponent($('textarea[name=body]').bbcode()));

	return formData;
}

// --------- GUARDAR EN BORRADORES
guardar = () => {
	let formData = newpost();
	//
	let borradorID = $('input[name="borrador_id"]').val()
	$('div#borrador-guardado').html('Guardando...');

	borrador_setTimeout = setTimeout('borrador_save_enabled()', 60000);
	borrador_save_disabled();

	if(!empty(borradorID)) {
		formData.append('borrador_id' , encodeURIComponent(borradorID))
	}

	let page = 'borradores-' + (!empty(borradorID) ? 'guardar' : 'agregar');
	$.ajax({
	   url: `${global_data.url}/${page}.php`,
	   type: 'POST',
	   data: formData,
	   processData: false, // No procesar datos
	   contentType: false, // No establecer el tipo de contenido
	   success: function (h) {
			switch(h.charAt(0)){
				case '0': //Error
					clearTimeout(borrador_setTimeout);
					borrador_setTimeout = setTimeout('borrador_save_enabled()', 5000);
					borrador_ult_guardado = h.substring(3);
					$('div#borrador-guardado').html(borrador_ult_guardado);
				break;
				case '1': //Guardado
					if(!empty(borradorID)) {
						$('input[name="borrador_id"]').val(h.substring(3));
					}
					var currentTime = new Date();
					borrador_ult_guardado = 'Guardado a las ' + currentTime.getHours() + ':' + currentTime.getMinutes() + ':' + currentTime.getSeconds() + ' hs.';
					$('div#borrador-guardado').html(borrador_ult_guardado);
				break;
			}
		}
	}).fail(() => mydialog.error_500('save_borrador()'))
}
function borrador_save_enabled(){
	if($('input#borrador-save')) 
		$('input#borrador-save').removeClass('disabled').removeAttr('disabled');
	borrador_is_enabled = true;
}
function borrador_save_disabled(){
	if($('input#borrador-save'))
		$('input#borrador-save').addClass('disabled').attr('disabled', 'disabled');
	borrador_is_enabled = false;
}

// --------- VERIFICAR ANTES DE CERRAR PESTAÑA
window.onbeforeunload = function() {
	if (confirm && (titulo.val() || contenido.bbcode())) {
		return 'Este post no fue publicado y se perder&aacute;.';
	}
};

// VERIFICAMOS QUE NO EXISTA UN TITULO IGUAL
titulo.on('blur', () =>{
   let q = titulo.val();
   const searching = false;
   post.titleTags({ q, searching }, 'title');
});
// GENERADOR DE TAGS
etiquetas.on('click', () => {
   var q = titulo.val();
   post.titleTags({ q }, 'tags');
});

// --------- VISTA PREVIA & PUBLICAR POST
addFnBoth = () => {
	// VERIFICAMOS TITULO
	if(empty(titulo.val())) {
		countUpperCase(titulo.val())
		titulo.focus();
		return false;
	}
	// VERIFICAMOS CONTENIDO
	if (contenido.bbcode().length < 1) {
		post.message(contenido, 'Ingresa contenido para el post')
		contenido.focus();
		window.scrollTo(0, 50)
		return false;
	} else post.message(contenido, '');
}

// --------- VISTA PREVIA
preliminar = () => {
	// VISTA PREVIA & PUBLICAR POST
	addFnBoth();
	//
	mydialog.size = 'big';
	mydialog.show(true);
	mydialog.title('Vista preliminar');
	mydialog.body('<div class="carf"><p>Cargando vista previa</p></div>');
   mydialog.buttons(false);
	// PREVIEW
	const body = encodeURIComponent(contenido.bbcode());
	$.post(global_data.url + '/posts-preview.php?ts=true', { body }, response => {
		mydialog.title(titulo.val());
		mydialog.body(response);
		mydialog.buttons(true, true, 'Cerrar', 'close', true, false);
		window.scrollTo(0, 50)
	})	
	mydialog.center();
}

// --------- PUBLICAR POST
publicar = () => {
	// VISTA PREVIA & PUBLICAR POST
	addFnBoth();
	// COMPROBAR CATEGORIA
	if (!categoria.val()) {
		post.message(categoria, 'Selecciona una categor&iacute;a');
		return false;
	}
	// COMPROBAR TAGS
	var tags = etiquetas.val().split(',');
	var msg = 'Tienes que ingresar por lo menos 4 tags separados por coma.';
	if (tags.length < 4) {
		post.message(etiquetas, msg);
		return false;
	} else {
		for(var i = 0; i < tags.length; i++) {
			post.message(etiquetas, '');
			if(tags[i] == '') return false;
		}
	}
	crear_el_post();
}
// Con esta función ya publicaremos el post
crear_el_post = () => {
	mydialog.show(true);
	mydialog.title('Publicando');
	mydialog.body('<div class="carf"><p>Comprobando contenido</p></div>');
	mydialog.buttons(false);
	mydialog.center();
			
	confirm = false;
	$('form[name=newpost]').submit();
}
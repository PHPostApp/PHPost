var button_title = '{if $tsDraft}Aplicar Cambios{else}Agregar post{/if}';
// Ejecutamos el wysibb
$(document).ready(() => $('#markItUp').css({height: 400}).wysibb());

function countUpperCase(string) {
	var len = string.length, 
	strip = string.replace(/([A-Z])+/g, '').length, 
	strip2 = string.replace(/([a-zA-Z])+/g, '').length, 
	percent = (len  - strip) / (len - strip2) * 100;
	return percent;
}
// Mostramos el error
error = (objeto, mensaje, tipo) => {
	// Añadimos o quitamos la clases al elemento padre
	objeto.parent()[(tipo?'add':'remove')+'Class']('error')
	// Buscamos al elemento hijo
	.children('.errormsg')
	// Y le añadimos un mensaje, lo mostramos u ocultamos
	.html(mensaje)[(tipo?'show':'hide')]();
}
//
var borrador_setTimeout;
var borrador_ult_guardado = '';
var borrador_is_enabled = true;
var confirm = true;
var tags = false;
//
guardar = () => {
	let replace_body = 'cuerpo=' + encodeURIComponent($('textarea[name=cuerpo]').bbcode());
	let params = $("form[name=newpost]").serialize().replace('cuerpo=', replace_body);
	const borrador_id = $('input[name="borrador_id"]').val()
	$('div#borrador-guardado').html('Guardando...');

	borrador_setTimeout = setTimeout('borrador_save_enabled()', 60000);
	borrador_save_disabled();

	if(!empty(borrador_id)) {
		params += '&borrador_id=' + encodeURIComponent(borrador_id)
	}
	let page = 'borradores-' + (!empty(borrador_id) ? 'guardar' : 'agregar');
	$.post(`${global_data.url}/${page}.php`, params, h => {
		switch(h.charAt(0)){
			case '0': //Error
				clearTimeout(borrador_setTimeout);
				borrador_setTimeout = setTimeout('borrador_save_enabled()', 5000);
				borrador_ult_guardado = h.substring(3);
				$('div#borrador-guardado').html(borrador_ult_guardado);
			break;
			case '1': //Guardado
				if(!empty(borrador_id)) {
					$('input[name="borrador_id"]').val(h.substring(3));
				}
				var currentTime = new Date();
				borrador_ult_guardado = 'Guardado a las ' + currentTime.getHours() + ':' + currentTime.getMinutes() + ':' + currentTime.getSeconds() + ' hs.';
				$('div#borrador-guardado').html(borrador_ult_guardado);
			break;
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
//
window.onbeforeunload = confirmleave;
function confirmleave() {
	if (confirm && ($('input[name=titulo]').val() || $('textarea[name=cuerpo]').bbcode())) 
		return "Este post no fue publicado y se perdera.";
}
//
preliminar = () => {
	//COMPROBAR TITULO
   if (countUpperCase($('input[name=titulo]').val()) < 5) {
		error($('input[name=titulo]'), 'Debes ingresar un titulo para el post', true);
		$('input[name=titulo]').focus();
		return false;
	}
	//COMPROBAR CONTENIDO
	if ($('textarea[name=cuerpo]').bbcode().length < 1) {
		error($('.wysibb'), 'Ingresa contenido para el post', true);
		$('textarea[name=cuerpo]').focus();
		window.scrollTo(0, 50)
		return false;
	}
	mydialog.class_aux = 'vistaPrevia';
	mydialog.size = 'big';
	mydialog.show(true);
	mydialog.title('Vista preliminar');
	mydialog.body('<div class="carf"><p>Cargando vista previa</p></div>');
   mydialog.buttons(false);
	// PREVIEW
	const data = 'cuerpo=' + encodeURIComponent($('textarea[name=cuerpo]').bbcode());
	$.post(global_data.url + '/posts-preview.php?ts=true', data, r => {
		mydialog.title($('input[name=titulo]').val());
		mydialog.body(r);
		mydialog.buttons(true, true, 'Cerrar', 'close', true, false);
		window.scrollTo(0, 50)
	})	
	mydialog.center();
}

// FUNCION PARA PUBLICAR
publicar = () => {
	// Comprobamos que tengo contenido
   if ($('input[name=titulo]').val().length < 5) {
		error($('input[name=titulo]'), 'Debes ingresar un titulo para el post', true);
		$('input[name=titulo]').focus();
		return false;
	}
	//COMPROBAR CONTENIDO
	if ($('textarea[name=cuerpo]').bbcode().length < 1) {
		error($('textarea[name=cuerpo]'), 'Ingresa contenido para el post', true);
		$('textarea[name=cuerpo]').focus();
		window.scrollTo(0, 50)
		return false;
	}
	//COMPROBAR CATEGORIA
	if (!$('select[name=categoria]').val()) {
		error($('select[name=categoria]'), 'Selecciona una categor&iacute;a', true);
		return false;
	}		
	//COMPROBAR TAGS
	var tags = $('input[name=tags]').val().split(',');
	var msg = 'Tienes que ingresar por lo menos 4 tags separados por coma.';
	if (tags.length < 4) {
		error($('input[name=tags]'), msg, true);
		return false;
	} else {
		for(var i = 0; i < tags.length; i++) {
			error($('input[name=tags]'), msg, (tags[i] == ''));
			if(tags[i] == '') return false;
		}
	}
	//GUARDAR POST DESPUES DE COMPROBAR CAMPOS
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
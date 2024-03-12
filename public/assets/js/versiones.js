// FEED SUPPORT
$.getJSON(global_data.url + "/feed-support.php", response => {
	$('#ulitmas_noticias').html('Obteniendo información...');
	if(Array.isArray(response)) {
		$('#ulitmas_noticias').html('');
		response.map( data => {
			const { link, title, info, version } = data;
			var html = `<a href="${link}" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
			 	<div class="me-auto">
			   	<div class="fw-bold">${title}</div>
			   	<span class="text-body-secondary">${info}</span>
			 	</div>
    			<span class="badge text-bg-primary rounded-pill">${version}</span>
			</a>`;
			$('#ulitmas_noticias').append(html);
		})
	} else $('#ulitmas_noticias').html(`<div class="phpostAlfa">${response}</div>`)
})

//
$.getJSON(global_data.url + "/feed-version.php?v=risus", response => {
	const { title, text, color } = response;
	// Clonamos
  	var clonar = $('.list-clone').first().clone();
  	// Añadimos color
  	clonar.addClass(color)
  	// Modificar los datos dentro del clon
  	clonar.find('.fw-bold').text(title);
  	clonar.find('.text-body-secondary').text(text);
  	// Agregar el clon a la lista
	if(typeof title === 'undefined') {
		clonar.addClass('list-group-item-danger')
		clonar.find('.fw-bold').text('No version');
  		clonar.find('.text-body-secondary').text(response);
	}
  	$('#ultima_version').append(clonar);
});

changeBranch = (branch = 'master') => {
	$.post(global_data.url + '/github-api.php', { branch }, response => {
		var cookiename = "LastCommitSha";
		var expires = { expires: 7 }
		//
		$('#lastCommit').html('');
		// Reemplazamos \n por saltos de línea con <br>
		content = response.commit.message.replace(/\n/g, '<br>');
		// Si la pantalla es menor a 1120px solo tendrá 7 caracteres
		SHA = (window.width < 1120) ? response.sha.substring(0, 7) : response.sha;
		// Si la Cookie no existe la crearemos por 7 días
		if($.cookie(cookiename) === null) $.cookie(cookiename, SHA, expires);
		// Obtenemos el valor de la cookie
		var getSHA = $.cookie(cookiename);
		// Comparamos
		if(SHA !== getSHA) {
			url = global_data.url + '/admin-update.php';
			$.post(url, 'update_now=false', r => $.cookie(cookiename, getSHA, expires))
		}
		let hace = $.timeago(response.commit.author.date)
		//
		var added = 0;
		var modified = 0;
		var deleted = 0;
		response.files.map( file => {
			if(file.status === 'added') added += 1;
			if(file.status === 'modified') modified += 1;
			if(file.status === 'deleted') deleted += 1;
		})
		// Creamos la plantilla para mostrar la infomación del mismo
		var html = `<div class="data-github">${content}<hr style="margin-top:.8rem;"><div class="row row-cols-3">
			<small>Agregados <strong>${added}</strong></small>
			<small>Modificados <strong>${modified}</strong></small>
			<small>Eliminados <strong>${deleted}</strong></small>
		</div></div>`;

		$('.panel-info.last-commit .card-footer').html(`<span>Sha: <a href="${response.html_url}" class="text-decoration-none text-primary" rel="noreferrer" target="_blank">${SHA}</a></span><span>${hace}</span>`);

		// La añadimos al HTML
		let transform = joypixels.toImage(html);
		$('#lastCommit').append(transform);
	}, 'json')
}
// Autoejecutamos
changeBranch();

$('input[name=branch]').on('click', e => {
	if ($('input[name=branch]').is(':checked')) {
    	const selectedOption = e.target.id;
    	changeBranch(selectedOption);
  	}
})
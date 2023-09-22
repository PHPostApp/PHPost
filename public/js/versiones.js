// FEED SUPPORT
$.getJSON(global_data.url + "/feed-support.php", response => {
	$('#ulitmas_noticias').html('');
	if(Array.isArray(response)) {
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
	} else $('#ulitmas_noticias').html('<div class="phpostAlfa">Sitio desconectado...</div>')
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
  	$('#ultima_version').append(clonar);
});


// ÚLTIMO COMMIT DE GITHUB
const ghuser = 'joelmiguelvalente';
const ghrepo = 'PHPost';
const ghbranch = 'master';
const apiGithub = `https://api.github.com/repos/${ghuser}/${ghrepo}/commits/${ghbranch}`;

var cookiename = "LastCommitSha";
var expires = { expires: 7 }

$.getJSON(apiGithub, data => {
	// Limpiamos
	$('#lastCommit').html('');
	// Reemplazamos \n por saltos de línea con <br>
	content = data.commit.message.replace(/\n/g, '<br>');
	// Si la pantalla es menor a 1120px solo tendrá 7 caracteres
	SHA = (window.width < 1120) ? data.sha.substring(0, 7) : data.sha;
	// Si la Cookie no existe la crearemos por 7 días
	if($.cookie(cookiename) === null) $.cookie(cookiename, SHA, expires);
	// Obtenemos el valor de la cookie
	var getSHA = $.cookie(cookiename);
	// Comparamos
	if(SHA !== getSHA) {
		url = global_data.url + '/admin-update.php';
		$.post(url, 'update_now=false', r => $.cookie(cookiename, getSHA, expires))
	}
	//
	let hace = $.timeago(data.commit.author.date)
	// Creamos la plantilla para mostrar la infomación del mismo
	var html = `<div class="data-github">${content}</div>`;

	$('.panel-info.last-commit .card-footer').html(`<span>Sha: <a href="${data.html_url}" class="text-decoration-none text-primary" rel="noreferrer" target="_blank">${SHA}</a></span><span>${hace}</span>`)

	// La añadimos al HTML
	$('#lastCommit').append(html);
})
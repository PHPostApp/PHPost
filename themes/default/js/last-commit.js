const ghuser = 'joelmiguelvalente';
const ghrepo = 'PHPost';
const ghbranch = 'master';
const apiGithub = `https://api.github.com/repos/${ghuser}/${ghrepo}/commits/${ghbranch}`;

var cookiename = "LastCommitSha";
var expires = { expires: 7 }

$.getJSON(apiGithub, data => {
	// Limpiamos
	$('#last_gh').html('');
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
	// Creamos la plantilla para mostrar la infomación del mismo
	var html = `<li class="data-github">
		<div class="title">
			<a href="${data.html_url}" rel="noreferrer" target="_blank">Actualizado por ${data.commit.author.name}</a>
			<time>${$.timeago(data.commit.author.date)}</time>
			<small>sha: ${SHA}</small>
		</div>
		<div class="body">${content}</div>
	</li>`;

	// La añadimos al HTML
	$('#last_gh').append(html);
})
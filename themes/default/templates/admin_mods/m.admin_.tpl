<div class="boxy-title">
	<h3>Centro de Administraci&oacute;n</h3>
</div>
<div id="res" class="boxy-content">
	<p><strong>Bienvenido(a), {$tsUser->nick}!</strong><br />Este es tu &quot;Centro de Administraci&oacute;n de PHPost&quot;. Aqu&iacute; puedes modificar la configuraci&oacute;n de tu web, modificar usuarios, modificar posts, y muchas otras cosas.<br />Si tienes algun problema, por favor revisa la p&aacute;gina de &quot;Soporte y Cr&eacute;ditos&quot;.  Si esa informaci&oacute;n no te sirve, puedes <a href="https://phpost.net/foro/" target="_blank">visitarnos para solicitar ayuda</a> acerca de tu problema.</p>
	<hr class="separator" />
	<div class="phpost">
		<h4>Último commit en Github</h4>
		<ul id="last_gh" class="pp_list">
			<div class="phpostAlfa">Cargando...</div>
		</ul>
		<h4>PHPost en directo</h4>
		<ul id="news_pp" class="pp_list">
			<div class="phpostAlfa">Cargando...</div>
		</ul>
	</div>
	<div class="phpost version">
		<h4>PHPost Risus</h4>
		<ul id="version_pp" class="pp_list">
			<li>
				 <div class="title">Versi&oacute;n instalada</div>
				 <div class="body"><strong>{$tsConfig.version}</strong></div>
			</li>
		</ul>
		<h4>Administradores</h4>
		<ul class="pp_list">                                    
			{foreach from=$tsAdmins item=admin}
			<li><div class="title"><a href="{$tsConfig.url}/perfil/{$admin.user_name}">{$admin.user_name}</a></div></li>                                    
			{/foreach}
		</ul>
		<h4>Instalaciones</h4>
		<ul class="pp_list stats">
		 	<li><span>Fundaci&oacute;n</span><span title="{$tsInst.0|fecha}">{$tsInst.0|hace:true}</span></li>
		 	<li><span>Actualizado</span><span title="{$tsInst.1|fecha}">{$tsInst.1|hace:true}</span></li>
		</ul>                                  
	</div>
	<div class="clearBoth"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/timeago@1"></script>
<script src="{$tsConfig.js}/timeago.es.js"></script>
{literal}
<script type="text/javascript">
$(() => {
	$.getJSON(global_data.url + "/feed-support.php", response => {
		$('#news_pp').html('');
		response.map( data => {
			const { link, title, info } = data;
			var html = `<li>
				<div class="title">
					<a href="${link}" target="_blank">${title}</a>
				</div>
				<div class="body">${info}</div>
			</li>`;
			$('#news_pp').append(html);
		})
	})
	//
	$.getJSON(global_data.url + "/feed-version.php?v=risus", response => {
		const { title, text } = response
		$('#version_pp').append(`
         <li>
            <div class="title">${title}</div>
            <div class="body"><b>${text}</b></div>
         </li>
      `);
	});
	// Último commit en github
   if ($('#last_gh').length) {
      const apiGithub = 'https://api.github.com/repos/joelmiguelvalente/PHPost/commits/master';
      $.getJSON(apiGithub, data => {
      	$('#last_gh').html('');
      	content = data.commit.message.replace(/\n/g, '<br>');
      	code = (window.width < 1120) ? data.sha.substring(0, 7) : data.sha;
      	tiempo = $.timeago(data.commit.author.date)
      	var html = `<li class="data-github">
				<div class="title">
					<a href="${data.html_url}" rel="noreferrer" target="_blank">Actualizado por ${data.commit.author.name}</a>
					<time>${tiempo}</time>
					<small>sha: ${code}</small>
				</div>
				<div class="body">${content}</div>
			</li>`;
			$('#last_gh').append(html);
      })
   }
});
</script>
{/literal}
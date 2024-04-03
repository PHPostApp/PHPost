<script>
	var action_menu = '{$tsAction}';
	$(() => {
		if(action_menu != '') $('#a_' + action_menu).addClass('active');
		else $('#a_main').addClass('active');
	});
</script>
<h4>General</h4>
<ul class="cat-list">
	<li id="a_main"><span class="cat-title"><a href="{$tsConfig.url}/admin/">Centro de Administraci&oacute;n</a></span></li>
	<li id="a_creditos"><span class="cat-title"><a href="{$tsConfig.url}/admin/creditos">Soporte y Cr&eacute;ditos</a></span></li>
</ul>
<h4>Configuraci&oacute;n de {$tsConfig.titulo}</h4>
<ul class="cat-list">
	<li id="a_configs"><span class="cat-title"><a href="{$tsConfig.url}/admin/configs">Configuraci&oacute;n </a></span></li>
	<li id="a_registro"><span class="cat-title"><a href="{$tsConfig.url}/admin/registro">Configurar registro </a></span></li>
	<li id="a_socials"><span class="cat-title"><a href="{$tsConfig.url}/admin/socials">Configurar redes sociales</a></span></li>
	<li id="a_seo"><span class="cat-title"><a href="{$tsConfig.url}/admin/seo">Configurar SEO</a></span></li>
	<li id="a_temas"><span class="cat-title"><a href="{$tsConfig.url}/admin/temas">Temas y apariencia</a></span></li>
	<li id="a_news"><span class="cat-title"><a href="{$tsConfig.url}/admin/news">Noticias</a></span></li>
	<li id="a_ads"><span class="cat-title"><a href="{$tsConfig.url}/admin/ads">Publicidad</a></span></li>
</ul>
<h4>Control de {$tsConfig.titulo}</h4>
<ul class="cat-list">
	<li id="a_medals"><span class="cat-title"><a href="{$tsConfig.url}/admin/medals">Medallas</a></span></li>
	<li id="a_afs"><span class="cat-title"><a href="{$tsConfig.url}/admin/afs">Afiliados</a></span></li>
	<li id="a_stats"><span class="cat-title"><a href="{$tsConfig.url}/admin/stats">Estad&iacute;sticas</a></span></li>
	<li id="a_blacklist"><span class="cat-title"><a href="{$tsConfig.url}/admin/blacklist">Bloqueos</a></span></li>
	<li id="a_badwords"><span class="cat-title"><a href="{$tsConfig.url}/admin/badwords">Censuras</a></span></li>
</ul>
<h4>Control de Contenido</h4>
<ul class="cat-list">
	<li id="a_posts"><span class="cat-title"><a href="{$tsConfig.url}/admin/posts">Todos los Posts</a></span></li>
	<li id="a_fotos"><span class="cat-title"><a href="{$tsConfig.url}/admin/fotos">Todas las Fotos</a></span></li>
	<li id="a_cats"><span class="cat-title"><a href="{$tsConfig.url}/admin/cats">Categor&iacute;as</a></span></li>
</ul>
<h4>Control de Usuarios</h4>
<ul class="cat-list">
	<li id="a_users"><span class="cat-title"><a href="{$tsConfig.url}/admin/users">Todos los Usuarios</a></span></li>
	<li id="a_sesiones"><span class="cat-title"><a href="{$tsConfig.url}/admin/sesiones">Sesiones</a></span></li>
	<li id="a_nicks"><span class="cat-title"><a href="{$tsConfig.url}/admin/nicks">Cambios de Nicks</a></span></li>
	<li id="a_rangos"><span class="cat-title"><a href="{$tsConfig.url}/admin/rangos">Rangos de Usuarios</a></span></li>
</ul>
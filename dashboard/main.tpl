<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" data-theme="light">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{meta facebook=false twitter=false}
{googlefonts fonts="Roboto"}
{phpost css=["main.css"] js=[] scriptGlobal=true}
</head>
<body>
<div id="loading" style="display:none"><img src="{$tsConfig.images}/ajax-loader.gif" alt="Cargando"> Procesando...</div>
<div id="swf"></div>
<div id="js" style="display:none"></div>
<div id="mydialog"></div>
<div class="UIBeeper" id="BeeperBox"></div>
<div id="brandday">
	 
	<div id="maincontainer">
		<header id="head">
			<div id="logo">
				<a id="logoi" title="{$tsConfig.titulo}" href="{$tsConfig.url}">
					<img border="0" align="top" title="{$tsConfig.titulo}" alt="{$tsConfig.titulo}" src="{$tsConfig.images}/space.gif">
				 </a>
			</div>
			<div id="banner"></div>
		</header>
		<main id="contenido_principal">
		  	{include "menu.tpl"}
		  	<div id="cuerpocontainer">
		  		{if $tsPage == 'moderacion'}
		  			{assign "pagina" "mod"}
		  		{else}
		  			{assign "pagina" "admin"}
		  		{/if}
		  		<aside id="admin_menu">
		  			{include "m.{$pagina}_sidemenu.tpl"}
		  		</aside>
		  		<section id="admin_panel">
					{include "m.{$pagina}_$tsAction.tpl"}
				</section>
			</div>
			<footer id="pie">
				<a href="{$tsConfig.url}/pages/ayuda/">Ayuda</a> -
				<a href="{$tsConfig.url}/pages/chat/">Chat</a> -
				<a href="{$tsConfig.url}/pages/contacto/">Contacto</a> -  
				<a href="{$tsConfig.url}/pages/protocolo/">Protocolo</a>
				<br/>
				<a href="{$tsConfig.url}/pages/terminos-y-condiciones/">T&eacute;rminos y condiciones</a> - 
				<a href="{$tsConfig.url}/pages/privacidad/">Privacidad de datos</a> -
				<a href="{$tsConfig.url}/pages/dmca/">Report Abuse - DMCA</a>
			</footer>
		</main>
		<!--END CONTAINER-->
	 </div>

	<template id="verification-install">
		<p>Esto es solamente para verificar tú versión con la versión actual.</p>
		<p>Si remueves esto, no recibirás información sobre actualizaciones y cambios!</p>
		<input type="hidden" name="verification-code" value="{$tsVerification}">
	</template>

	<div id="pp_copyright">
		<a href="{$tsConfig.url}"><strong>{$tsConfig.titulo}</strong></a> &copy; {$smarty.now|date_format:"%Y"} - Powered by <a href="https://phpost.es/" target="_blank"><strong>PHPost</strong></a>
	</div>
</div>
{if $tsUser->is_admod && $tsConfig.c_see_mod && $tsConfig.novemods.total}
	<div id="stickymsg" onmouseover="$('#brandday').css('opacity',0.5);" onmouseout="$('#brandday').css('opacity',1);" onclick="location.href = '{$tsConfig.url}/moderacion/'" style="cursor:default;">Hay {$tsConfig.novemods.total} contenido{if $tsConfig.novemods.total != 1}s{/if} esperando revisi&oacute;n</div>
{/if}
</body>
</html>
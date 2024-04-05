<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es" data-theme="light">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{meta facebook=true twitter=true}
{googlefonts fonts="Roboto"}
{phpost css=["estilo.css", "phpost.css", "extras.css"] js=["acciones.js"] scriptGlobal=true}
<script>
$(document).ready(() => {
	notifica.popup({$tsNots});
	mensaje.popup({$tsMPs});
});
</script>
</head>
<body>
{if $tsUser->is_admod == 1}{$tsConfig.install}{/if}
<!--JAVASCRIPT-->

<div id="loading" style="display:none"><img src="{$tsConfig.images}/ajax-loader.gif" alt="Cargando"> Procesando...</div>
<div id="swf"></div>
<div id="js" style="display:none"></div>
<div id="mydialog"></div>
<div class="UIBeeper" id="BeeperBox"></div>
<div id="brandday">
	 <div class="rtop"></div>
	 <div id="maincontainer">
		<!--MAIN CONTAINER-->
		  <header id="head">
				<div id="logo">
					<a id="logoi" title="{$tsConfig.titulo}" href="{$tsConfig.url}">
						<img border="0" align="top" title="{$tsConfig.titulo}" alt="{$tsConfig.titulo}" src="{$tsConfig.images}/space.gif">
					 </a>
				</div>
				<div id="banner">
					 {if $tsPage == 'posts' && $tsPost.post_id}
						  {include "m.global_search.tpl"}
					 {else}
						  {include "m.global_ads_468.tpl"}
					 {/if}
				</div>
		  </header>
		  <main id="contenido_principal">
		  {include "head_menu.tpl"}
		  {include "head_submenu.tpl"}
		  {include "head_noticias.tpl"}
		  <div id="cuerpocontainer">
		  <!--Cuperpo-->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="es" xml:lang="es">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
<link href="{$tsConfig.images}/favicon.ico?{$smarty.now}" rel="shortcut icon" type="image/x-icon" />
<link href="{$tsConfig.tema.t_url}/estilo.css?{$smarty.now}" rel="stylesheet" type="text/css" />
<link href="{$tsConfig.tema.t_url}/phpost.css?{$smarty.now}" rel="stylesheet" type="text/css" />
<link href="{$tsConfig.tema.t_url}/extras.css?{$smarty.now}" rel="stylesheet" type="text/css" />
<link href="{$tsConfig.css}/wysibb.css?{$smarty.now}" rel="stylesheet" type="text/css" />
{includeAsset file="$tsPage.css"}
<script src="{$tsConfig.js}/jquery.min.js?{$smarty.now}" type="text/javascript"></script>
<script src="{$tsConfig.js}/jquery.plugins.js?{$smarty.now}" type="text/javascript"></script>
<script src="{$tsConfig.js}/acciones.js?{$smarty.now}" type="text/javascript"></script>
<script src="{$tsConfig.js}/wysibb.js?{$smarty.now}" type="text/javascript"></script>
{includeAsset file="$tsPage.js"}
{if $tsUser->is_admod || $tsUser->permisos.moacp || $tsUser->permisos.most || $tsUser->permisos.moayca || $tsUser->permisos.mosu || $tsUser->permisos.modu || $tsUser->permisos.moep || $tsUser->permisos.moop || $tsUser->permisos.moedcopo || $tsUser->permisos.moaydcp || $tsUser->permisos.moecp}
<script src="{$tsConfig.js}/moderacion.js?{$smarty.now}" type="text/javascript"></script>
{/if}
{if $tsConfig.c_allow_live}
<link href="{$tsConfig.css}/live.css?{$smarty.now}" rel="stylesheet" type="text/css" />
<script src="{$tsConfig.js}/live.js?{$smarty.now}" type="text/javascript"></script>
{/if}
<script type="text/javascript">
var global_data = {
	user_key:'{$tsUser->uid}',
	postid:'{$tsPost.post_id}',
	fotoid:'{$tsFoto.foto_id}',
	img:'{$tsConfig.images}',
	url:'{$tsConfig.url}',
	domain:'{$tsConfig.domain}',
	s_title: '{$tsConfig.titulo}',
	s_slogan: '{$tsConfig.slogan}'
};
$(document).ready(() =>{
{if $tsNots > 0} notifica.popup({$tsNots}); {/if}
{if $tsMPs > 0 && $tsAction != 'leer'} mensaje.popup({$tsMPs});{/if}
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
		  <div id="head">
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
		  </div>
		  <div id="contenido_principal">
		  {include "head_menu.tpl"}
		  {include "head_submenu.tpl"}
		  {include "head_noticias.tpl"}
		  <div id="cuerpocontainer">
		  <!--Cuperpo-->
<!DOCTYPE html>
<html lang="es" data-bs-theme="dark" data-bs-core="modern">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{meta facebook=false twitter=false}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
{jsdelivr type="js" files=["jquery","bootstrap","iconify","lazyload","croppr"]}
{phpost css="halfmoon.css" js=["acciones.js", "mode.js"] scriptGlobal=true}
{*phpost 
	favicon="favicon.ico" 
	css=[, "$tsPage.css"] 
	js= 
	deny=["moderacion.js", "cuenta.js"]
*}
<script>
$(document).ready(() => {
{if $tsNots > 0}notifica.popup({$tsNots});{/if}
{if $tsMPs > 0 && $tsAction != 'leer'}mensaje.popup({$tsMPs});{/if}
});
</script>
</head>
<body class="ps-md-sbwidth">

	<div id="loading" style="display: none;">
		<div class="d-flex align-items-center p-1">
	  		<div class="spinner-grow spinner-grow-sm text-body-tertiary me-2" role="status" aria-hidden="true"></div> 
	  		Procesando... 
  		</div>
	</div>
	<div id="swf"></div>
	<div id="js" style="display:none"></div>
	<div id="mydialog"></div>
	<div class="UIBeeper" id="BeeperBox"></div>
	<div id="brandday">
	{include "sidebar.tpl"}
	<main>
		<header>
			{include "menu.tpl"}
		</header>
		<section class="position-relative p-3">
			<div style="z-index:999" class="button-mode position-fixed d-flex justify-content-center align-items-center rounded">
				<span id="mode_change"></span>
			</div>

			{if $tsSave || $extOK || $tsError || $tsDelete == 'true'}
			<div class="toast-container position-fixed p-3" style="top: 1rem;right: 1rem;" id="toast-placement-container">
				<div class="toast fade show text-bg-{if $tsSave}success{elseif $extOK || $tsError || $tsDelete == 'true'}danger{/if} border-0 shadow-none" role="alert" aria-live="assertive" aria-atomic="true">
				 	<div class="toast-body">{if $tsSave}Configuraciones guardadas{elseif $extOK}{$extOK}{elseif $tsError}{$tsError}{else}Noticia eliminada.{/if}</div>
				</div>
			</div>
			{/if}
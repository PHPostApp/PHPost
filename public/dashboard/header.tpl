<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{phpost 
	favicon="favicon.ico" 
	css=["halfmoon.css", "$tsPage.css"] 
	js=["acciones.js", "$tsPage.js"] 
	deny=["moderacion.js", "cuenta.js"]
}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
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
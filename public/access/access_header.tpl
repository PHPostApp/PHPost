<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{phpost favicon="favicon.ico" css=["halfmoon.css", "buttons-social.css", "$tsPage.css"] js=["mode.js", "$tsPage.js"] deny=["registro.js"]}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>
	<div id="loading" style="display:none"><img src="{$tsConfig.images}/ajax-loader.gif" alt="Cargando"> Procesando...</div>
	<div id="mydialog"></div>
	<main>
		<section>
			<div style="z-index:999" class="button-mode position-fixed d-flex justify-content-center align-items-center rounded">
				<span id="mode_change"></span>
			</div>
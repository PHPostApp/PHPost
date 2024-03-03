<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
{jsdelivr type="js" files='jquery'}
{phpost css=["halfmoon.css", "buttons-social.css", "access.css"] js="jquery.plugins.js" scriptGlobal=true}
</head>
<body class="bg-{$tsPage}">
	<div id="loading" style="display:none;">Procesando...</div>
	<div id="mydialog"></div>
	<main>
		<section>
			<div style="z-index:999" class="button-mode position-fixed d-flex justify-content-center align-items-center rounded">
				<span id="mode_change"></span>
			</div>
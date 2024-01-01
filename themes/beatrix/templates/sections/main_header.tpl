<!DOCTYPE html>
<html class="no-js" lang="es" data-theme="{$tsConfig.tema.t_name}">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{$tsTitle}</title>
{meta facebook=true twitter=true}
{phpost 
	favicon="favicon.ico" 
	css=["bootstrap.min.css", "app.css", "responsive.css", "$tsPage.css", "wysibb.css"] 
}
</head>
<body>
{if $tsUser->is_admod == 1}{$tsConfig.install}{/if}

  <div id="preloader">
		<div class="spinner">
			<div class="dot1"></div>
			<div class="dot2"></div>
		</div>
	</div>
	<!-- DIV'S FLOTANTES -->
	<div id="swf"></div>
	<div id="js" style="display:none"></div>
	<div id="mydialog"></div>
	<div class="UIBeeper" id="BeeperBox"></div>

	<div id="main-wrapper" class="main-wrapper">
		{include "main_menu.tpl"}
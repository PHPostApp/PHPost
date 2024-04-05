			<!--end-cuerpo-->
			{if $tsPage == 'home'}</div>{/if}
		</main>
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
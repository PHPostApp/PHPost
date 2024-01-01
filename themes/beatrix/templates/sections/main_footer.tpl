		<footer class="theme-footer-area">
			<div class="footer-top pt-80 pb-70">
				<div class="container">
					<div class="row">
						<div class="col-lg-3">
							<div class="footer-widget widget">
								<div class="footer-logo logo-font">
									<a href="{$tsConfig.url}" rel="internal" title="{$tsConfig.titulo}">
										{$tsConfig.titulo}
									</a>
								</div>
								<p>{$tsConfig.titulo} <br> {$tsSeoData.seo_descripcion}.</p>
								<div class="footer-social">
									<a aria-label="Nuestro grupo" target="_blank" title="Discord" rel="noreferrer" href="https://discord.gg/mx25MxAwRe"><iconify-icon icon="logos:discord-icon"></iconify-icon></a>
									<a aria-label="Nuestro grupo" target="_blank" title="Telegram" rel="noreferrer" href="https://t.me/PHPost23"><iconify-icon icon="logos:telegram"></iconify-icon></a>
								</div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="footer-widget widget widget-nav-menu ml-70">
								<h4 class="widget-title">Recursos</h4>
								<ul class="menu">
									<li><a href="{$tsConfig.url}/pages/support/">Soporte</a></li>
									<li><a href="{$tsConfig.url}/pages/cookies-policy/">Política de cookies</a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-2">
							<div class="footer-widget widget widget-nav-menu">
								<h4 class="widget-title">Enlaces útiles</h4>
								<ul class="menu">
									<li><a rel="internal" href="{$tsConfig.url}/pages/ayuda/">Ayuda</a></li>
									<li><a rel="internal" href="{$tsConfig.url}/pages/contacto/">Contacto</a></li>
									<li><a rel="internal" href="{$tsConfig.url}/pages/protocolo/">Protocolo</a></li>
									<li><a rel="internal" href="{$tsConfig.url}/pages/dmca/">Report Abuse - DMCA</a></li>
								</ul>
							</div>
						</div>
						<div class="col-lg-4">
							{include "newsletter.tpl"}
						</div>
					</div>
				</div>
			</div>
			<div class="footer-divider"></div>
			<div class="footer-bottom">
				<div class="container">
					<div class="row">
						<div class="col-lg-6">
							{* 
								# El siguiente contenedor sirve para validar el Copyright 
								# El ID del div NO debe ser alterado de lo contrario nuestro validador
								# tomará al sitio como una web sin copyright 
							*}
							<div class="copyright-text" id="pp_copyright">
								<p>Copyright © <a href="{$tsConfig.url}/" rel="internal"><strong>{$tsConfig.titulo}</strong></a> {$smarty.now|date_format:'Y'}. Todos los derechos reservado</p>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="footer-menu">
								<ul class="footer-nav">
									<li><a rel="internal" href="{$tsConfig.url}/pages/privacidad/">Política de privacidad</a></li>
									<li><a rel="internal" href="{$tsConfig.url}/pages/terminos-y-condiciones/">Condiciones de uso</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<span class="scrolltotop"><iconify-icon icon="uil:angle-up"></iconify-icon></span>
	</div> <!-- #main-wrapper.main-wrapper -->

	{if $tsUser->is_admod && $tsConfig.c_see_mod && $tsConfig.novemods.total}
		<div id="stickymsg" onmouseover="$('#main-wrapper').css('opacity',0.5);" onmouseout="$('#main-wrapper').css('opacity',1);" onclick="location.href = '{$tsConfig.url}/moderacion/'" style="cursor:default;">Hay {$tsConfig.novemods.total} contenido{if $tsConfig.novemods.total != 1}s{/if} esperando revisi&oacute;n</div>
	{/if}

	<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
	{phpost 
		js=["wysibb.js", "acciones.js", "$tsPage.js"] 
		deny=[]
	}
	{phpost js=["app.js"] from='footer'}
	<script>
	{if $tsNots > 0}notifica.popup({$tsNots});{/if}
	{if $tsMPs > 0 && $tsAction != 'leer'}mensaje.popup({$tsMPs});{/if}
	{if $tsPage === 'perfil'}
muro.stream.total = {$tsMuro.total};
	{/if}
	</script>
</body>
</html>
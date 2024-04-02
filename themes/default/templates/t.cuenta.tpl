{include "main_header.tpl"}
<script>
	$(document).ready(() => {
		avatar.uid = '{$tsUser->uid}';
		avatar.current = '{$tsConfig.url}/files/avatar/{if $tsPerfil.p_avatar}{$tsPerfil.user_id}{else}avatar{/if}.jpg';
	});
</script>
<div class="tabbed-d">
	<div id="alerta_guarda"></div>
	<div class="floatL">
		<ul class="menu-tab">
			<li{if $tsAccion == ''} class="active"{/if}><a href="{$tsConfig.url}/cuenta/">Cuenta</a></li>
			<li{if $tsAccion == 'perfil'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/perfil">Perfil</a></li>    
			<li{if $tsAccion == 'block'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/block">Bloqueados</a></li>
			<li{if $tsAccion == 'clave'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/clave">Cambiar Clave</a></li>
			<li{if $tsAccion == 'nick'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/nick">Cambiar Nick</a></li>
			<li{if $tsAccion == 'config'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/config">Privacidad</a></li>
		</ul>
		<a name="alert-cuenta"></a>
		<form class="horizontal" method="post" action="" name="editarcuenta">
         <input type="hidden" name="pagina" value="{$tsAccion}">
			{include "m.cuenta_$tsAccion.tpl"}
		</form>
	</div>
	<div class="floatR">
		{include "m.cuenta_sidebar.tpl"}
	</div>
</div>
<div style="clear:both"></div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/croppr/dist/croppr.min.css">
<script src="https://cdn.jsdelivr.net/combine/npm/iconify-icon,npm/croppr"></script>
<script src="{$tsConfig.js}/cuenta.js?{$smarty.now}"></script>
{include "main_footer.tpl"}
{include "main_header.tpl"}
<div class="container">
	<div class="tabbed-d">
		<div class="floatL">
			<div id="alerta_guarda" style="display: none;"></div>
			<ul class="nav">
				<li class="nav-item"><a class="nav-link{if $tsAccion == ''} active{/if}" href="{$tsConfig.url}/cuenta/">Cuenta</a></li>
				<li class="nav-item"><a class="nav-link{if $tsAccion == 'perfil'} active{/if}" href="{$tsConfig.url}/cuenta/perfil?tab=me">Perfil</a></li>
				<li class="nav-item"><a class="nav-link{if $tsAccion == 'block'} active{/if}" href="{$tsConfig.url}/cuenta/block">Bloqueados</a></li>
				<li class="nav-item"><a class="nav-link{if $tsAccion == 'clave'} active{/if}" href="{$tsConfig.url}/cuenta/clave">Cambiar Clave</a></li>
				<li class="nav-item"><a class="nav-link{if $tsAccion == 'nick'} active{/if}" href="{$tsConfig.url}/cuenta/nick">Cambiar Nick</a></li>
				<li class="nav-item"><a class="nav-link{if $tsAccion == 'config'} active{/if}" href="{$tsConfig.url}/cuenta/config">Privacidad</a></li>
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
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/croppr@2.3.1/dist/croppr.min.css">
<script src="https://cdn.jsdelivr.net/npm/croppr@2"></script>
{include "main_footer.tpl"}
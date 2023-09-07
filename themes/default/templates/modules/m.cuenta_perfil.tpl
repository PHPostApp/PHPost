<div class="content-tabs perfil">
	<input type="hidden" name="tab" value="{$tsTab}">
	<ul class="tabs">
		<li id="me"{if $tsTab == 'me'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/perfil?tab=me">M&aacute;s sobre mi</a></li>
		<li id="fisico"{if $tsTab == 'fisico'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/perfil?tab=fisico">Como soy</a></li>
		<li id="job"{if $tsTab == 'job'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/perfil?tab=job">Formaci&oacute;n y trabajo</a></li>
		<li id="pref"{if $tsTab == 'pref'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/perfil?tab=pref">Intereses y preferencias</a></li>
	</ul>
	{include "m.cuenta_perfil_$tsTab.tpl"}
</div>
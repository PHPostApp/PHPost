<div class="content-tabs perfil">
	<input type="hidden" name="tab" value="{$tsTab}">
	<!--ul class="tabs">
		<li id="me"{if $tsTab == 'me'} class="active"{/if}><a href="{$tsConfig.url}/cuenta/perfil?tab=me">M&aacute;s sobre mi</a></li>
	</ul-->
	{include "m.cuenta_perfil_$tsTab.tpl"}
</div>
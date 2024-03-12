<div id="perfil_wall" status="activo">
	<div id="perfil-form" class="widget">
		{if $tsPrivacidad.mf.v == true}
			{include "m.perfil_muro_form.tpl"}
		{else}
			<div class="emptyData" style="border-top:none">{$tsPrivacidad.mf.m}</div>
		{/if}
	</div>
	<div class="widget clearfix" id="perfil-wall">
		<div id="wall-content">
			{include "m.perfil_muro_story.tpl"}
		</div>
		<!-- more -->
		{if $tsMuro.total >= 10}
			<div class="more-pubs text-center bg-secondary rounded py-2">
				<a href="javascript:muro.stream.loadMore('wall')">Publicaciones m&aacute;s antiguas</a>
				<span style="display: none;"><iconify-icon icon="eos-icons:three-dots-loading" style="color: white;"></iconify-icon></span>
			</div>
		{elseif $tsMuro.total == 0 && $tsUser->is_member}
			<div class="alert color-primary text-center">Este usuario no tiene comentarios, se el primero.</div>
		{/if}
	</div>
</div>
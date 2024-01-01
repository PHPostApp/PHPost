{include "main_header.tpl"}

	{include "m.perfil_head_info.tpl"}
	<div class="container">
		<div class="perfil-main {$tsGeneral.stats.user_rango.1}">
			<!-- SIDEBAR -->
			<div class="perfil-sidebar-left">
				{include "m.perfil_sidebar.tpl"}
			</div>
			<!-- CONTENT -->
			<div class="perfil-content general">
				<div id="info" pid="{$tsInfo.uid}"></div>
				<div id="perfil_content">
					{if $tsPrivacidad.m.v == false}
						<div id="perfil_wall" status="activo" class="widget">
						 	<div class="emptyData">{$tsPrivacidad.m.m}</div>
						 	<script type="text/javascript">
								perfil.load_tab('info', $('#informacion'));
						 	</script>
						</div>
					{elseif $tsType == 'story'}
						{include "m.perfil_story.tpl"}
					{elseif $tsType == 'news'}
						{include "m.perfil_noticias.tpl"}
					{else}
						{include "m.perfil_muro.tpl"}
					{/if}
				</div>
				<div style="display: none;" id="perfil_load">
					<div class="text-center py-4 loading">
						<iconify-icon icon="eos-icons:loading"></iconify-icon>
					</div>
				</div>
			</div>
		</div>
	</div>

{include "main_footer.tpl"}
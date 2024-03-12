<div class="content-tabs bloqueados">
	<fieldset>
		{if $tsBlocks}
			<div class="bloqueados row">
				{foreach from=$tsBlocks item=b}
					<div class="item col-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 d-flex justify-content-between align-items-center">
						<a href="{$tsConfig.url}/perfil/{$b.user_name}" class="item-user d-flex justify-content-start align-items-center">
							<img src="{$tsConfig.url}/files/avatar/{$b.user_id}_120.jpg" class="image rounded" alt="">
							<span>{$b.user_name}</span>
						</a>
						<a title="Desbloquear Usuario" href="javascript:bloquear('{$b.b_auser}', false, 'mis_bloqueados')" class="btn btn-sm btn-primary desbloqueadosU bloquear_usuario_{$b.b_auser}">Desbloquear</a>
					</div>
				{/foreach}
			</div>
		{else}
			<div class="alert bgc-primary color-secondary text-center">No hay usuarios bloqueados</div>
		{/if}
	</fieldset>
</div>
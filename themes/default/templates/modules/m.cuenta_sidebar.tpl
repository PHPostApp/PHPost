<div class="sidebar-tabs clearbeta">
	<h3>Mi Avatar</h3>
	<div class="avatar-big-cont">
		<div style="display: none" class="avatar-loading"></div>
		<img width="120" height="120" alt="" src="{$tsConfig.url}/files/avatar/{if $tsPerfil.p_avatar}{$tsPerfil.user_id}_120{else}avatar{/if}.jpg?t={$smarty.now}" class="avatar-big" id="avatar-img"/>
		<div id="drop-region" class="avatarimg">
			<input type="file" name="desktop" onchange="return cambiarFile(false);" class="browse"/>
		</div>
	</div>
	{if isset($gd_info)}
		<div class="emptyData">{$gd_info}</div>
	{/if}
	<div class="avatar-cambiar" style="text-align: center;padding-bottom: 8px">
		<div class="mBtn btnOk" id="changePC">Desde PC</div>
		<div class="mBtn btn{if $tsConfig.c_allow_upload == '1'}Ok{else}Delete{/if}"{if $tsConfig.c_allow_upload == '1'} id="changeURL"{/if}>Desde Url</div>
		<div id="input_add" style="display: none;">
			<input type="text" name="url" autocomplete="off" placeholder="Url de la imagen" class="browse form-control"/>
				<span class="verify">&check;</span>
			</div>
		</div>
	</div>
	<div class="clearfix"></div>
	<div class="btn-group-socials">
		{foreach $tsConfig.oauth key=i item=social}
			{if $tsPerfil.socials.$i}
				<span class="btn-social btn-inactive btn-{$i}">Vinculado {$i}</span>
			{else}
				<a class="btn-social btn-active btn-{$i}" href="{$social}">Vincular {$i}</a>
			{/if}
		{/foreach}
	</div>
</div>
<div class="clearfix"></div>
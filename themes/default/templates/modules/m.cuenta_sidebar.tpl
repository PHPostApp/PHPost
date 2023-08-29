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
	<div class="porcentaje-total">
		<div class="box" data-percentage="{$tsPerfil.porcentaje}">
		  	<div class="percentage">
		      <div class="completed active"></div>
		  	</div>
		</div>
	</div>
	<div class="clearfix"></div>
</div>

	


						  <div class="clearfix"></div>
						  <h3 style="margin: 25px 0 0; padding: 0" id="porc-completado-label">Perfil completo al {$tsPerfil.porcentaje}%</h3>
						  <div style="margin-top:5px;text-align:center;font-size:13px;margin-bottom:10px;color:#FFF;text-shadow: 0 1px 0px #000" id="porc-completado">
								<div style="background: #CCC;padding:2px;line-height:17px">
									 <div style="width: {$tsPerfil.porcentaje}%; height:17px;border-right:1px solid #004b8d; border-left: 1px solid #004b8d;background: url('{$tsConfig.images}/barra.gif') top left repeat-x;" id="porc-completado-barra">
									 </div>
								</div>
						  </div>
						  <div id="prueba"></div>
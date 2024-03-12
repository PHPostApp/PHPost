<div style="display: none;" id="portadaurl" data-url="{$tsInfo.portada}"></div>
<div class="headinfo background-general">
	<div class="container">
		<div class="background-profile{if empty({$tsInfo.portada})}-empty{/if}"></div>
		<div class="profile-info">
			<div class="profile-picture {$tsInfo.status.css} position-relative">
				<img alt="perfil avatar user" class="image" src="{$tsConfig.images}/mantenimiento.gif" data-src="{$tsConfig.avatar}/{if $tsInfo.p_avatar}{$tsInfo.uid}_120{else}avatar{/if}.jpg?{$smarty.now}"/>
				<iconify-icon id="change-picture" icon="clarity:camera-solid"></iconify-icon>
			</div>
			<div class="profile-data">
				<span class="nickname">@{$tsInfo.nick}</span>
				<span>{if !empty($tsInfo.p_nombre)}{$tsInfo.p_nombre} - {/if}<span class="badge text-white text-uppercase" style="background-color:#{$tsInfo.stats.r_color};">{$tsInfo.stats.r_name}</span>{if !$tsInfo.user_activo || $tsInfo.user_baneado} <span class="badge text-white text-uppercase" style="background-color:#CE152E;">Cuenta {if !$tsInfo.user_activo}desactivada{else}baneada{/if}</span>{/if}</span>
				<span><strong>{$tsInfo.stats.user_amigos}</strong> Amigos • <strong>{$tsInfo.stats.user_seguidos}</strong> Seguidos • <strong>{$tsInfo.stats.user_seguidores}</strong> Seguidores</span>
			</div>
			<div class="profile-buttons">
				<div class="d-flex flex-column">
					{if $tsUser->uid != $tsInfo.uid && $tsUser->is_member}
						{* SEGUIR/DEJAR DE SEGUIR USUARIO *}
						<a class="btn btn-sm btn-danger btn_g unfollow_user_post" onclick="notifica.unfollow('user', {$tsInfo.uid}, notifica.userInPostHandle, $(this).children('span'))" {if $tsInfo.follow == 0}style="display: none;"{/if}><span class="icons unfollow">Dejar de seguir</span></a>
						<a class="btn btn-sm btn-primary btn_g follow_user_post" onclick="notifica.follow('user', {$tsInfo.uid}, notifica.userInPostHandle, $(this).children('span'))" {if $tsInfo.follow == 1}style="display: none;"{/if}><span class="icons follow">Seguir Usuario</span></a>
						<div class="dropdown dropstart w-100">
  							<a href="javascript:perfilJS.opciones()" class="btn btn-secondary d-block btn-sm mt-2 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">M&aacute;s...</a>
					  		<ul class="dropdown-menu" data-popper-placement="left-start">
					  			{* BLOQUEAR USUARIO *}
					    		<li><a class="dropdown-item" href="javascript:bloquear({$tsInfo.uid}, {if $tsInfo.block.bid}false{else}true{/if}, 'perfil')" id="bloquear_cambiar">{if $tsInfo.block.bid}Desbloquear{else}Bloquear{/if}</a></li>
					    		{* DENUNCIAR USUARIO *}
					    		<li><a class="dropdown-item" href="javascript:denuncia.nueva('usuario',{$tsInfo.uid}, '', '{$tsInfo.nick}')">Denunciar</a></li>
					    		{* SUSPENDER USUARIO *}
					    		{if ($tsUser->is_admod || $tsUser->permisos.mosu) && !$tsInfo.user_baneado}
					    			<li><a class="dropdown-item" href="javascript:mod.users.action({$tsInfo.uid}, 'ban', true)">Suspender</a></li>
					    		{/if}
					  		</ul>
						</div>
					{/if}
				</div>
			</div>
		</div>
		<div class="profile-menu">
			<ul id="tabs_menu">
				{if $tsType == 'news' || $tsType == 'story'}
				 	<li class="selected"><a href="#" onclick="perfil.load_tab('news', this); return false">{if $tsType == 'story'}Publicaci&oacute;n{else}Noticias{/if}</a></li>
				{/if}
				<li {if $tsType == 'wall'}class="selected"{/if}><a href="#" onclick="perfil.load_tab('wall', this); return false">Muro</a></li>
				<li><a href="#" onclick="perfil.load_tab('actividad', this); return false" id="actividad">Actividad</a></li>
				<li><a href="#" onclick="perfil.load_tab('info', this); return false" id="informacion">Informaci&oacute;n</a></li>
				<li><a href="#" onclick="perfil.load_tab('posts', this); return false">Posts</a></li>
				<li><a href="#" onclick="perfil.load_tab('seguidores', this); return false" id="seguidores">Seguidores</a></li>
				<li><a href="#" onclick="perfil.load_tab('siguiendo', this); return false" id="siguiendo">Siguiendo</a></li>
				<li><a href="#" onclick="perfil.load_tab('medallas', this); return false">Medallas</a></li>
			</ul>
		</div>
	</div>
</div>
{$tsInfo.user_perfil}
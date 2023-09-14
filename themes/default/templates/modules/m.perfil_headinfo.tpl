<div class="perfil-user">

	<div class="perfil-box">

		<div class="perfil-data">
			<div class="perfil-avatar">
				<a href="{$tsConfig.url}/perfil/{$tsInfo.nick}">
					<img alt="perfil avatar user" class="image" src="{$tsConfig.images}/mantenimiento.gif" data-src="{$tsConfig.avatar}/{if $tsInfo.p_avatar}{$tsInfo.uid}_120{else}avatar{/if}.jpg?{$smarty.now}"/>
				</a>
			</div>
			<div class="perfil-info">
				<h2 class="nick">{$tsInfo.nick}</h2>
				<span class="realname">{$tsInfo.p_nombre}</span>
				<span class="frase-personal">{$tsInfo.p_mensaje}</span>
				<span class="bio">{if $tsInfo.p_nombre != ''}{$tsInfo.p_nombre}. {/if}Vive en <span id="info_pais">{$tsInfo.user_pais}</span> y se uni&oacute; {$tsInfo.user_registro|hace}.</span>
				{if $tsUser->uid != $tsInfo.uid && $tsUser->is_member}
					<span class="ex_opts">
						<a href="javascript:bloquear({$tsInfo.uid}, {if $tsInfo.block.bid}false{else}true{/if}, 'perfil')" id="bloquear_cambiar">{if $tsInfo.block.bid}Desbloquear{else}Bloquear{/if}</a>
						<a href="#" onclick="denuncia.nueva('usuario',{$tsInfo.uid}, '', '{$tsInfo.nick}'); return false">Denunciar</a>
						{if ($tsUser->is_admod || $tsUser->permisos.mosu) && !$tsInfo.user_baneado}<a href="#" onclick="mod.users.action({$tsInfo.uid}, 'ban', true); return false;" style="background-color:#CE152E;">Suspender</a>{/if}
						{if !$tsInfo.user_activo || $tsInfo.user_baneado}<span style="background-color:#CE152E;">Cuenta {if !$tsInfo.user_activo}desactivada{else}baneada{/if}</span>{/if}
					</span>
					<br />
					<a class="btn_g unfollow_user_post" onclick="notifica.unfollow('user', {$tsInfo.uid}, notifica.userInPostHandle, $(this).children('span'))" {if $tsInfo.follow == 0}style="display: none;"{/if}><span class="icons unfollow">Dejar de seguir</span></a>
					<a class="btn_g follow_user_post" onclick="notifica.follow('user', {$tsInfo.uid}, notifica.userInPostHandle, $(this).children('span'))" {if $tsInfo.follow == 1}style="display: none;"{/if}><span class="icons follow">Seguir Usuario</span></a>
				{/if}
			</div>
		</div>
		
		<div class="user-level">
			<ul>
				<li style="color:#{$tsInfo.stats.r_color}; background-color:#FFF">
					<strong style="color:#{$tsInfo.stats.r_color}">{$tsInfo.stats.r_name}</strong>
					<span>Rango</span>
					<span title="{$tsInfo.status.t}" class="qtip status {$tsInfo.status.css}"></span>
				</li>
				<li>
					<strong>{$tsInfo.stats.user_puntos}</strong>
					<span>Puntos</span>
				</li>
				<li>
					<strong>{$tsInfo.stats.user_posts}</strong>
					<span>Posts</span>
				</li>
				<li>
					<strong>{$tsInfo.stats.user_comentarios}</strong>
					<span>Comentarios</span>
				</li>
				<li>
					<strong>{$tsInfo.stats.user_seguidores}</strong>
					<span>Seguidores</span>
				</li>
				<li>
					<strong>{$tsInfo.stats.user_fotos}</strong>
					<span>Fotos</span>
				</li>
			</ul>
		</div>
	</div>
	<div class="menu-tabs-perfil">
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
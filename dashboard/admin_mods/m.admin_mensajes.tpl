<div class="boxy-title">
	<h3>{if $tsAct == ''}Control de Mensajes{elseif $tsAct == 'leer'}Leer Mensaje{/if}</h3>		
</div>
<div id="res" class="boxy-content" style="position:relative">
	{if !$tsAct}
		{if !$tsCmp.data}
			<div class="phpostAlfa">No hay mensajes.</div>
		{else}
			<table cellpadding="0" cellspacing="0" border="0" class="admin_table" width="100%" align="center">
				<thead>
					<th>Rango</th>
					<th>De:</th>
					<th>Para:</th>
					<th>Enviado</th>
					<th>Asunto:</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					{foreach from=$tsCmp.data item=m}
					<tr id="mp_{$m.mp_id}">
						<td><img class="qtip" title="{$m.r_name}" src="{$tsConfig.default}/images/icons/ran/{$m.r_image}" /></td>
						<td align="left"><a href="{$tsConfig.url}/perfil/{$m.user_name}" class="hovercard" uid="{$m.mp_from}" style="color:#{$m.r_color};">{$m.user_name}</a></td>
						<td><a style="color:#{$tsUser->getUserRango($m.mp_to)};" class="hovercard" uid="{$m.mp_to}" href="{$tsConfig.url}/perfil/{$tsUser->getUserName($m.mp_to)}">{$tsUser->getUserName($m.mp_to)}</a></td>
						<td>{$m.mp_date|hace}</td>
						<td style="cursor:pointer;"class="qtip" title="{$m.mp_subject}<hr>{$m.mp_preview}">{$m.mp_subject|truncate:40}</td>
						<td class="admin_actions">
							<a href="{$tsConfig.url}/admin/mensajes/leer/{$m.mp_id}"><img src="{$tsConfig.assets}/images/icon-mensajes-recibidos.gif" title="Leer Mensajes" /></a>{if $m.user_id!=$tsUser->uid}<a href="#" onclick="mod.users.action({$m.user_id}, 'aviso', false); return false;"><img src="{$tsConfig.assets}/images/icons/warning.png" title="Enviar Alerta" /></a>{/if}
							<a href="#" onclick="admin.mp.borrar('{$m.mp_id}'); return false"><img src="{$tsConfig.assets}/images/icons/close.png" title="Eliminar Mensaje" /></a>
						</td>
					</tr>
					{/foreach}
				</tbody>
				<tfoot>
					<td colspan="8" class="pag-compl">{if $tsCmp.data > 10}{$tsCmp.pages}{/if}</td>
				</tfoot>
			</table>
		{/if}
	{elseif $tsAct == 'leer'}
		<div class="frommp">
		   <h2>{$tsDatamp.mp_subject}</h2>
		</div>
		<ul class="lest-mp">
			<li><h2>Entre <a style="color:#{$tsUser->getUserRango($tsDatamp.mp_from)};" class="hovercard" uid="{$tsDatamp.mp_from}" href="{$tsConfig.url}/perfil/{$tsUser->getUserName($m.$tsDatamp.mp_from)}">{$tsUser->getUserName($tsDatamp.mp_from)}</a> y <a style="color:#{$tsUser->getUserRango($tsDatamp.mp_to)};" class="hovercard" uid="{$tsDatamp.mp_to}" href="{$tsConfig.url}/perfil/{$tsUser->getUserName($m.$tsDatamp.mp_to)}">{$tsUser->getUserName($tsDatamp.mp_to)}</a></h2>
		   </li>
			{foreach from=$tsLeermp item=m}
			  {if !$m.mr_id}
			  		<div class="phpostAlfa">Respuesta eliminada</div>
			  {else}
			  		<li id="rmp_{$m.mr_id}">
					  	<div class="delres">
					   {if $m.mr_from!=$tsUser->uid}<a href="#" onclick="mod.users.action({$m.mr_from}, 'aviso', false); return false;"><img style="border:none;width:16px; height:16px;" src="http://i.i.imgur.com/Hrcm8US.png" class="qtip" title="Enviar Alerta" /></a>{/if}
					   	<a href="#" onclick="admin.rmp.borrar('{$m.mr_id}'); return false"><img style="border:none;width:16px; height:16px;" src="http://i.i.imgur.com/eJFVO4p.png" class="qtip" title="Eliminar Respuesta" /></a>
					  	</div>
					  	<a href="{$tsConfig.url}/perfil/{$tsUser->getUserName($m.mr_from)}"><img src="{$tsConfig.url}/files/avatar/{$m.mr_from}_50.jpg"></a>
					  	<span><a style="color:#{$tsUser->getUserRango($m.mr_from)};" class="hovercard" uid="{$m.mr_from}" href="{$tsConfig.url}/perfil/{$tsUser->getUserName($m.mr_from)}">{$tsUser->getUserName($m.mr_from)}</a>{$m.mr_date|hace}</span>
					  	<div class="bodymp">{$m.mr_body}</div>
				  </li>
			  {/if}
			{/foreach}
		</ul>	
	{/if}
</div>
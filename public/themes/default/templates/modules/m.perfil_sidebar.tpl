<div style="margin-bottom: 10px">{$tsConfig.ads_300}</div>
{if $tsUser->is_admod == 1 && $tsUser->uid != $tsInfo.uid}
	<div class="widget w-seguidores clearfix">
		<a class="mBtn btnOk" style="display: block;text-align: center;margin-bottom: 1rem;font-size: .8rem;font-weight: 700;" href="{$tsConfig.url}/admin/users?act=show&amp;uid={$tsInfo.uid}">Editar a {$tsInfo.nick}</a>
	</div>
{/if}
{if $tsUser->uid != $tsInfo.uid}
	{if $tsUser->is_member}
		<div class="widget w-seguidores clearfix">
			<a href="#" class="mBtn btnGreen" style="display: block;text-align: center;margin-bottom: 1rem;font-size: .8rem;font-weight: 700;" onclick="mensaje.nuevo('{$tsInfo.nick}','','',''); return false">Enviar Mensaje Privado</a>
		</div>
	{/if}
{/if}
{if $tsInfo.p_socials != ''}
<div class="widget w-seguidores clearfix">
	<div class="title-w clearfix">
		<h3>Redes Sociales</h3>
	</div>
	<div>
	  	{assign var="redesConContenido" value=[]}
      {assign var="redesSinContenido" value=[]}

      {foreach $tsRedes key=name item=red}
         {if $tsInfo.p_socials.$name != ''}
            {assign var="redConContenido" value="<a class='sitio icon icon_$name' target='_blank' href='{$red.url}/{$tsInfo.p_socials.$name}' title='{$red.nombre}' class=''></a>"}
            {append var="redesConContenido" value=$redConContenido}
         {else}
            {assign var="redSinContenido" value="<span class='sitio icon icon_$name icon_off'></span>"}
            {append var="redesSinContenido" value=$redSinContenido}
         {/if}
      {/foreach}

      {foreach $redesConContenido as $redConContenidoItem}
         {$redConContenidoItem}
      {/foreach}

      {foreach $redesSinContenido as $redSinContenidoItem}
         {$redSinContenidoItem}
      {/foreach}
  	</div>
  	<br>
{/if}

						<div class="widget w-medallas clearfix">
								<div class="title-w clearfix">
									<h3>Medallas</h3>
									<span>{$tsGeneral.m_total}</span>
								</div>
									 {if $tsGeneral.m_total}
								<ul class="clearfix">
										  {foreach from=$tsGeneral.medallas item=m}
							<img src="{$tsConfig.images}/icons/med/{$m.m_image}_16.png" class="qtip" title="{$m.m_title} - {$m.m_description}"/>
										  {/foreach}
								</ul>
									 {if $tsGeneral.m_total >= 21}<a href="#medallas" onclick="perfil.load_tab('medallas', $('#medallas'));" class="see-more">Ver m&aacute;s &raquo;</a>{/if}
									 {else}
									 <div class="emptyData">No tiene medallas</div>
									 {/if}
							</div>
								<div class="widget w-seguidores clearfix">
								<div class="title-w clearfix">
									<h3>Seguidores</h3>
									<span>{$tsInfo.stats.user_seguidores}</span>
								</div>
									 {if $tsGeneral.segs.data}
								<ul class="clearfix">
										  {foreach from=$tsGeneral.segs.data item=s}
									<li><a href="{$tsConfig.url}/perfil/{$s.user_name}" class="hovercard" uid="{$s.user_id}" style="display:inline-block;"><img src="{$tsConfig.url}/files/avatar/{$s.user_id}_50.jpg" width="32" height="32"/></a></li>
										  {/foreach}
								</ul>
									 {if $tsGeneral.segs.total >= 21}<a href="#seguidores" onclick="perfil.load_tab('seguidores', $('#seguidores'));" class="see-more">Ver m&aacute;s &raquo;</a>{/if}
									 {else}
									 <div class="emptyData">No tiene seguidores</div>
									 {/if}
							</div>
								<div class="widget w-siguiendo clearfix">
									 <div class="title-w clearfix">
										<h3>Siguiendo</h3>
										<span>{$tsGeneral.sigd.total}</span>
									 </div>
									 {if $tsGeneral.sigd.data}
								<ul class="clearfix">
										  {foreach from=$tsGeneral.sigd.data item=s}
									<li><a href="{$tsConfig.url}/perfil/{$s.user_name}" class="hovercard" uid="{$s.user_id}" style="display:inline-block;"><img src="{$tsConfig.url}/files/avatar/{$s.user_id}_50.jpg" width="32" height="32"/></a></li>
										  {/foreach}
								</ul>
									 {if $tsGeneral.sigd.total >= 21}<a href="#siguiendo" onclick="perfil.load_tab('siguiendo', $('#siguiendo'));" class="see-more">Ver m&aacute;s &raquo;</a>{/if}
									 {else}
									 <div class="emptyData">No sigue usuarios</div>
									 {/if}
							</div>
							<div class="widget w-comunidades clearfix">
                            <div class="title-w clearfix">
                              <h3>Comunidades</h3>
                              <span>{$tsGeneral.comus_total}</span>
                            </div>
                            {if $tsGeneral.comus}
            				<ul class="clearfix">
                                {foreach from=$tsGeneral.comus item=c}
            					<li style="width: 100%;margin-bottom: 5px;">
                                <a href="{$tsConfig.url}/comunidades/{$c.c_nombre_corto}/" class="floatL" style="margin-right: 3px;"><img src="{$tsConfig.url}/files/uploads/c_{$c.c_id}.jpg" width="32" height="32"/></a>
                                <a href="{$tsConfig.url}/comunidades/{$c.c_nombre_corto}/" style="color:#006595;font-weight:bold;font-size:12px;">{$c.c_nombre}</a>
                                <span style="display: block;font-size: 11px;color: #999;">{$c.c_miembros} Miembros</span>
                                </li>
                                {/foreach}
            				</ul>
                            <a href="#comunidades" onclick="perfil.load_tab('comunidades', $('#comunidades'));" class="see-more">Ver todas &raquo;</a>
                            {else}
                            <div class="emptyData">No participa en ninguna comunidad</div>
                            {/if}
            			</div>
						{if $tsInfo.can_hits}
						<div class="widget w-visitas clearfix">
									 <div class="title-w clearfix">
										<h3>&Uacute;ltimas visitas</h3>
										<span>{$tsInfo.visitas_total}</span>
									 </div>
									 {if $tsInfo.visitas}
								<ul class="clearfix">
										  {foreach from=$tsInfo.visitas item=v}
									<li><a href="{$tsConfig.url}/perfil/{$v.user_name}" class="hovercard" uid="{$v.user_id}" style="display:inline-block;"><img src="{$tsConfig.url}/files/avatar/{$v.user_id}_50.jpg" class="vctip" title="{$v.date|hace:true}" width="32" height="32"/></a></li>
										  {/foreach}
								</ul>
									 {else}
									 <div class="emptyData">No tiene visitas</div>
									 {/if}
							</div>
						{/if}
<div class="com_new_box">
    <div class="com_box_title clearfix"><h2>Buscar en la Comunidad</h2></div>
    <div class="clearfix">
        <form action="{$tsConfig.url}/comunidades/buscar/">
            <input type="text" class="input_text floatL" name="q" style="width: 202px;margin-right: 3px;" /><input type="submit" value="Buscar" class="input_button ibg" />
            <input type="hidden" name="tipo" value="temas" />
			<input type="hidden" name="comid" value="{$tsCom.c_id}" />
        </form>
    </div>
</div>

<div class="com_new_box">
    <div class="com_box_title clearfix"><h2>Staff de la comunidad</h2></div>
    <div class="clearfix">
        {foreach from=$tsStaff item=a}
        <a href="{$tsConfig.url}/perfil/{$a.user_name}" uid="{$a.m_user}" class="floatL hovercard com_staff_avatar {if $a.m_permisos == 5}admin{/if}">
            <img src="{$tsConfig.url}/files/avatar/{$a.m_user}_120.jpg" width="55" height="55" />
            <span class="csa_status {if $a.is_on}online{/if}" title="{if $a.is_on}Online{else}Offline{/if}"></span>
        </a>    
        {/foreach}
    </div>
</div>

<div class="com_new_box">
    <div class="com_box_title clearfix"><h2>Comentarios recientes</h2></div>
    <div class="com_box_body clearfix">
    	{if $tsRespuestas}
        {foreach from=$tsRespuestas item=r}
        <div class="com_list_element" {if $r.t_estado == 1}style="opacity: 0.5;background: #000;" title="El tema ha sido eliminado"{/if}><a class="cle_autor" href="{$tsConfig.url}/perfil/{$r.user_name}">{$r.user_name}</a><a class="cle_title" href="{$tsConfig.url}/comunidades/{$tsCom.c_nombre_corto}/{$r.t_id}/{$r.t_titulo|seo}.html#coment_id_{$r.r_id}">{$r.t_titulo|truncate:30}</a></div>
        {/foreach}
        {else}
        <div class="com_bigmsj_blue">No hay comentarios recientes</div>
        {/if}
    </div>
</div>

<div class="com_new_box">
    <div class="com_box_title clearfix"><h2>&Uacute;ltimos miembros</h2></div>
    <div class="clearfix">
        {foreach from=$tsMiembros item=a}
        <a href="{$tsConfig.url}/perfil/{$a.user_name}" class="floatL hovercard" uid="{$a.m_user}" style="margin-right:1px;margin-bottom:1px;"><img src="{$tsConfig.url}/files/avatar/{$a.m_user}_50.jpg" width="35" height="35" /></a>
        {/foreach}
    </div>
    <div class="com_box_title clearfix"><div class="cbt_right"><a href="{$tsConfig.url}/comunidades/{$tsCom.c_nombre_corto}/miembros/">Ver m&aacute;s &raquo;</a></div></div>
</div>

<div class="com_new_box">
    <div class="com_box_title clearfix">
        <h2>Top temas</h2>
        <div class="cbt_right cbt_list"><span id="com_change_hover">Semana</span>
            <ul id="com_change_list">
                <li class="pop_list_semana active"><a href="javascript:com.pop_list_change('Semana');">Semana</a></li>
                <li class="pop_list_mes"><a href="javascript:com.pop_list_change('Mes');">Mes</a></li>
                <li class="pop_list_historico"><a href="javascript:com.pop_list_change('Historico');">Hist&oacute;rico</a></li>
            </ul>
        </div>
    </div>
    <div class="com_box_body clearfix">
        <div id="com_change_pop">
            <div id="ccp_semana" style="display: block;">
            	{if $tsTop.semana}
                {foreach from=$tsTop.semana item=t key=i}
                <div class="com_list_element" {if $t.t_estado == 1}style="opacity: 0.5;background: #000;" title="El tema ha sido eliminado"{/if}>
                    <span class="cle_item">{$i+1}</span>
                    <a href="{$tsConfig.url}/comunidades/{$tsCom.c_nombre_corto}/{$t.t_id}/{$t.t_titulo|seo}">{$t.t_titulo|truncate:30}</a>
                    <span class="cle_number">{$t.t_votos_pos}</span>
                </div>
                {/foreach}
                {else}
                <div class="com_bigmsj_blue">Nada por aqu&iacute;</div>
                {/if}
            </div>
            <div id="ccp_mes" style="display: none;">
            	{if $tsTop.mes}
                {foreach from=$tsTop.mes item=t key=i}
                <div class="com_list_element" {if $t.t_estado == 1}style="opacity: 0.5;background: #000;" title="El tema ha sido eliminado"{/if}>
                    <span class="cle_item">{$i+1}</span>
                    <a href="{$tsConfig.url}/comunidades/{$tsCom.c_nombre_corto}/{$t.t_id}/{$t.t_titulo|seo}">{$t.t_titulo|truncate:30}</a>
                    <span class="cle_number">{$t.t_votos_pos}</span>
                </div>
                {/foreach}
                {else}
                <div class="com_bigmsj_blue">Nada por aqu&iacute;</div>
                {/if}
            </div>                
            <div id="ccp_historico" style="display: none;">
            	{if $tsTop.historico}
                {foreach from=$tsTop.historico item=t key=i}
                <div class="com_list_element" {if $t.t_estado == 1}style="opacity: 0.5;background: #000;" title="El tema ha sido eliminado"{/if}>
                    <span class="cle_item">{$i+1}</span>
                    <a href="{$tsConfig.url}/comunidades/{$tsCom.c_nombre_corto}/{$t.t_id}/{$t.t_titulo|seo}">{$t.t_titulo|truncate:30}</a>
                    <span class="cle_number">{$t.t_votos_pos}</span>
                </div>
                {/foreach}
                {else}
                <div class="com_bigmsj_blue">Nada por aqu&iacute;</div>
                {/if}
            </div>
        </div>
    </div>
</div>
    
<small><i class="com_icon icon_denuncia" style="vertical-align:top;margin-right:1px;opacity: 0.5;"></i><a href="#" onclick="denuncia.nueva('comunidad',{$tsCom.c_id}, '{$tsCom.c_nombre}', ''); return false;">Denunciar Comunidad</a> - <a href="{$tsConfig.url}/comunidades/{$tsCom.c_nombre_corto}/mod-history/">Historial</a></small>
<div class="com_tema_autor clearfix">
	<div class="com_box_title clearfix">
        <h2><a href="{$tsConfig.url}/perfil/{$tsAutor.user_name}">{$tsAutor.user_name}</a></h2>
        <div class="cbt_right">
            <img src="{$tsConfig.default}/images/icons/ran/{$tsAutor.rango.r_image}" class="qtip" title="{$tsAutor.rango.r_name}" />
            <img src="{$tsConfig.default}/images/icons/{if $tsAutor.user_sexo == 0}female{else}male{/if}.png" class="qtip" title="{if $tsAutor.user_sexo == 0}Mujer{else}Hombre{/if}" />
            <img src="{$tsConfig.default}/images/flags/{$tsAutor.pais.icon}.png" style="padding:2px" class="qtip" title="{$tsAutor.pais.name}" />
        </div>
    </div>
    <div class="com_box_body clearfix">
    	<div class="cta_avatar floatL">
        	<a href="{$tsConfig.url}/perfil/{$tsAutor.user_name}">
                <img alt="Ver perfil de {$tsAutor.user_name}" src="{$tsConfig.url}/files/avatar/{$tsAutor.user_id}_120.jpg" width="120" height="120"/>
            </a>
            <span title="{$tsAutor.status.t}" class="cta_status {$tsAutor.status.css} qtip">{$tsAutor.rango.r_name}</span>
        </div>
        <div class="cta_detalles floatL">
        	<ul class="ctad_items">
            	<li><i class="com_icon icon_eye"></i> <strong>{$tsAutor.user_seguidores|number_format:0:",":"."}</strong> Seguidores</li>
                <li><i class="com_icon icon_puntos"></i> <strong>{$tsAutor.user_puntos|number_format:0:",":"."}</strong> Puntos</li>
                <li><i class="com_icon icon_temas"></i> <strong>{$tsAutor.user_temas|number_format:0:",":"."}</strong> Temas</li>
                <li><i class="com_icon icon_comentarios"></i> <strong>{$tsAutor.user_comentarios|number_format:0:",":"."}</strong> Comentarios</li>
            </ul>
            <div class="clearfix">
                {if !$tsUser->is_member}
                <a href="#" class="input_button" onclick="registro_load_form(); return false"><i class="com_icon icon_eye"></i>Seguir Usuario</a>
                {elseif $tsAutor.user_id != $tsUser->uid}
                <a href="#" class="input_button follow_user_post" id="follow_user" {if $tsAutor.follow > 0}style="display:none"{/if} onclick="notifica.follow('user', {$tsAutor.user_id}, notifica.userInPostHandle, $(this).children('i'))"><i class="com_icon icon_eye"></i>Seguir</a>
                <a href="#" class="input_button ibg unfollow_user_post" id="unfollow_user" style="{if !$tsAutor.follow}display: none{/if}" onclick="notifica.unfollow('user', {$tsAutor.user_id}, notifica.userInPostHandle, $(this).children('i'))" title="Dejar de seguir"><i class="com_icon icon_eye"></i>Siguiendo</a>
                {/if}
            </div>
        </div>
    </div>
</div>
{include "m.global_ads_300.tpl"}
<br class="spacer"/>
{if $tsAutor.user_id == $tsUser->uid || $tsComunidades.mi_rango >= 4 || $tsUser->is_admod}
<div class="com_new_box">
    <div class="com_box_title clearfix"><h2>Opciones</h2></div>
    <div class="com_box_body clearfix">
        {if $tsTema.t_estado == 1}
        	<a href="javascript:com.reactivar_tema();" class="input_button"><i class="com_icon icon_editar"></i>Reactivar</a>
        {else}
            <a href="{$tsConfig.url}/comunidades/{$tsComunidades.c_nombre_corto}/editar-tema/{$tsTema.t_id}/" class="input_button">Editar</a>
            {if $tsAutor.user_id == $tsUser->uid}
            <a href="javascript:com.del_tema();" class="input_button ibr">Eliminar</a>
            {else}
            <a href="javascript:com.del_mod_tema();" class="input_button ibr">Eliminar</a>
            {/if}
        {/if}
    </div>
</div>
{/if}

<div class="com_new_box">
    <div class="com_box_title clearfix"><h2>Comentarios recientes</h2></div>
    <div class="com_box_body clearfix">
    	{if $tsLastRespuestas}
        {foreach from=$tsLastRespuestas item=r}
        <div class="com_list_element"><a class="cle_autor" href="{$tsConfig.url}/perfil/{$r.user_name}">{$r.user_name}</a><a class="cle_title" href="{$tsConfig.url}/comunidades/{$tsComunidades.c_nombre_corto}/{$r.t_id}/{$r.t_titulo|seo}.html#coment_id_{$r.r_id}">{$r.t_titulo|truncate:30}</a></div>
        {/foreach}
        {else}
        <div class="com_bigmsj_blue">No hay comentarios recientes</div>
        {/if}
    </div>
</div>

<small><i class="com_icon icon_denuncia" style="vertical-align:top;margin-right:1px;opacity: 0.5;"></i><a href="#" onclick="denuncia.nueva('tema',{$tsTema.t_id}, '{$tsTema.t_titulo}', '{$tsAutor.user_name}'); return false;">Denunciar Tema</a> - <a href="{$tsConfig.url}/comunidades/{$tsComunidades.c_nombre_corto}/mod-history/">Historial</a></small>

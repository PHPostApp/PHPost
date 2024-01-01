<div class="com_tema_comentarios clearfix">
	<div class="com_box_title clearfix"><h2><span id="cbt_val">{$tsTema.t_respuestas|number_format:0:',':'.'}</span> comentarios</h2></div>
    {if !$tsTema.t_respuestas && $tsTema.t_cerrado == 0}<div class="com_bigmsj_blue">No hay comentarios. ¡S&eacute; el primero!</div>{/if}
    {if $tsTema.t_cerrado == 1 && $tsUser->uid != $tsTema.t_autor || !$tsUser->is_admod}<div class="com_bigmsj_yellow">El tema est&aacute; cerrado y no se permiten comentarios</div>{/if}
    
    <div id="result_answers">{include file='t.comus_ajax/c.pages_respuestas.tpl'}</div> 
    
    {if $tsUser->is_member && $tsCom.es_miembro || $tsCom.mi_rango >= 3 || $tsUser->is_admod}
    <div class="ctc_form clearfix">
    	<div class="com_bigmsj_red" style="display:none;"></div>
    	<div id="procesando"></div>
    	<div class="ctcf_avatar"><a href="{$tsConfig.url}/perfil/{$tsUser->nick}"><img src="{$tsConfig.url}/files/avatar/{$tsUser->uid}_50.jpg" width="48" height="48" /></a></div>
        <div class="ctcf_add_coment floatL clearfix">
        	<textarea id="markit_resp" class="input_text" style="width: 535px;border-radius: 0 0 4px 4px;border-top: 0;resize: vertical;"></textarea>
            <input type="button" class="input_button floatL" id="btn_newcom" value="Comentar" onclick="com.add_respuesta({$tsTema.t_id});" />
            <div id="markit_emoticon" class="floatL" style="display:none;margin-left: 5px;margin-top: 8px;">{include "m.global_emoticons.tpl"}</div>
        </div>
    </div>
    {elseif !$tsCom.es_miembro || !$tsUser->is_member}
    <div class="com_bigmsj_yellow">Tienes que ser miembro para responder en este tema</div>
    {elseif $tsCom.mi_rango < 3}
    <div class="com_bigmsj_yellow">Tu rango no te permite realizar comentarios en esta comunidad</div>
    {/if}
</div>

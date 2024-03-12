<form action="" method="post" id="add_new_tema">
<div class="com_left">
	<div class="com_add_tema">
    	<div class="cat_info"><a href="{$tsConfig.url}/pages/protocolo/" title="Protocolo">Importante: antes de crear un tema lee el protocolo.</a></div>
    	<div class="cat_item clearfix">
        	<label>T&iacute;tulo</label>
            <input type="text" name="titulo" class="input_text required" style="width: 400px;padding: 8px;" maxlength="60" value="{$tsTema.t_titulo}" />
        </div>
        <div class="cat_item clearfix">
        	<label>Cuerpo</label>
            <textarea type="text" name="cuerpo" id="markItUp" class="input_text required" style="width:586px;min-height: 250px;border-radius: 0 0 5px 5px;">{$tsTema.t_cuerpo}</textarea>
        </div>
        {if $tsTema.t_autor && $tsTema.t_autor != $tsUser->uid}
        <div class="cat_item clearfix">
        	<label>Causa de la moderac&oacute;n</label>
            <input type="text" name="razon" class="input_text required" style="width: 582px;padding: 8px;" maxlength="80" />
        </div>
        {/if}
        <div class="cat_item clearfix">
        	<input type="hidden" name="temaid" value="{$tsTema.t_id}" />
            <input type="button" class="input_button ibg" value="Continuar" onclick="com.crear_tema()" />
            <input type="button" class="input_button" value="Guardar en borradores" onclick="com.save_borrador()" />
            <input type="hidden" name="bid" value="{$tsTema.b_id}" />
            <i id="msj_borrador"></i>
        </div>
    </div>
</div>
<div class="com_right">
	<div class="com_add_tema">
    	<div class="cat_item clearfix" style="margin: 0;">
        	<label>Opciones</label>
            <div class="cat_option clearfix">
            	<input type="checkbox" name="cerrado" class="floatL" {if $tsTema.t_cerrado == 1}checked="checked"{/if} /><label> Cerrar resuestas</label>
                <p>Nadie podr&aacute; responder en este tema</p>
            </div>
            {if $tsComunidades.mi_rango >= 4 || $tsUser->is_admod}
            <div class="cat_option clearfix">
            	<input type="checkbox" name="sticky" class="floatL" {if $tsTema.t_sticky == 1}checked="checked"{/if} /><label> Sticky</label>
                <p>El tema quedar&aacute; destacado</p>
            </div>
            {/if}
        </div>
    </div>
</div>
</form>
<div class="com_home_left">
	<div class="com_box_title clearfix">
    	<h2>&Uacute;ltimos temas</h2>
        <div class="cbt_right">
            <select class="com_select_home" onchange="com.ir_a_categoria(this.value)">
            	<option value="{if $tsAct}-1{/if}">{if $tsAct}Ver todas{else}Seleccionar categor&iacute;a{/if}</option>
                {foreach from=$tsCats item=c}
                <option value="{$c.c_seo}" {if $tsAct == $c.c_seo}selected="selected"{/if}>{$c.c_nombre}</option>
                {/foreach}
            </select>
      	</div>
	</div>
    {if $tsLastTemas.data}
    <div class="com_list_temas">
        {foreach from=$tsLastTemas.data item=t}
        <div class="com_list_element clearfix" {if $c.c_estado == 1 || $t.t_estado == 1}style="opacity: 0.5;background: #000;" title="La tema ha sido eliminado"{/if}>
        	<i class="com_icon {$t.sub_cat.c_seo}"></i>
            <a href="{$tsConfig.url}/comunidades/{$t.c_nombre_corto}/{$t.t_id}/{$t.t_titulo|seo}.html">{$t.t_titulo|truncate:45}</a>
            <div class="cli_info">En <a href="{$tsConfig.url}/comunidades/{$t.c_nombre_corto}/">{$t.c_nombre}</a> Por <a href="{$tsConfig.url}/perfil/{$t.user_name}/">{$t.user_name}</a></div>
        </div>
        {/foreach}
    </div>
    <div class="com_temas_footer">
    	{if $tsPages.next <= $tsPages.pages || $tsPages.prev > 0}
    	<div class="com_msj_blue clearfix">
        	{if $tsPages.prev > 0 && $tsPages.max == false}<a href="pagina.{$tsPages.prev}" class="floatL">&laquo; Anterior</a>{/if}
            {if $tsPages.next <= $tsPages.pages}<a href="pagina.{$tsPages.next}" class="floatR">Siguiente &raquo;</a>
            {elseif $tsPages.max == true}<a href="pagina.2">Siguiente &raquo;</a>{/if}
        </div>
        {/if}
    </div>
    {else}
    	<div class="com_bigmsj_blue" style="margin-top: 5px;">No se han creado temas {if $tsAct}para esta categor&iacute;a{else}a&uacute;n{/if}</div>
    {/if}
</div>
<div class="com_home_center">
    <div class="com_new_box">
        <div class="com_box_title clearfix"><h2>Estad&iacute;sticas</h2></div>
        <div style="margin:5px 0 0 0;">
            <div class="clearfix">
               <div class="floatL"><strong id="stat-onl">{$tsStats.stats_online|kmg}</strong>  usuarios online</div>
               <div class="floatR"><strong id="stat-comu">{$tsStats.stats_comunidades|kmg}</strong>  comunidades</div>
            </div>
            <div class="clearfix">
                <div class="floatL"><strong id="stat-tem">{$tsStats.stats_temas|kmg}</strong>  temas</div>
                <div class="floatR"><strong id="stat-com">{$tsStats.stats_respuestas|kmg}</strong>  respuestas</div>
            </div>
            <div class="com_msj_blue" style="margin-top:5px">
              <a href="#">{$tsConfig.titulo}! en vivo</a>
            </div>
        </div>
    </div>
    <div class="com_new_box">
        <div class="com_box_title clearfix"><h2>Comentarios recientes</h2></div>
        <div class="com_box_body">
        	{if $tsRespuestas}
        	{foreach from=$tsRespuestas item=r}
            <div class="com_list_element" {if $r.t_estado == 1}style="opacity: 0.5;background: #000;" title="El tema ha sido eliminado"{/if}><a class="cle_autor" href="{$tsConfig.url}/perfil/{$r.user_name}">{$r.user_name}</a><a class="cle_title" href="{$tsConfig.url}/comunidades/{$r.c_nombre_corto}/{$r.t_id}/{$r.t_titulo|seo}.html#coment_id_{$r.r_id}">{$r.t_titulo|truncate:30}</a></div>
            {/foreach}
            {else}
            <div class="com_bigmsj_blue">No hay comentarios recientes</div>
            {/if}
        </div>
    </div>
    <div class="com_new_box">
        <div class="com_box_title clearfix">
            <h2>Populares</h2>
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
                	{if $tsPopulares.semana}
                    {foreach from=$tsPopulares.semana item=c key=i}
                    <div class="com_list_element" {if $c.c_estado == 1}style="opacity: 0.5;background: #000;" title="La comunidad ha sido eliminada"{/if}>
                        <span class="cle_item">{$i+1}</span>
                        <a href="{$tsConfig.url}/comunidades/{$c.c_nombre_corto}/">{$c.c_nombre|truncate:30}</a>
                        <span class="cle_number">{$c.c_miembros}</span>
                    </div>
                    {/foreach}
                    {else}
                    <div class="com_bigmsj_blue">Nada por aqu&iacute;</div>
                    {/if}
                </div>
                <div id="ccp_mes" style="display: none;">
                	{if $tsPopulares.mes}
                    {foreach from=$tsPopulares.mes item=c key=i}
                    <div class="com_list_element" {if $c.c_estado == 1}style="opacity: 0.5;background: #000;" title="La comunidad ha sido eliminada"{/if}>
                        <span class="cle_item">{$i+1}</span>
                        <a href="{$tsConfig.url}/comunidades/{$c.c_nombre_corto}/">{$c.c_nombre|truncate:30}</a>
                        <span class="cle_number">{$c.c_miembros}</span>
                    </div>
                    {/foreach}
                    {else}
                    <div class="com_bigmsj_blue">Nada por aqu&iacute;</div>
                    {/if}
                </div>                
                <div id="ccp_historico" style="display: none;">
                	{if $tsPopulares.historico}
                    {foreach from=$tsPopulares.historico item=c key=i}
                    <div class="com_list_element" {if $c.c_estado == 1}style="opacity: 0.5;background: #000;" title="La comunidad ha sido eliminada"{/if}>
                        <span class="cle_item">{$i+1}</span>
                        <a href="{$tsConfig.url}/comunidades/{$c.c_nombre_corto}/">{$c.c_nombre|truncate:30}</a>
                        <span class="cle_number">{$c.c_miembros}</span>
                    </div>
                    {/foreach}
                    {else}
                    <div class="com_bigmsj_blue">Nada por aqu&iacute;</div>
                    {/if}
                </div>
            </div>
        </div>
    </div>
    <div class="com_new_box">
        <div class="com_box_title clearfix"><h2>Comunidades recientes</h2></div>
        <div class="com_box_body clearfix">
        	{if $tsRecientes}
            {foreach from=$tsRecientes item=c}
            <div class="com_list_element"  {if $c.c_estado == 1}style="opacity: 0.5;background: #000;" title="La comunidad ha sido eliminada"{/if}><a href="{$tsConfig.url}/comunidades/{$c.c_nombre_corto}/">{$c.c_nombre}</a></div>
            {/foreach}
            {else}
            <div class="com_bigmsj_blue">No se han creado comunidades a&uacute;n</div>
            {/if}
        </div>
        <div align="center" style="margin-top: 20px;">
        	<a href="{$tsConfig.url}/comunidades/crear/" class="input_button">&iquest;Qu&eacute; esperas para crear la tuya?</a>
        </div>
    </div>
</div>
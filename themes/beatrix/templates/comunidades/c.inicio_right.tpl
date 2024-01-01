<div class="com_home_right">
	<div class="com_box_info">
        <div class="cbi_title">Comunidades</div>
        <div class="cbi_body">{$tsConfig.titulo} te permite crear tu comunidad para que puedas compartir gustos e intereses con los dem&aacute;s.</div>
        <div class="cbi_footer clearfix"><a class="input_button" href="{$tsConfig.url}/comunidades/crear/">¡Crea la tuya!</a></div>
    </div>
    
    {* Solo la primera comunidad de la semana *}
    
    {if $tsPopulares.semana}
    <div class="com_box_info" style="margin-top: 20px;">
        <div class="cbi_title">Comunidad destacada</div>
        <div class="cbi_body clearfix">
            {foreach from=$tsPopulares.semana item=c key=i}
            {if $i == 0}
            <div class="com_destacada">
            	<div class="cd_left floatL">
                <a href="{$tsConfig.url}/comunidades/{$c.c_nombre_corto}/" title="{$c.c_nombre}"><img src="{$tsConfig.url}/files/uploads/c_{$c.c_id}.jpg" alt="{$c.c_nombre}" width="120" height="120" /></a>                
            	</div>
                <div class="cd_right floatL">
                	<h2>{$c.c_nombre}</h2>
                	<ul>
                    	<li>Creada {$c.c_fecha|hace}</li>
                        <li><strong>Miembros: </strong>{$c.c_miembros}</li>
                        <li><strong>Temas: </strong>{$c.c_temas}</li>
                        <a class="input_button" href="{$tsConfig.url}/comunidades/{$c.c_nombre_corto}/">Ver comunidad</a>                      
                    </ul>
                </div>
            </div>
            {/if}
        	{/foreach}
    	</div>
    </div>
    {/if}
    
</div>
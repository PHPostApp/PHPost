<div class="ver_com_all">
    <div class="ver_com_temas clearfix">
        <h3 class="floatL">Temas</h3>
        {if $tsCom.es_miembro && $tsCom.mi_rango > 2}<a href="{$tsConfig.url}/comunidades/{$tsCom.c_nombre_corto}/agregar/" class="floatR input_button ibg">Nuevo tema</a>{/if}
    </div>
    <div id="result_temas">{include file='t.comus_ajax/c.pages_temas.tpl'}</div>    
</div>
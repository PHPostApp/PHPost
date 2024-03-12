<div class="ver_com_all">
    <div class="ver_com_temas clearfix">
        <h3 class="floatL">Temas</h3>
        {if $tsComunidades.es_miembro && $tsComunidades.mi_rango > 2}<a href="{$tsConfig.url}/comunidades/{$tsComunidades.c_nombre_corto}/agregar/" class="floatR input_button ibg">Nuevo tema</a>{/if}
    </div>
    <div id="result_temas">{include file='t.php_files/c.pages_temas.tpl'}</div>    
</div>
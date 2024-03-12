1: 
<div class="item" id="div_cmnt_{$tsComunidadesment.0}">
    <a href="{$tsConfig.url}/perfil/{$tsUser->nick}">
        <img src="{$tsConfig.url}/files/avatar/{$tsUser->info.user_id}_50.jpg" width="50" height="50" class="floatL" />
    </a>
    <div class="firma">
        <div class="options">
            {if $tsComunidadesment.3 == $tsUser->uid}
            <a href="#" onclick="fotos.borrar({$tsComunidadesment.0}, 'com'); return false" class="floatR" style="margin: 8px 5px">
			  <img title="Borrar Comentario" alt="borrar" src="{$tsConfig.images}/borrar.png"/>
            </a>
            {/if}
        </div>
        <div class="info"><a href="{$tsConfig.url}/fotos/{$tsUser->nick}/">{$tsUser->nick}</a> @ {$tsComunidadesment.2|date_format:"%d/%m/%Y"} dijo:</div>
        <div class="clearfix">{$tsComunidadesment.1|nl2br}</div>
    </div>
    <div class="clearBoth"></div>
</div>
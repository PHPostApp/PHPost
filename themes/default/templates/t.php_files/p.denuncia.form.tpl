{if $tsAction == 'denuncia-post'}
<div align="center" style="padding:10px 10px 0">
    <strong>Denunciar el post:</strong><br />
    {$tsData.obj_title}<br /><br />
    <strong>Creado por:</strong><br />
    <a href="{$tsConfig.url}/perfil/{$tsData.obj_user}" target="_blank">{$tsData.obj_user}</a><br /><br />
    <strong>Raz&oacute;n de la denuncia:</strong><br />
    <select name="razon">
    {foreach from=$tsDenuncias key=i item=denuncia}
    	{if $denuncia}<option value="{$i}">{$denuncia}</option>{/if}
    {/foreach}
    </select><br />
    <strong>Aclaraci&oacute;n y comentarios:</strong><br />
    <textarea tabindex="6" rows="5" cols="40" name="extras"></textarea><br />
    <span class="size9">En el caso de ser Re-post se debe indicar el link del post original.</span>
</div>
{elseif $tsAction == 'denuncia-foto'}
<div align="center" style="padding:10px 10px 0">
    <strong>Denunciar foto:</strong><br />
    {$tsData.obj_title}<br /><br />
    <strong>Raz&oacute;n de la denuncia:</strong><br />
    <select name="razon">
    {foreach from=$tsDenuncias key=i item=denuncia}
    	{if $denuncia}<option value="{$i}">{$denuncia}</option>{/if}
    {/foreach}
    </select><br />
    <strong>Aclaraci&oacute;n y comentarios:</strong><br />
    <textarea tabindex="6" rows="5" cols="40" name="extras"></textarea><br />
    <span class="size9">Para atender tu caso r&aacute;pidamente, adjunta pruevas de tu denuncia.<br /> (capturas de pantalla)</span>
</div>
{elseif $tsAction == 'denuncia-mensaje'}
<div class="emptyData">Si reportas este mensaje ser&aacute; eliminado de tu bandeja. <br />&iquest;Realmente quieres denunciar este mensaje como correo no deseado?</div>
<input type="hidden" name="razon" value="spam" />
{elseif $tsAction == 'denuncia-usuario'}
<div align="center" style="padding:10px 10px 0">
    <strong>Denunciar usuario:</strong><br />
    {$tsData.nick}<br /><br />
    <strong>Raz&oacute;n de la denuncia:</strong><br />
    <select name="razon">
    {foreach from=$tsDenuncias key=i item=denuncia}
    	{if $denuncia}<option value="{$i}">{$denuncia}</option>{/if}
    {/foreach}
    </select><br />
    <strong>Aclaraci&oacute;n y comentarios:</strong><br />
    <textarea tabindex="6" rows="5" cols="40" name="extras"></textarea><br />
    <span class="size9">Para atender tu caso r&aacute;pidamente, adjunta pruevas de tu denuncia.<br /> (capturas de pantalla)</span>
</div>
{/if}
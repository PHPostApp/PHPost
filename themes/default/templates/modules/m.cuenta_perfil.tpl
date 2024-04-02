<div class="content-tabs perfil">
	<input type="hidden" name="tab" value="{$tsTab}">
	<fieldset>
	<div class="field">
		<label for="nombrez">Nombre completo</label>
		<input type="text" value="{$tsPerfil.p_nombre}" maxlength="60" name="nombre" id="nombrez" class="text cuenta-save-2" style="width:230px">
	</div>
	<div class="field">
		<label for="sitio">Mensaje Personal</label>
		<textarea value="" maxlength="60" name="mensaje" id="mensaje" class="cuenta-save-2">{$tsPerfil.p_mensaje}</textarea>
	</div>
	<div class="field">
		<label for="sitio">Sitio Web</label>
		<input type="text" value="{$tsPerfil.p_sitio}" maxlength="60" name="sitio" id="sitio" class="text cuenta-save-2" style="width:230px">
	</div>
	<div class="field">
		<label for="ft">Redes sociales</label>
		<div style="display:grid;gap:.3rem;grid-template-columns: repeat(2, 1fr);">
         {foreach $tsRedes key=name item=red}
            <div class="red-item">
               <iconify-icon icon="{$red.iconify}"></iconify-icon>
	            <input type="text" class="text" value="{$tsPerfil.p_socials.$name}" placeholder="{$red.nombre}" name="red[{$name}]">
            </div>
         {/foreach}
       </div>
	</div>
	 <div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="mBtn btnOk">
	 </div>
</fieldset>
</div>
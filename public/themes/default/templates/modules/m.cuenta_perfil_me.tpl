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
	            <input type="text" value="{$tsPerfil.p_socials.$name}" placeholder="{$red.nombre}" name="red[{$name}]">
	            {if $name == 'discord'}
	            	<small onclick="explicacion();return false;" style="top: 6px;right: 5px;" class="position-absolute icono" title="Ajustes de usuario > Copiar ID"><iconify-icon icon="flat-color-icons:info"></iconify-icon></small>
	            {/if}
            </div>
         {/foreach}
       </div>
	</div>
	<div class="field">
		  <label>Me gustar&iacute;a</label>
		  <div class="input-fake">
				<ul>
					{foreach from=$tsPData.gustos key=val item=text}
					 <li><input type="checkbox" name="g_{$val}" class="cuenta-save-2" value="1" {if $tsPerfil.p_gustos.$val == 1}checked="checked"{/if}>{$text}</li>
					 {/foreach}
				</ul>
		  </div>
	 </div>
	 <div class="field">
		  <label for="estado">Estado Civil</label>
		  <div class="input-fake">
				<select class="cuenta-save-2" name="estado" id="estado">
					{foreach from=$tsPData.estado key=val item=text}
					 <option value="{$val}" {if $tsPerfil.p_estado == $val}selected="selected"{/if}>{$text}</option>
					 {/foreach}
				</select>
		  </div>
	 </div>
	 <div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="mBtn btnOk">
	 </div>
</fieldset>
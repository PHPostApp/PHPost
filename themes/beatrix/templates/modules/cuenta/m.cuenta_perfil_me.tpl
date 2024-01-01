<fieldset>
	<div class="form-item">
		<label class="form-label" for="nombrez">Nombre completo</label>
		<input type="text" value="{$tsPerfil.p_nombre}" placeholder="Nombre completo" maxlength="60" name="nombre" id="nombrez" class="form-control">
	</div>
	<div class="form-item">
		<label class="form-label" for="sitio">Mensaje Personal</label>
		<textarea maxlength="60" name="mensaje" id="mensaje" placeholder="Mensaje Personal" class="form-control">{$tsPerfil.p_mensaje}</textarea>
	</div>
	<div class="form-item">
		<label class="form-label" for="sitio">Sitio Web</label>
		<input type="text" value="{$tsPerfil.p_sitio}" maxlength="60" name="sitio" id="sitio" placeholder="https://tupaginaweb.com" class="form-control">
	</div>
	<div class="form-item">
		<label class="form-label" for="ft">Redes sociales</label>
		<div class="red-group">
         {foreach $tsRedes key=name item=red}
            <div class="red-item">
            	<div class="icon">
            		<iconify-icon icon="{$red.iconify}"></iconify-icon>
            	</div>
	            <input type="text" value="{$tsPerfil.p_socials.$name}" placeholder="{$red.nombre}" name="red[{$name}]">
	            {if $name == 'discord'}
	            	<small onclick="explicacion();return false;" style="top: 6px;right: 5px;" class="position-absolute icono" title="Ajustes de usuario > Copiar ID"><iconify-icon icon="flat-color-icons:info"></iconify-icon></small>
	            {/if}
            </div>
         {/foreach}
       </div>
	</div>
	<div class="form-item">
		<label class="form-label">Me gustar&iacute;a</label>
		<div class="input-fake">
			{foreach from=$tsPData.gustos key=val item=text}
				<div class="form-check">
				  	<input class="form-check-input" type="checkbox" name="g_{$val}" value="1" id="vg{$val}"{if $tsPerfil.p_gustos.$val == 1} checked{/if}>
				  	<label class="form-check-label" for="vg{$val}">{$text}</label>
				</div>
			{/foreach}
		</div>
	 </div>
	 <div class="form-item">
		  <label class="form-label" for="estado">Estado Civil</label>
		  <div class="input-fake">
				<select class="form-select" name="estado" id="estado">
					{foreach from=$tsPData.estado key=val item=text}
					 <option value="{$val}"{if $tsPerfil.p_estado == $val} selected{/if}>{$text}</option>
					 {/foreach}
				</select>
		  </div>
	 </div>
	 <div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="mBtn btnOk">
	 </div>
</fieldset>
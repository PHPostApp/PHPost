<div class="content-tabs cuenta">
	<fieldset>
		<div class="form-item">
			<label class="form-label" for="email">E-Mail:</label>
			<input type="text" value="{$tsUser->info.user_email}" maxlength="35" name="email" id="email" class="text cuenta-save-1 form-control" autocomplete="OFF">
		</div>
		<div class="form-item">
			<label class="form-label" for="pais">Pa&iacute;s:</label>
			<select onchange="cuenta.chgpais()" class="form-select cuenta-save-1" name="pais" id="pais">
				<option value="">Pa&iacute;s</option>
				{foreach from=$tsPaises key=code item=pais}
					<option value="{$code}"{if $code == $tsPerfil.user_pais} selected{/if}>{$pais}</option>
				{/foreach}
			</select>
		</div>
		<div class="form-item">
			<label class="form-label" for="estados">Estado/Provincia:</label>
			<select name="estado" id="estados" class="form-select cuenta-save-1">
				{foreach from=$tsEstados key=code item=estado}
					<option value="{$code+1}"{if $code+1 == $tsPerfil.user_estado} selected{/if}>{$estado}</option>
				{/foreach}
			</select>
		</div>
		<div class="form-item">
			<label class="form-label">Sexo</label>
			<div>
				<div class="form-check">
				  	<input class="form-check-input" type="radio" name="" value="m" id="sex0"{if $tsPerfil.user_sexo == '1'} checked{/if}>
				  	<label class="form-check-label" for="sex0">Hombre</label>
				</div>
				<div class="form-check">
				  	<input class="form-check-input" type="radio" name="sexo" value="f" id="sex1"{if $tsPerfil.user_sexo == '0'} checked{/if}>
				  	<label class="form-check-label" for="sex1">Mujer</label>
				</div>
			</div>
		</div>
		 <div class="form-item">
			<label for="nacimiento" class="form-label">Nacimiento:</label>
			<input name="nacimiento" class="form-control" type="date" id="nacimiento" min="{$tsMaxY}-12-31" max="{$tsEndY}-12-31" value="{$tsPerfil.nacimiento}" /> 
		</div>
		<div class="form-item">
			<label class="form-label" for="portada">Portada:</label>
			<input type="text" value="{$tsPerfil.user_portada}" name="portada" id="portada" class="text cuenta-save-1 form-control" autocomplete="OFF">
		</div>
		{if $tsConfig.c_allow_firma}
		 	<div class="form-item">
				<label class="form-label" for="firma">Firma:<br /> <small style="font-weight:normal">(Acepta BBCode) Max. 300 car.</small></label>
			  	<textarea name="firma" id="firma" class="form-control">{$tsPerfil.user_firma}</textarea>
		 	</div>
		{/if}
	 </fieldset>
	<div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="mBtn btnOk">
	</div>
	<div class="clearfix"></div>
</div>
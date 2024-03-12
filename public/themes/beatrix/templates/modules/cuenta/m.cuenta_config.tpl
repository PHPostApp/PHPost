<div class="content-tabs privacidad">
	<fieldset>
		<h2 class="active">&iquest;Qui&eacute;n puede...</h2>
		<div class="form-item">
			<label>ver tu muro?</label>
			<select name="muro" class="form-select">
				{foreach from=$tsPrivacidad item=p key=i}
					<option value="{$i}"{if $tsPerfil.p_configs.m == $i} selected{/if}>{$p}</option> 
				{/foreach}
			</select>
		</div>
		{$tsPerfil.p_configs.muro}
		<div class="form-item">
			<label>firmar tu muro?</label>
			<div class="input-fake">
				<select name="muro_firm" class="form-select">
					{foreach from=$tsPrivacidad item=p key=i} 
						{if $i != 6}
							<option value="{$i}"{if $tsPerfil.p_configs.mf == $i} selected{/if}>{$p}</option>
						{/if}
					{/foreach}
				</select>
			</div>
		</div>                  
		<div class="form-item">
			<label>ver &uacute;ltimas visitas?</label>
			<select name="last_hits" class="form-select">
				{foreach from=$tsPrivacidad item=p key=i}  
					{if $i != 1 && $i != 2}
						<option value="{$i}"{if $tsPerfil.p_configs.hits == $i} selected{/if}>{$p}</option>
					{/if}
				{/foreach}
			</select>
		</div>
		{if !$tsUser->is_admod}
			{if $tsPerfil.p_configs.rmp != 8}
				<div class="form-item">
					<label>enviarte MPs?</label>
					<select name="rec_mps" class="form-select">
						{foreach from=$tsPrivacidad item=p key=i}
							{if $i != 6}
								<option value="{$i}"{if $tsPerfil.p_configs.rmp == $i} selected{/if}>{$p}</option>
							{/if}
						{/foreach}
					</select>
				</div>
			{else}
				<div class="mensajes error">Algunas opciones de su privacidad han sido deshabilitadas, contacte con la administraci&oacute;n.</div>
			{/if}
		{/if}  
	</fieldset>
	{if !$tsUser->is_admod}
		<a onclick="$('#primi').slideUp(); $('#passi').slideDown(); $('#informa').slideDown(); $('#btninforma').slideDown();" class="btn btn-sm btn-danger" id="primi">Desactivar Cuenta</a>
		<p style="display:none;" id="informa" class="alert fst-italic text-warning"> Si desactiva su cuenta, todo el contenido relacionado a usted dejar&aacute; de ser visible durante un tiempo. Pasado ese tiempo, la administraci&oacute;n borrar&aacute; todo su contenido y no podr&aacute; recuperarlo.</p>
		<a onclick="desactivate()" style="display:none;" id="btninforma"><input type="button" value="Lo s&eacute;, pero quiero desactivarla" style="position:right;" class="btn btn-sm btn-danger"></a>
	{/if}
	<div class="buttons">
		<input type="button" value="Guardar" onclick="cuenta.guardar_datos()" class="mBtn btnOk">    
	</div> 
	<div class="clearfix"></div>
</div>
<div class="boxy-title">
	<h3>Opciones del registro</h3>
</div>
<div id="res" class="boxy-content">
	<form action="" method="post" autocomplete="off">
      <fieldset>
		  	<legend>Configuraci&oacute;n del Registro</legend>
		  	<dl>
            <dt><label for="pkey">reCaptcha p&uacute;blica</label><br /><span>Clave p&uacute;blica de <a targe="_blank" href="https://www.google.com/recaptcha/admin">reCatpcha</a>.</span></dt>
            <dd><input type="text" id="pkey" name="pkey" class="form-control" value="{$tsConfig.pkey}" /></dd>
         </dl>
         <dl>
            <dt><label for="skey">reCaptcha secreta</label><br /><span>Clave privada de <a targe="_blank" href="https://www.google.com/recaptcha/admin">reCatpcha</a>.</span></dt>
            <dd><input type="text" id="skey" name="skey" class="form-control" value="{$tsConfig.skey}" /></dd>
         </dl>
         <hr />
		  	<dl>
		  		<dt><label for="ai_edad">Edad requerida:</label><br /><span>A partir de que edad los usuarios pueden registrarse.</span></dt>
            <dd class="small">
               <div class="input-group">
                  <input class="form-control" type="text" id="ai_edad" name="c_allow_edad" maxlength="2" value="{$tsConfig.c_allow_edad}" />
                  <span class="input-group-text">a&ntilde;os</span>
               </div>
            </dd>
         </dl>
			<dl>
			 	<dt><label for="ai_met_welcome">Mensaje de Bienvenida:</label><br /><span id="desc_message_welcome" {if $tsConfig.c_met_welcome==0 }style="display:none;" {/if}> <br /> [usuario] => Nombre del registrado <br /> [welcome] => Bienvenido/a depende del sexo <br /> [web] => Nombre de esta web <br /> <br />(Se aceptan BBCodes y Smileys)</span></dt>
	         <dd>
					{html_options name='c_met_welcome' id='c_met_welcome' options=[0 => 'No dar bienvenida', 1 => 'Muro', 2 => 'Mensaje privado', 3 => 'Aviso'] selected=$tsConfig.c_met_welcome class="form-select select"}
	            <textarea name="c_met_welcome" id="ai_met_welcome" class="mt-3 form-control" style="height: 100px; {if $tsConfig.c_met_welcome == 0} display:none; {/if}">{$tsConfig.c_message_welcome}</textarea>
	         </dd>
	      </dl>
			<dl>
				<dt><label for="ai_reg_active">Registro abierto:</label><br /><span>Permitir el registro de nuevos usuarios</span></dt>
				<dd>
					{html_radios_custom name="c_reg_active" id="ai_reg_active" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_reg_active class="radio"}
				</dd>
			</dl>
			<dl>
				<dt><label for="ai_reg_activate">Activar usuarios:</label><br /><span>Activar autom&aacute;ticamente la cuenta de usuario.</span></dt>
				<dd>
					{html_radios_custom name="c_reg_activate" id="ai_reg_activate" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_reg_activate class="radio"}
				</dd>
		  </dl>
			<dl>
				<dt><label for="upperkey">Activar may&uacute;sculas:</label><br /><span>Activar al registrarse y/o loguearse! Al modificar ocasionar&aacute; error para los registrados.</span></dt>
				<dd>
					{html_radios_custom name="c_upperkey" id="upperkey" values=[1, 0] output=['Si', 'No'] selected=$tsConfig.c_upperkey class="radio"}
				</dd>
		  </dl>
		<dl>
		  <p><input type="submit" name="save" value="Guardar Cambios" class="btn btn-primary"/></p>
	 </fieldset>
	 </form>
</div>
<script>
	$("select.select").on('change', () => {
		let welcome = $("select.select").val();
		if(welcome > 0) {
			$('#ai_met_welcome').slideDown();
			$('#desc_message_welcome').slideDown();
		}
	})
</script>
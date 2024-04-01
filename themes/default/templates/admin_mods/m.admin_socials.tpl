<div class="boxy-title">
   <h3>Configurar redes sociales</h3>
</div>
<div id="res" class="boxy-content">
   {if $tsAct == ''}

      <table class="table table-striped table-hover">
         <caption><a href="{$tsConfig.url}/admin/socials?act=nueva" class="btn btn-success">Agregar Nueva Red social</a></caption>
         <thead>
            <tr>
               <th scope="col">Red</th>
               <th scope="col">Client ID</th>
               <th scope="col">Client Secret</th>
               <th scope="col">Redirect URL</th>
               <th scope="col">Acciones</th>
            </tr>
         </thead>
         <tbody>
            {foreach from=$tsSocials item=social}
            <tr id="{$social.social_id}">
               <th style="width: 100px;" class="text-center" scope="row"><iconify-icon icon="logos:{$social.social_name}"></iconify-icon></th>
               <td>{$social.social_client_id|truncate:25}</td>
               <td>{$social.social_client_secret|truncate:25}</td>
               <td>{$social.social_redirect_uri|replace:"{$tsConfig.url}":''}</td>
               <td class="align-bottom">
                  <div class="admin_actions">
                     <a href="{$tsConfig.url}/admin/socials?act=editar&id={$social.social_id}"><img src="{$tsConfig.public}/images/icons/editar.svg" title="Editar red social"/></a>
                     <a href="{$tsConfig.url}/admin/socials?act=borrar&id={$social.social_id}"><img src="{$tsConfig.public}/images/icons/close.svg" title="Borrar red social"/></a>
                  </div>
               </td>
            </tr>
            {/foreach}
         </tbody>
      </table>   	
			
   {elseif $tsAct === 'nueva' || $tsAct === 'editar'}
	   <form action="" method="post" autocomplete="off">
	      <fieldset>
	         <legend>{$tsAct|ucfirst} red social</legend>
	         {if $tsAct === 'editar'}
	         	<input type="hidden" name="social_id" value="{$social.social_id}">
	         {else}
	         	<dl>
            		<dt><label for="social_name">Sitio:</label></dt>
            		<dd>
            			{html_options name='social_name' id='social_name' options=$tsNetsSocials selected=$tsSocial.social_name class="form-select"}
            		</dd>
         		</dl>
	         {/if}
	         <dl>
            	<dt><label for="clientid">Client-ID:</label></dt>
            	<dd><input class="form-control" type="text" id="clientid" name="social_client_id" value="{$tsSocial.social_client_id}" /></dd>
         	</dl>
	         <dl>
            	<dt><label for="clientsecret">Client-Secret:</label></dt>
            	<dd><input class="form-control" type="text" id="clientsecret" name="social_client_secret" value="{$tsSocial.social_client_secret}" /></dd>
         	</dl>
	         <dl class="position-relative">
            	<dt><label for="redirecturi">Redirect URL:</label></dt>
            	<dd><input class="form-control" type="text" id="redirecturi" value="{$tsSocial.social_redirect_uri}" />
                  <small class="position-absolute" style="right:4rem;top:1rem"></small>
                  <span style="top:.4rem;right:1rem;cursor:pointer;" class="position-absolute" id="botonCopiar"><img src="{$tsConfig.public}/images/icons/copy.svg" alt="Copiar"></span></dd>
         	</dl>
	         <p><input type="submit" name="save" value="Guardar Cambios" class="btn btn-primary" /></p>
	      </fieldset>
	   </form>
	   <script>
         $(document).ready(() => {
   	   	if(empty($('#redirecturi').val())) $('#redirecturi').val(global_data.url + '/discord.php')
   	   	$('#social_name').on('change', () => {
   	   		var replace = global_data.url + '/' + $('#social_name option:selected').val() + '.php';
   	   		$('#redirecturi').val(replace)
   	   	});
            $("#botonCopiar").on("click", () => {
               let input = $("#redirecturi");
               input.select();
               document.execCommand("copy");
               window.getSelection().removeAllRanges();
               input.parent().find('small').html("Redirect URL ha sido copiado correctamente!");
               setTimeout(() => input.parent().find('small').html(''), 5000);
          });
         })
	   </script>
	  {/if}
</div>
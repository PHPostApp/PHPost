<div class="boxy-title">
   <h3>Configuraciones extras del Sitio</h3>
</div>
<div id="res" class="boxy-content">
   {if $tsSave}<div class="alert alert-success">Configuraciones guardadas</div>{/if}
   <form action="" method="post" autocomplete="off">
      <fieldset>			
			{if $optimizer}
         <dl>
            <dt><label for="ai_optimizar">Optimizador:</label><br /><span>Optimiza las imagenes de portada de los posts.</span></dt>
            <dd>
               {html_radios_custom name="optimizar" id="ai_optimizar" values=[1, 0] output=['Si', 'No'] selected=$tsExtra.optimizar class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_extension">Tipo de extensi&oacute;n:</label><br /><span>Extensi&oacute;n para las imagenes optimizadas.</span></dt>
            <dd>
               {html_options name='extension' id='extension' options=[0 => 'png', 1 => 'jpg', 2 => 'jpeg', 3 => 'webp'] selected=$tsExtra.extension class="form-select select"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_tamano">Tamaño de la imagen:</label><br /><span>Tamaño por defecto para la imagen.</span></dt>
            <dd class="small">
               <div class="input-group">
                  <input class="form-control" type="text" id="ai_tamano" name="tamano" maxlength="4" value="{$tsExtra.tamano}" />
                  <span class="input-group-text">px</span>
               </div>
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_calidad">Calidad de la imagen:</label><br /><span>La calidad va desde 0 al 100.</span></dt>
            <dd class="small">
               <div class="input-group">
                  <input class="form-control" type="text" id="ai_calidad" name="calidad" maxlength="3" value="{$tsExtra.calidad}" />
                  <span class="input-group-text">%</span>
               </div>
            </dd>
         </dl>
         {/if}
         <h3>Smarty</h3>
         <dl>
            <dt><label for="ai_smarty_security">Seguridad:</label><br /><span>La seguridad es buena para situaciones en las que partes que no son de confianza.</span></dt>
            <dd>
               {html_radios_custom name="smarty_security" id="ai_smarty_security" values=[1, 0] output=['Si', 'No'] selected=$tsExtra.smarty_security class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_smarty_compress">Comprimir HTML:</label><br /><span>Esto har&aacute; que el html este en una l&iacute;nea para m&aacute;s velocidad.</span></dt>
            <dd>
               {html_radios_custom name="smarty_compress" id="ai_smarty_compress" values=[1, 0] output=['Si', 'No'] selected=$tsExtra.smarty_compress class="radio"}
            </dd>
         </dl>
         <dl>
            <dt><label for="ai_smarty_lifetime">Tiempo Cacheado:</label><br /><span>El tiempo que tardará en regenerar los archivos cacheados.</span></dt>
            <dd class="small">
               <div class="input-group">
                  <input class="form-control" type="text" id="ai_smarty_lifetime" name="smarty_lifetime" maxlength="3" value="{$tsExtra.smarty_lifetime}" />
                  <span class="input-group-text">hs</span>
               </div>
            </dd>
         </dl>
         <p><input type="submit" name="save" value="Guardar Cambios" class="btn btn-primary" /></p>
      </fieldset>
   </form>
</div>
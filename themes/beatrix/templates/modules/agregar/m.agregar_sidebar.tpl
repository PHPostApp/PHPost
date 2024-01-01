<div class="mb-3">
   <label class="form-label d-block" for="portada">Imagen (Max. 2 MB)</label>

   {html_radios_custom name="myportada" label_ids=true values=['url', 'pc'] id="typeimage" output=['Desde URL', 'Desde PC'] selected='url' class="mb-3"}
   <!-- https://png.pngtree.com/background/20230612/original/pngtree-wolf-animals-images-wallpaper-for-pc-384x480-picture-image_3180467.jpg -->
   <input type="text" tabindex="4" name="url" id="url" class="form-control typeselect-url mb-2" placeholder="Desde URL" value="{$tsDraft.b_portada}"/>
   <div class="load-image typeselect-pc rounded-2" style="display: none;">
      <input type="hidden" name="key" value="">
      <input type="hidden" name="ext" value="">
      <input type="file" tabindex="4" name="portada" id="portada" value="{$tsDraft.b_image}" />
      <span id="fichero">Seleccionar archivo...</span>
   </div>
   <div class="feedback"></div>
   <div class="visor-portada position-relative rounded overflow-hidden shadow">
      <div class="spinner-border text-light" role="status" style="display: none;">
         <span class="visually-hidden">Loading...</span>
      </div>
      <img src="{$tsConfig.public}/images/portada.png" class="image" alt="portada para post">
   </div>
</div>

<div class="mb-3">
   <label class="form-label" for="category">Categor&iacute;a</label>
   <select class="form-select agregar required" tabindex="5" id="category" name="category">
      <option value="">Elegir una categor&iacute;a</option>
      {foreach from=$tsConfig.categorias item=c}
         <option value="{$c.cid}"{if $tsDraft.b_category == $c.cid} selected{/if}>{$c.c_nombre}</option>
      {/foreach}
   </select>
   <div class="feedback"></div>
</div>

<div class="mb-3">
   <span>Opciones</span>
   <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" role="switch" tabindex="6" name="private" id="privado"{if $tsDraft.b_private == 1} checked{/if}>
      <label class="form-check-label" for="privado">S&oacute;lo usuarios registrados
      <small class="d-block text-secondary fst-italic">Tu post ser&aacute; visto s&oacute;lo por los usuarios que tengan cuenta!</small></label>
   </div>
   <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" role="switch" tabindex="7" name="block_comments" id="sin_comentarios"{if $tsDraft.b_block_comments == 1} checked{/if}>
      <label class="form-check-label" for="sin_comentarios">Cerrar Comentarios
      <small class="d-block text-secondary fst-italic">Si tu post es pol&eacute;mico ser&iacute;a mejor que cierres los comentarios.</small></label>
   </div>
   <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" role="switch" tabindex="8" name="visitantes" id="visitantes"{if $tsDraft.b_visitantes == 1} checked{/if}>
      <label class="form-check-label" for="visitantes">Mostrar visitantes recientes
      <small class="d-block text-secondary fst-italic">Tu post mostrar&aacute; los &uacute;ltimos visitantes que ha tenido.</small></label>
   </div>
   <div class="form-check form-switch mb-3">
      <input class="form-check-input" type="checkbox" role="switch" tabindex="9" name="smileys" id="smileys"{if $tsDraft.b_smileys == 1} checked{/if}>
      <label class="form-check-label" for="smileys">Sin Smileys
      <small class="d-block text-secondary fst-italic">Si tu post no necesita smileys, desact&iacute;valos.</small></label>
   </div>
   {if $tsUser->is_admod == 1}
      <div class="form-check form-switch mb-3">
         <input class="form-check-input" type="checkbox" role="switch" tabindex="10" name="sponsored" id="patrocinado"{if $tsDraft.b_sponsored == 1} checked{/if}>
         <label class="form-check-label" for="patrocinado">Patrocinado
         <small class="d-block text-secondary fst-italic">Resalta este post entre los dem&aacute;s.</small></label>
      </div>
   {/if}
   {if $tsUser->is_admod || $tsUser->permisos.most}
      <div class="form-check form-switch mb-3">
         <input class="form-check-input" type="checkbox" role="switch" tabindex="11" name="sticky" id="sticky"{if $tsDraft.b_sticky == 1} checked{/if}>
         <label class="form-check-label" for="sticky">Sticky
         <small class="d-block text-secondary fst-italic">Colocar a este post fijo en la home.</small></label>
      </div>
   {/if}
</div>

{*<div class="sidebar-add-post">
   <div id="protocolo">
      <div class="clearfix">
         <p class="floatL">
            <span class="stitle">Consejos: Para hacer un buen post es importante que tengas en cuenta algunos puntos.</span>
         </p>
         <a class="consejos-view-more-button">Ver m&aacute;s...</a>
      </div>
      <div style="display: none;" class="consejos-view-more clearfix">
         <p style="margin-top: 5px;">Esto ayuda a mantener una mejor calidad de contenido y evitar que sea eliminado por los moderadores.</p>
         <div class="clearfix">
            <div class="floatL clearfix">
            	<strong>El Titulo</strong>
            	<ul>
            		<li class="correct">Que sea descriptivo</li>
            		<li class="">TODO EN MAYUSCULA</li>
            		<li class="">!!!!!!!Exagerados!!!!!!</li>
            		<li class="">PARCIALMENTE en may&uacute;sculas!</li>
            	</ul>
            </div>
            <div class="floatL clearfix">
            	<strong class="floatL">Contenido</strong>
            	<br/>
            	<ul style="margin-right: 10px;" class="floatL">
            		<li class="">Informaci&oacute;n personal o de un tercero</li>
            		<li class="">Fotos de personas menores de edad</li>
            		<li class="">Muertos, sangre, v&oacute;mitos, etc.</li>
            		<li class="">Con contenido racista y/o peyorativo</li>
            	</ul>
            	<ul class="floatL">
            		<li class="">Poca calidad (una imagen, texto pobre)</li>
            		<li class="">Chistes escritos, adivinanzas, trivias</li>
            		<li class="">Haciendo preguntas o cr&iacute;ticas</li>
            		<li class="">Insultos o malos modos</li>
            	</ul>
            	<ul class="floatL">
            		<li class="">Con intenci&oacute;n de armar pol&eacute;mica</li>
            		<li class="">Apolog&iacute;a de delito</li>
            		<li class="">Software spyware, malware, virus o troyanos</li>
            	</ul>
            </div>
         </div>
      </div>
   </div>
</div>*}
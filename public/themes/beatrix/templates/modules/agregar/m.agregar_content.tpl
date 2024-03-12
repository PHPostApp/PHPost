<div class="mb-3"> <!-- has-validation -->
	<label class="form-label" for="titulo">T&iacute;tulo</label>
	<input type="text" tabindex="1" id="titulo" name="title" maxlength="60" size="60" class="form-control" placeholder="{$tsTitle}" value="{$tsDraft.b_title}" required />
	<div class="feedback"></div>
	<div id="repost"></div>
</div>
{if ($tsUser->is_admod > 0 || $tsUser->permisos.moedpo) && $tsDraft.b_title && $tsDraft.b_user != $tsUser->uid}
	<div class="mb-3"> <!-- has-validation -->
	 	<label class="form-label" for="razon">Raz&oacute;n</label>
	 	<input type="text" tabindex="8" name="razon" id="razon" placeholder="La raz&oacute;n por la cual modificaste el post." maxlength="150" size="60" class="form-control" value=""/>
		<div class="feedback"></div>
	</div>
{/if}
<div class="mb-3"> <!-- has-validation -->
	<a name="post"></a>
	<label class="form-label" for="cuerpo">Contenido del Post</label>
	<textarea id="cuerpo" name="body" tabindex="2" class="form-control" required>{$tsDraft.b_body}</textarea>
	<div class="feedback"></div>
</div>
<div class="mb-3">
   <label class="form-label" for="tags">Tags</label>
   <input type="text" tabindex="5" name="tags" id="tags" maxlength="128" class="form-control" placeholder="Una lista separada por comas." value="{$tsDraft.b_tags}" required />
   <div class="feedback"></div>
</div>
<div class="end-form clearbeta">
   <span class="btn btn-warning" id="borrador-save" onclick="guardar()">Guardar en borradores</span>
   <span class="btn btn-success" onclick="preliminar()">Previsualizar</span>
   <span class="btn btn-primary" onclick="publicar()">Publicar</span>
   <div id="borrador-guardado" style="float: right; font-style: italic; margin: 7px 0pt 0pt;"></div>
</div>
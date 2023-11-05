<div class="boxy-title">
   <h3>Configuración SEO</h3>
</div>
<div id="res" class="boxy-content">
   {if $tsSave}<div class="alert alert-success">Configuraciones guardadas</div>{/if}
   <form action="" method="post" autocomplete="off">
      <fieldset>
         <dl>
            <dt><label for="titulo">Titulo:</label><small class="d-block">Debe contener entre 50 a 60 caracteres!</small></dt>
            <dd><input class="form-control" type="text" id="titulo" name="titulo" minlength="30" maxlength="60" value="{$tsSeo.seo_titulo}" /></dd>
         </dl>
         <dl>
            <dt><label for="descripcion">Decripción:</label><small class="d-block">Debe contener entre 150 a 160 caracteres!</small></dt>
            <dd><textarea name="descripcion" id="descripcion" class="form-control">{$tsSeo.seo_descripcion}</textarea></dd>
         </dl>
         <dl>
            <dt><label for="keywords">Palabras claves:</label></dt>
            <dd><input class="form-control" type="text" id="keywords" name="keywords" value="{$tsSeo.seo_keywords}" /></dd>
         </dl>
         <dl>
            <dt><label for="robots">Activar rasteadores:</label><small class="d-block">Activar los rastreadores de los motores de búsqueda si pueden o no indexar una página.</small></dt>
            <dd>
               {html_radios_custom name="robots" values=[1, 0] id="robots" output=['Si', 'No'] selected=$tsSeo.seo_robots}
            </dd>
         </dl>
         <dl>
            <dt><label for="robots_name">Tipos de rasteadores:</label><small class="d-block">indica a los buscadores que no muestren esa página en los resultados de búsqueda.</small></dt>
            <dd class="row row-cols-2">
					<div class="mb-3">
						<div class="input-group">
							<label class="input-group-text" for="robots_name">Name</label>
							{html_options name='robots_data[name]' id='robots_name' options=[0 => 'robots', 1 => 'googlebot', 2 => 'googlebot-news'] selected=$tsSeo.robots_name class="form-select"}
						</div>
					</div>
					<div class="mb-3">
						<div class="input-group">
							<label class="input-group-text" for="robots_name">Content</label>
							{html_options name='robots_data[content]' id='robots_content' options=[0 => 'index', 1 => 'follow', 2 => 'noindex', 3 => 'nofollow', 4 => 'nosnippet', 5 => 'index, follow', 6 => 'index, nofollow', 7 => 'noindex, follow', 8 => 'noindex, nofollow'] selected=$tsSeo.robots_content class="form-select"}
						</div>
					</div>
				</dd>
         </dl>
         <dl>
            <dt><label for="portada">Portada:</label></dt>
            <dd class="input-group">
               <input class="form-control" type="text" id="portada" name="portada" value="{$tsSeo.seo_portada}" />
            </dd>
         </dl>
         <dl>
            <dt><label for="favicon">Icono del sitio:</label></dt>
            <dd class="input-group">
 		 			<input class="form-control" type="text" id="favicon" name="favicon" value="{$tsSeo.seo_favicon}" />
				</dd>
         </dl>
         <dl>
            <dt><label for="images">Otros iconos:</label><small class="d-block">16x16, 32x32, 64x64, etc</small></dt>
            <dd>
            	{foreach $tsSeo.seo_images_total key=i item=px}
               	<div class="input-group mb-3">
                     <span class="input-group-text" id="pixeles">{$px}x{$px}</span>
               		<input class="form-control" type="text" id="images" name="images[{$px}]" value="{$tsSeo.seo_images.$px}" />
               		<button type="button" class="btn btn-primary" onclick="$(this).parent().remove()">Quitar</button>
               	</div>
            	{/foreach}
            </dd>
         </dl>
         <div class="search-results">
	        	<div class="result">
               <img class="rounded image mb-3" src="{$tsSeo.seo_portada}" alt="{$tsSeo.seo_titulo}">
	            <span class="title text-primary">{$tsSeo.seo_titulo}</span><br>
	            <span class="url fst-italic">{$tsConfig.url}</span><br>
	            <p class="description m-0 p-0">{$tsSeo.seo_descripcion}</p>
	        	</div>
	      </div>
         <style>
            .search-results {
               width: 600px;
               margin: 1rem auto;
               padding: 1rem;
               border: var(--bs-border-width) solid var(--bs-content-border-color);
               border-radius: 5px;
               background-color: var(--bs-content-bg);
            }
           .title { font-size: 18px; font-weight: bold; }
           .url { color: #006621; }
           .description { color: #545454; }
           .image { width: 100%;object-fit: cover; }
         </style>
         <script>
            $('#titulo').on('keyup', () => $('.result .title').html($('#titulo').val()))
            $('#descripcion').on('keyup', () => $('.result .description').html($('#descripcion').val()))
            $('#image').on('keyup', () => $('.result .image').attr({ src: $('#image').val() }))
         </script>
         <p><input type="submit" name="save" value="Guardar Cambios" class="btn btn-primary" /></p>
      </fieldset>
   </form>
</div>
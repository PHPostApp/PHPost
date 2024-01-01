<ul class="list-unstyled">
	{assign var="imageCount" value=$tsGeneral.fotos|count}
	{foreach from=$tsGeneral.fotos item=f key=i}
		{if $f.foto_id}
		<li>
			<div class="foto">
				<a href="{$tsConfig.url}/fotos/{$tsInfo.nick}/{$f.foto_id}/{$f.f_title|seo}.html" title="{$f.f_title}"><img class="image" src="{$tsConfig.images}/mantenimiento.gif" data-src="{$f.f_url}"/></a>
			</div>
		</li>
		{/if}
	{/foreach}
	{section name=emptyImage loop=9-$imageCount}
		<li class="bg-secondary">
		  <div class="foto">
				<!-- Este contenedor no tiene una imagen y se llenará con el fondo de color #CCC -->
		  </div>
		</li>
	 {/section}
</ul>
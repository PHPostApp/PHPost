<div class="fotos_home_content">
	<h2>&Uacute;ltimas fotos</h2>
	<div class="row">
		{foreach from=$tsLastFotos.data item=f}
			<div class="col-lg-6"{if $f.f_status != 0 || $f.user_activo == 0 || $f.user_baneado == 1} title="{if $f.f_status == 2}Imagen eliminada{elseif $f.f_status == 1}Imagen oculta por acumulaci&oacute;n de denuncias{elseif $f.user_activo == 0}La cuenta del usuario est&aacute; desactivada{elseif $f.user_baneado == 1}La cuenta del usuario est&aacute; suspendida{/if}" style="border: 1px solid {if $f.f_status == 2}rosyBrown{elseif $f.f_status == 1}coral{elseif $f.user_activo == 0}brown{elseif $f.user_baneado == 1}orange{/if};opacity: 0.5;filter: alpha(opacity=50);"{/if}>
				<article class="post-block-style-wrapper post-block-template-one post-block-template-medium mb-24">
					<div class="post-block-style-inner">
						<div class="post-block-media-wrap">
							<a href="post-single.html">
								<img src="{$tsConfig.images}/loadImage.gif" data-src="{$f.f_url}" class="image rounded" loading="lazy" alt="{$f.f_title}">
							</a>
						</div>
						<div class="post-block-content-wrap">
							<div class="post-item-title">
								<h2 class="post-title"><a href="{$tsConfig.url}/fotos/{$f.user_name}/{$f.foto_id}/{$f.f_title|seo}.html">{$f.f_title}</a></h2>
							</div>
							<div class="post-excerpt-box text-truncate-2"><p>{$f.f_description}</p></div>
	                  <div class="post-bottom-meta-list">
	                     <div class="post-meta-author-box">
	                     	Por <a href="{$tsConfig.url}/perfil/{$f.user_name}">{$f.user_name}</a>
	                   	</div>
	                   	<div class="post-meta-date-box">{$f.f_date|date_format:"%b %d, %Y"}</div>
	                  </div>
	               </div>
	            </div>
	         </article>
	      </div>
		{/foreach} 
   </div>
	<div class="row">
	   <div class="col-lg-12">
	      <div class="blog-pagination-area mt-20">
	      	{$tsPages.item}
	      </div>
	   </div>
	</div>
   {if $tsLastFotos.data > 10}P&aacute;ginas: {$tsLastFotos.pages}{/if}
</div>
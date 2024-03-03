{if $TypePost === 'normal'}<div class="col-lg-6">{/if}
   <article class="post-block-style-wrapper post-block-template{if $TypePost === 'normal'}-one post-block-template-medium mb-24{else}-two most-read-block-list{/if}">
      {if $p.post_sponsored == 1}
         <div class="ribbon down" style="--color: #fd9c2e;">
            <div class="content">
               <iconify-icon icon="heroicons:star-solid"></iconify-icon>
            </div>
         </div>
      {/if}
      {if $p.post_private == 1}
         <div class="ribbon up" style="--color: #BE0945;{if $p.post_sponsored == 1}--right: 3.8rem{/if}">
            <div class="content">
               <iconify-icon icon="dashicons:lock-duplicate"></iconify-icon>
            </div>
         </div>
      {/if}
   	<div class="post-block-style-inner{if $TypePost === 'fijos'} post-block-list-style-inner{/if}">
         <div class="post-block-media-wrap">
            <a href="{$p.post_url}">
               {image type="post" src="{$p.post_portada}" class="rounded" alt="{$p.post_title}"}
            </a>
         </div>
         <div class="post-block-content-wrap">
            <div class="post-category{if $TypePost === 'fijos'}-box{/if}">
               <a class="post-cat{if $TypePost === 'fijos'}-item{/if}" href="{$tsConfig.url}/posts/{$p.c_seo}/">{$p.c_nombre}</a>
            </div>
            <div class="post-item-title">
               <h2 class="post-title">
                  <a class="text-truncate-2" href="{$p.post_url}">{$p.post_title}</a>
               </h2>
            </div>
            {if $TypePost === 'normal'}
	            <div class="post-excerpt-box text-truncate-2">
	            	<p>{$p.post_descripcion|strip_tags}</p>
	            </div>
	         {/if}
            <div class="post-bottom-meta-list">
            	<div class="post-meta-author-box">
            		{if $TypePost === 'normal'}Por {/if}<a href="{$tsConfig.url}/perfil/{$p.user_name}">{$p.user_name}</a>
            	</div>
            	<div class="post-meta-date-box">{$p.post_fecha} {if $p.post_private}| Privado{/if}</div>
            </div>
         </div>
      </div>
   </article>
{if $TypePost === 'normal'}</div>{/if}
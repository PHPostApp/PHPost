<article class="post-block-style-wrapper post-block-template-two most-read-block-list">
	<div class="post-block-style-inner post-block-list-style-inner">
		<div class="post-block-media-wrap{if $userpost === 'outname'} user{/if}">
         <a href="{$tsConfig.url}/posts/{$p.c_seo}/{$p.post_id}/{$p.post_title|seo}.html" rel="dc:relation">
         	{image type="post" src="{$p.post_portada}" class="rounded" alt="{$p.post_title}"}
         </a>
      </div>
      <div class="post-block-content-wrap">
         <div class="post-item-title">
           	<h2 class="post-title">
               <a href="{$tsConfig.url}/posts/{$p.c_seo}/{$p.post_id}/{$p.post_title|seo}.html" rel="dc:relation">{$p.post_title}</a>
            </h2>
         </div>
         <div class="post-bottom-meta-list">
            {if $userpost === 'inname'}<div class="post-meta-author-box"><a href="{$tsConfig.url}/perfil/{$p.user_name}">{$p.user_name}</a></div>{/if}
            <div class="post-meta-date-box">{$p.post_date|date_format:'M. d'}{if $p.post_private} | privado{/if}</div>
         </div>
      </div>
   </div>
</article>
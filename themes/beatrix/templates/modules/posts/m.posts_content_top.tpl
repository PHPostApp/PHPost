<div class="post-cat-box">
	<a title="{$tsPost.categoria.c_nombre}" href="{$tsConfig.url}/posts/{$tsPost.categoria.c_seo}/">{$tsPost.categoria.c_nombre}</a>
</div>
<h1 class="post-title">{$tsPost.post_title}</h1>
<div class="blog-small-excerpt-box d-flex justify-content-start align-items-center">
   <div class="d-flex justify-content-start align-items-center me-4" style="column-gap:.4rem;"><iconify-icon icon="la:eye"></iconify-icon> {$tsPost.post_hits} Visitas</div>
   <div class="d-flex justify-content-start align-items-center me-4" style="column-gap:.4rem;"><iconify-icon icon="la:coins"></iconify-icon> {$tsPost.post_puntos} Puntos</div>
   <div class="d-flex justify-content-start align-items-center" style="column-gap:.4rem;"><iconify-icon icon="la:user-friends"></iconify-icon> {$tsPost.post_seguidores} Seguidores</div>
</div>
<div class="post-bottom-meta-list post-meta-wrapper">
   <div class="post-left-details-meta">
      <div class="post-meta-author-box">
      	Por <a title="{$tsAutor.user_name}" href="{$tsConfig.url}/perfil/{$tsAutor.user_name}">{$tsAutor.user_name}</a>
      </div>
      <div class="post-meta-date-box">{$tsPost.post_date|date_format:'d M, Y'}</div>
   </div>
   <div class="post-meta-social d-flex justify-content-end align-items-center">
      <a role="button" id="web" data-title="{$tsPost.post_title|seo}" title="Compartir post con tus amigos" href="#"><iconify-icon icon="la:link"></iconify-icon></a>
      <a role="button" id="facebook" data-title="{$tsPost.post_title|seo}" title="Compartir post en facebook" href="#"><iconify-icon icon="la:facebook-f"></iconify-icon></a>
      <a role="button" id="twitter" data-title="{$tsPost.post_title|seo}" title="Compartir post en twitter" href="#"><iconify-icon icon="pajamas:twitter"></iconify-icon></a>
      <a role="button" id="telegram" data-title="{$tsPost.post_title|seo}" title="Compartir post en telegram" href="#"><iconify-icon icon="la:telegram"></iconify-icon></a>
   </div>
</div>
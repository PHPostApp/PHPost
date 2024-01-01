{include "main_header.tpl"}
<a name="cielo"></a>
<div class="post-single-area mt-60">
   <div class="container">
   	{if $tsPost.post_status > 0 || $tsAutor.user_activo != 1}
			<div class="alert alert-danger text-center">Este post se encuentra {if $tsPost.post_status == 2}eliminado{elseif $tsPost.post_status == 1}inactivo por acomulaci&oacute;n de denuncias{elseif $tsPost.post_status == 3}en revisi&oacute;n{elseif $tsPost.post_status == 3}en revisi&oacute;n{elseif $tsAutor.user_activo != 1}oculto porque pertenece a una cuenta desactivada{/if}, t&uacute; puedes verlo porque <strong>{if $tsUser->is_admod == 1}eres Administrador{elseif $tsUser->is_admod == 2} Moderador{else}tienes permiso{/if}</strong>.</div><br />
		{/if}
		<div class="row">
         <div class="col-lg-8 single-blog-content">
            <div class="post-single-wrapper">
               {include "m.posts_content_top.tpl"}
               {if $tsPost.post_portada != ''}
               <div class="post-featured-image">
                  {image type="portada" src="{$tsPost.post_portada}" class="rounded" alt="{$tsPost.post_title}"}
               </div>
               {/if}
            </div>
            <div class="theme-blog-details">
               <span>{$tsPost.post_body}</span>
               <div class="author-bio-wrap">
                  <div class="author-thumbnail">
                     <a href="#" title="Perfil de {$tsAutor.user_name}">
                     	{image src="{$tsConfig.avatar}/{$tsAutor.user_id}_120.jpg" class="rounded-circle" style="border:2px solid #{$tsAutor.rango.r_color};" alt="{$tsAutor.user_name}"}
                     </a>
                  </div>
                  <div class="author-body">
                     <span class="subtitle">{$tsAutor.rango.r_name}</span>
                     <h5 class="title">{$tsAutor.user_name}</h5>
                     <p class="author-inner-text">My favorite compliment is being told that I look like my mom. Seeing myself in her image, like this daughter up top, makes me so proud of how far I’ve come.</p>
                     <div class="social-share-author">
                        <a href="#"><iconify-icon icon="la:facebook-f"></iconify-icon></a>
                        <a href="#"><iconify-icon icon="la:instagram"></iconify-icon></a>
                        <a href="#"><iconify-icon icon="pajamas:twitter"></iconify-icon></a>
                        <a href="#"><iconify-icon icon="la:linkedin-in"></iconify-icon></a>
                     </div>
                  </div>
               </div>
               <!-- INICIO COMENTAR -->
               <div class="theme-comment-area">
                  <div class="comment-respond">
                     <h4 class="title">Post a comment</h4>
                  </div>
               </div>
               <!-- FIN COMENTAR -->
            </div>
         </div>
         <div class="col-lg-4">
            {include "m.posts_usuario_relacionado.tpl"}             
                        </div>
                    </div>
	</div>
</div>

{phpost css="AtomOneDark.css" js="highlight.min.js" from='beforeFooter'}
<script>
   const colores = [];
   //hljs.highlightAll();
   document.querySelectorAll('pre code').forEach((el) => {
      hljs.highlightElement(el);
   });
</script>
{*<div class="post-wrapper">
	{include "m.posts_autor.tpl"}
	{include "m.posts_content.tpl"}
	<div class="floatR" style="width: 766px;">
		{include "m.posts_related.tpl"}
		{include "m.posts_banner.tpl"}
		<div class="clearfix"></div>
	</div>
	<a name="comentarios"></a>
	{include "m.posts_comments.tpl"}
	<a name="comentarios-abajo"></a>
	<br />
	{if !$tsUser->is_member}
		<div class="emptyData clearfix">Para poder comentar necesitas estar <a href="{$tsConfig.url}/registro">Registrado.</a> O.. ya tienes usuario? <a href="{$tsConfig.url}/login">Logueate!</a></div>
	{elseif $tsPost.block > 0}
		<div class="emptyData clearfix">&iquest;Te has portado mal? {$tsPost.user_name} te ha bloqueado y no podr&aacute;s comentar sus post.</div>
	{/if}
	<div style="text-align:center"><a class="irCielo" href="#cielo"><strong>Ir al cielo</strong></a></div>
</div>
<div style="clear:both"></div> *}    
{include "main_footer.tpl"}
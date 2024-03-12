<div id="post-comentarios">
	<div class="theme-comment-area">
      <div class="comment-respond">
         <h4 class="title"><span id="ncomments">{$tsPost.post_comments}</span> Comentarios</h4>
      </div>
   </div>
	<div class="comentarios-title">
		<h4 class="titulorespuestas floatL"></h4>
		<iconify-icon icon="eos-icons:loading" id="com_gif" style="display: none;"></iconify-icon>
		<hr />
	</div>
	{if $tsPost.post_comments > $tsConfig.c_max_com}
		<div class="comentarios-title"><div class="paginadorCom"></div></div>
	{/if}
	<div id="comentarios">
		<div id="no-comments" class="my-4 text-center text-warning">Cargando comentarios espera un momento...</div>
	</div>
	{if $tsPost.post_comments > $tsConfig.c_max_com}
		<div class="comentarios-title"><div class="paginadorCom"></div></div>
	{/if}
	{if $tsPost.post_block_comments == 1 && ($tsUser->is_admod == 0 && $tsUser->permisos.mocepc == false)}
		<div id="no-comments" class="my-4 text-center text-warning">El post se encuentra cerrado y no se permiten comentarios.</div>
	{elseif $tsUser->is_admod == 0 && $tsUser->permisos.gopcp == false}
		<div id="no-comments" class="my-4 text-center text-warning">No tienes permisos para comentar.</div>
	{elseif $tsUser->is_member && ($tsPost.post_block_comments != 1 || $tsPost.post_user == $tsUser->uid || $tsUser->is_admod || $tsUser->permisos.gopcp) && $tsPost.block == 0}
		<div class="miComentario">
			{include "m.posts_comments_form.tpl"}
		</div>
	{/if}
</div>
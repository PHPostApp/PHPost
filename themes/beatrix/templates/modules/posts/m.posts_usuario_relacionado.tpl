<div class="sidebar blog-sidebar">
	<div class="section-title">
		<h2 class="title-block">M&aacute;s posts del usuario</h2>
	</div>
	{if $tsRelatedUser}
      {foreach from=$tsRelatedUser item=p}
      {assign "userpost" "outname"}
			{include "m.posts_article.tpl"}
		{/foreach}
	{else}
		<div class="text-center alert text-warning">No tiene m&aacute;s posts creados.</div>
	{/if}
</div>
<div class="sidebar blog-sidebar mt-40">
	<div class="section-title">
		<h2 class="title-block">Posts relacionados</h2>
	</div>
	{if $tsRelated}
      {foreach from=$tsRelated item=p}
      	{assign "userpost" "inname"}
			{include "m.posts_article.tpl"}
		{/foreach}
	{else}
		<div class="text-center alert text-warning">No se encontraron posts relacionados.</div>
	{/if}
</div>
1:
{if $tsMuro.total}<div id="total_pubs" val="{$tsMuro.total}"></div>{/if}
{include "m.perfil_muro_story.tpl"}
<script>
  	new LazyLoad({
     	elements_selector: '.image',
     	use_native: true,
     	class_loading: 'lazy-loading',
     	callback_error: callback => {
		   callback.setAttribute("srcset", global_data.img + "/deleted-post.gif");
		   callback.setAttribute("src", global_data.img + "/suspension.gif");
		}
  	})
</script>
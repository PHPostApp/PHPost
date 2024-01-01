{include "main_header.tpl"}
{$tsInstall}
<div class="theme-breadcrumb-area">
   <div class="container">
      <div class="row">
         <div class="col-lg-12">
            <div class="breadcrumb-inner">
               <ul>
                  <li class="text-capitalize">{$tsPage}</li>
                  {if $tsCat}
                  	<li class="text-capitalize">Categor&iacute;a</li>
                  	<li class="text-capitalize">{$tsCat}</li>
                  {/if}
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="theme-blog-page-area mb-80">
	<div class="container">
		<div class="row">

			<div class="col-lg-8">
				<div class="row">
					{if $tsPosts}
						{assign "TypePost" "normal"}
						{foreach from=$tsPosts item=p}
							{include "m.home_lasts_posts.tpl"}
						{/foreach}
					{else}
						<div class="col-lg-12">
	   					<div class="fs-3 py-4 text-center">No hay posts aqu&iacute;</div>
	   				</div>
              	{/if}
				</div>
				<div class="row">
	            <div class="col-lg-12">
	               <div class="blog-pagination-area mt-20">
	               	{$tsPages.item}
	               </div>
	            </div>
	         </div>
	      </div>
	      <div class="col-lg-4">
				<div class="sidebar blog-sidebar" data-title="IMPORTANTES">
               <div class="section-title">
                  <h2 class="title-block">Importantes</h2>
               </div>
               {if $tsPostsStickys}
						{assign "TypePost" "fijos"}
						{foreach from=$tsPostsStickys item=p}
	               	{include "m.home_lasts_posts.tpl"}
						{/foreach}
	            {/if}
            </div>
            {include "m.global_ads_160.tpl"}
         </div>

		</div>
	</div>
</div>

{*<div id="izquierda">
	{include "m.home_last_posts.tpl"}
</div>
<div id="centro">
	{include "m.home_search.tpl"}
	{include "m.home_stats.tpl"}
	{include "m.home_last_comments.tpl"}
	{include "m.home_top_posts.tpl"}
	{include "m.home_top_users.tpl"}
</div>
<div id="derecha">
	{if $tsConfig.c_fotos_private == 1 || $tsUser->is_member}
		{include "m.home_fotos.tpl"}
	{/if}
	{include "m.home_afiliados.tpl"}
	<br class="spacer"/>
	{include "m.global_ads_160.tpl"}
</div>
<div style="clear:both"></div>*}

{include "main_footer.tpl"}
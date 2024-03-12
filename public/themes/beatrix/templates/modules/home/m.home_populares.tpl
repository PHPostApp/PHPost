{if $tsPopular}
<div class="row divider-border mt-5">
	{foreach $tsPopular key=i item=p}
		<div class="col-lg-4">
		   <div class="post-block-template-three-wrapper popular-post-block-bottom-wrapper">
		      <article class="post-block-style-wrapper post-block-template-three">
		         <div class="post-block-style-inner post-block-list-style-inner-three">
		            <div class="post-block-number-wrap">
		               <span class="post-number-counter">{$i+1}</span>
		            </div>
		            <div class="post-block-content-wrap">
		               <div class="post-item-title">
		                  <h2 class="post-title">
		                     <a href="{$p.post_url}" rel="internal" alt="{$p.post_title}">{$p.post_title}</a>
		                  </h2>
		               </div>
		               <div class="post-bottom-meta-list">
		                  <div class="post-meta-author-box">
		                     Por <a href="{$tsConfig.url}/perfil/{$p.user_name}">{$p.user_name}</a>
		                  </div>
		                  <div class="post-meta-date-box">{$p.post_fecha}</div>
		               </div>
		           </div>
		         </div>
		      </article>
		   </div>
		</div>
 	{/foreach}
</div>
{/if}
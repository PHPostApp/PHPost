{if $tsDo == 'search' && $tsPosts}
	<div style="font-size: 0.789rem;padding:.3rem">Parece que existen posts similares al que quieres agregar, te recomendamos leerlos antes para evitar un repost.</div>
	{foreach from=$tsPosts item=p}
		<a style="font-size: 0.8rem;background-color: #f003;border:1px solid #f009;display: inline-block;padding:0 .4rem;border-radius:.2rem;margin-bottom:.3rem;" rel="interal" href="{$tsConfig.url}/posts/{$p.c_seo}/{$p.post_id}/{$p.post_title|seo}.html" target="_blank">{$p.post_title}</a>
	{/foreach}
{else}
	{$tsTags}
{/if}
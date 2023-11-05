		</section>
		<footer role="contentinfo" class="d-flex flex-wrap justify-content-between align-items-center px-3 border-top">
	    	<div class="col-md-4 d-flex align-items-center">
	      	<span><a href="{$tsConfig.url}" class="text-decoration-none fw-bold">{$tsConfig.titulo}</a> &copy; {$smarty.now|date_format:"%Y"} - Powered by <a href="https://phpost.es" target="_blank" class="text-decoration-none fw-bold">PHPost.es</a></span>
	    	</div>

	    	<ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
	     		<li class="ms-3"><a class="fs-4" title="PHPost '23" href="https://discord.gg/mx25MxAwRe" target="_blank" tabindex="0" aria-labelledby="discord para unirse"><iconify-icon id="discord" icon="logos:discord-icon"></iconify-icon></a></li>
	      	<li class="ms-3"><a class="fs-4" title="PHPost '23" href="https://t.me/PHPost23" target="_blank" tabindex="1" aria-labelledby="telegram para unirse"><iconify-icon id="telegram" icon="logos:telegram"></iconify-icon></a></li>
	    	</ul>
	  </footer>
	</main>
	{if $tsUser->is_admod && $tsConfig.c_see_mod && $tsConfig.novemods.total}
		<div id="stickymsg" onmouseover="$('#brandday').css('opacity',0.5);" onmouseout="$('#brandday').css('opacity',1);" onclick="location.href = '{$tsConfig.url}/moderacion/'" style="cursor:default;">Hay {$tsConfig.novemods.total} contenido{if $tsConfig.novemods.total != 1}s{/if} esperando revisi&oacute;n</div>
	{/if}
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</body>
</html>
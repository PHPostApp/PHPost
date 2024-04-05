<div id="search_box" class="new-search posts">

	<div class="search-body">
		<form action="{$tsConfig.url}/buscador/" name="search" gid="{$tsConfig.ads_search}">
			<input type="hidden" name="e" value="web" />
			<div class="input">
				<input type="text" autocomplete="off" value="Buscar" name="q" class="input-search-middle"/>
				<a class="btn-search-home" href="javascript:$('form[name=search]').submit()">
					<svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 48 48"><g fill="#616161"><path d="m29.175 31.99l2.828-2.827l12.019 12.019l-2.828 2.827z"/><circle cx="20" cy="20" r="16"/></g><path fill="#37474f" d="m32.45 35.34l2.827-2.828l8.696 8.696l-2.828 2.828z"/><circle cx="20" cy="20" r="13" fill="#64b5f6"/><path fill="#bbdefb" d="M26.9 14.2c-1.7-2-4.2-3.2-6.9-3.2s-5.2 1.2-6.9 3.2c-.4.4-.3 1.1.1 1.4c.4.4 1.1.3 1.4-.1C16 13.9 17.9 13 20 13s4 .9 5.4 2.5c.2.2.5.4.8.4c.2 0 .5-.1.6-.2c.4-.4.4-1.1.1-1.5"/></svg>
				</a>
			</div>
			<label class="more-cats">
				<span>Categor&iacute;a:</span>
				<select name="cat">
					<option value="0">Todas</option>
					{foreach from=$tsConfig.categorias item=c}
						<option value="{$c.cid}"{if $tsCategoria == '$c.c_seo'} selected{/if}>{$c.c_nombre}</option>
				  	{/foreach}
			  </select>
			</label>
		</form>
	</div>

</div>
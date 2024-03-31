{if $tsAct == '' || $tsAct == 'editar' || $tsAct == 'nueva'}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest"></script>
<script>
$(() => {
   {if $tsAct == ''} 
   new Sortable(document.getElementById('cats_orden'), {
      animation: 150,
      dragClass: "arrastrar",
      selectedClass: 'seleccionado',
      fallbackTolerance: 3,
      multiDrag: true,
      store: {
         // Guardar orden
         set: sortable => $.post(global_data.url + '/admin-ordenar-categorias.php', 'cats=' + sortable.toArray().join(','))
      }
   });
   {/if}
   $('#cat_img').on('change', () => {
      var icono = $("#cat_img option:selected").val();
      $('#c_icon').css({
         "background": 'url(\'{$tsConfig.images}/icons/cat/'+icono+'\') no-repeat center',
         "background-size": '16px'
      })
   })
})
</script>
{/if}
<div class="boxy-title">
	 <h3>Administrar Categor&iacute;as</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsSave}<div class="mensajes ok">Tus cambios han sido guardados.</div>{/if}
	{if $tsAct == ''}
		{if !$tsSave}<div class="mensajes error">Puedes cambiar el orden de las categor&iacute;as tan s&oacute;lo con arrastrarlas con el puntero.</div>{/if}
		<table class="admin_table">
	      <h4>Categor&iacute;as</h4>
	      <thead>
	         <th>ID</th>
	         <th>Orden</th>
	         <th>Imagen</th>
	         <th>Categoría</th>
	         <th>SEO</th>
	       	<th>Acción</th>
	      </thead>
			<tbody id="cats_orden">
				{foreach from=$tsConfig.categorias item=c}
				<tr id="{$c.cid}" data-id="{$c.cid}">
					<td width="30">{$c.cid}</td>
					<td width="30">{$c.c_orden}</td>
					<td width="30"><img src="{$tsConfig.images}/icons/cat/{$c.c_img}" alt="{$c.c_nombre}"></td>
					<td style="text-align:left;"><strong>{$c.c_nombre}</strong></td>
					<td style="text-align:left;"><em>{$c.c_seo}</em></td>
					<td class="admin_actions" width="100">
						<a href="{$tsConfig.url}/admin/cats?act=editar&cid={$c.cid}&t=cat"><img src="{$tsConfig.images}/icons/editar.png" title="Editar Categor&iacute;a"/></a>
						<a href="{$tsConfig.url}/admin/cats?act=borrar&cid={$c.cid}&t=cat"><img src="{$tsConfig.images}/icons/close.png" title="Borrar Categor&iacute;a"/></a>
					</td>
				</tr>
				{/foreach}
			</tbody>
			<tfoot>	
				<td colspan="3">&nbsp;</td>
			</tfoot>
		</table><hr />
		<a href="{$tsConfig.url}/admin/cats?act=nueva&t=cat" class="mBtn btnOk">Agregar Nueva Categor&iacute;a</a>
		<a href="{$tsConfig.url}/admin/cats?act=change" class="btn_g">Mover Posts</a>							
	{elseif $tsAct == 'editar'}
		<form action="" method="post" autocomplete="off">
		  	<fieldset>
				<legend>Editar</legend>
				<dl>
					<dt><label for="cat_name">Nombre de la categor&iacute;a:</label></dt>
					<dd><input type="text" id="cat_name" name="c_nombre" value="{$tsCat.c_nombre}" /></dd>
				</dl>
				<dl>
					<dt><label for="cat_img">Icono de la categor&iacute;a:</label></dt>
					<dd>
                  <img src="{$tsConfig.images}/space.gif" style="background:url({$tsConfig.images}/icons/cat/{$tsCat.c_img}) no-repeat left center;" width="16" height="16" id="c_icon"/>
					  	<select name="c_img" id="cat_img" style="width:164px">
					  		{foreach from=$tsIcons key=i item=img}
								<option value="{$img}"{if $tsCat.c_img == $img} selected{/if}>{$img}</option>
					  		{/foreach}
					  	</select>
					</dd>
				</dl>
				<p><input type="submit" name="save" value="Guardar cambios" class="btn_g"/  ></p>
		  </fieldset>
		  </form>
	 {elseif $tsAct == 'nueva'}
		  <div class="mensajes error">Si deseas m&aacute;s iconos para las categor&iacute;as debes subirlos al directorio: {$tsConfig.images}/icons/cat/</div>
		  <form action="" method="post" autocomplete="off">
		  <fieldset>
				<legend>Nueva</legend>
				<dl>
					<dt><label for="cat_name">Nombre de la categor&iacute;a:</label></dt>
					<dd><input type="text" id="cat_name"name="c_nombre" value="" /></dd>
				</dl>
				<dl>
					<dt><label for="cat_img">Icono de la categor&iacute;a:</label></dt>
					<dd>
						<img src="{$tsConfig.images}/space.gif" width="16" height="16" id="c_icon"/>
						<select name="c_img" id="cat_img" style="width:164px">
						  	{foreach from=$tsIcons key=i item=img}
								<option value="{$img}">{$img}</option>
						  	{/foreach}
						</select>
					</dd>
				</dl>
				<p><input type="submit" name="save" value="Crear Categor&iacute;a" class="btn_g"/></p>
		  </fieldset> 
		</form>
	{elseif $tsAct == 'borrar'}
		{if $tsError}<div class="mensajes error">{$tsError}</div>{/if}
		{if $tsType == 'cat'}
		  	<form action="" method="post" id="admin_form">
				<label for="h_mov" style="width:500px;">Borrar categor&iacute;a y mover las subcategor&iacute;as y demas datos a otra categor&iacute;a diferente. Mover datos a:</label>
				<select name="ncid">
					<option value="-1">Categor&iacute;as</option>
					{foreach from=$tsConfig.categorias item=c}
						{if $c.cid != $tsCID}
							<option value="{$c.cid}">{$c.c_nombre}</option>
						{/if}
					 {/foreach}
				</select>
			<hr />
			<label>&nbsp;</label> <input type="submit" name="save" value="Guardar cambios" class="mBtn btnOk">
		  </form>	                                        
		  {/if}
	{elseif $tsAct == 'change'}
		{if $tsError}<div class="mensajes error">{$tsError}</div>{/if}
		<form action="" method="post" id="admin_form">
			<label style="width:500px;">Mover todos los posts de la categor&iacute;a </label>
			<select name="oldcid">
				<option value="-1">Categor&iacute;as</option>
				{foreach from=$tsConfig.categorias item=c}
					{if $c.cid != $tsCID}
						<option value="{$c.cid}">{$c.c_nombre}</option>
					{/if}
				 {/foreach}
			</select>
			<label style="width:500px;"> a </label>
			<select name="newcid">
				<option value="-1">Categor&iacute;as</option>
				{foreach from=$tsConfig.categorias item=c}
					{if $c.cid != $tsCID}
						<option value="{$c.cid}">{$c.c_nombre}</option>
					{/if}
				 {/foreach}
			</select>
			<hr />
			<label>&nbsp;</label> <input type="submit" name="save" value="Guardar cambios" class="mBtn btnOk">
		</form>	                                        
	{/if}
</div>
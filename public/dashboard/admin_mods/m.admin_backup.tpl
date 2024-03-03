<div class="boxy-title">
	 <h3>Administrar Copias de seguridad</h3>
</div>
<div id="res" class="boxy-content">
	{if $tsAct == ''}
		{if !$verifyFiles}
			<div class="phpostAlfa">No hay copias de seguridad.</div>
		{else}
			<table class="admin_table table">
				<thead>
					<th>#</th>
					<th>Nombre de copia</th>
					<th>Fecha</th>
					<th>Peso</th>
					<th>Acciones</th>
			  	</thead>
			  	<tbody>
			  		{foreach from=$verifyFiles key=numb item=copy}
					<tr>
						<td>{$numb+1}</td>
						<td>{$copy.name}</td>
						<td>{$copy.date|hace}</td>
						<td>{$copy.size}</td>
						<td class="admin_actions">
							<a href="javascript:admin.backupDel('{$copy.name}')">Borrar copia</a>
						</td>
					</tr>
					{/foreach}
			 	</tbody>
		 	</table>
		 	<hr />
		{/if}
		<input type="button" onclick="location.href = 'javascript:admin.backup()'" value="Crear nueva copia" class="mBtn btnOk">
	{/if}
</div>
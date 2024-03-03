<div class="boxy-title">
	<h3>Centro de Administraci&oacute;n</h3>
</div>
<div id="res" class="boxy-content">
	<div class="p-3 rounded ">
		<h4><iconify-icon icon="noto-v1:waving-hand"></iconify-icon> Hola, {$tsUser->nick}!</h4>
		<p class="lead"><iconify-icon icon="noto-v1:backhand-index-pointing-right"></iconify-icon> Este es tu &quot;<strong>Centro de Administraci&oacute;n de {$tsConfig.titulo}</strong>&quot;. Aqu&iacute; puedes modificar la configuraci&oacute;n de tu web, modificar usuarios, modificar posts, y muchas otras cosas.<br />Si tienes algun problema, por favor revisa la p&aacute;gina de &quot;<a class="fw-bold fst-italic" href="{$tsConfig.url}/admin/creditos"> Soporte y Cr&eacute;ditos</a>&quot;.  Si esa informaci&oacute;n no te sirve, puedes <a href="https://phpost.es/" class="fw-bold fst-italic" target="_blank">visitarnos para solicitar ayuda</a> acerca de tu problema.</p>
	</div>
	 
	<div class="row">
		<div class="col-12 col-sm-12 col-md-6 col-lg-9 col-xl-8">
			<!-- INFORMACIÓN SOBRE EL SCRIPT -->
			<div class="panel-info card">
				<h5 class="card-header">PHPost en directo</h5>
				<div id="ulitmas_noticias" class="list-group list-group-flush">
					<div class="phpostAlfa">Cargando...</div>
				</div>
			</div>
			<!-- INFOMACIÓN DEL ÚLTIMO COMMIT -->
			<div class="panel-info last-commit card">
				<h5 class="card-header d-flex justify-content-between align-items-center"><span>Último commit en Github</span>
					<div class="text-end">
						<!-- Radio buttons as toggle buttons -->
						<input type="radio" name="branch" class="btn-check" id="master" checked>
						<label class="btn btn-outline-primary" for="master">Master</label>
						 
						<input type="radio" name="branch" class="btn-check" id="dev">
						<label class="btn btn-outline-primary" for="dev">Dev</label>
					</div>
				</h5>
				<div id="lastCommit" class="card-body">
					<div class="phpostAlfa">Cargando...</div>
				</div>
	  			<div class="card-footer d-flex justify-content-between align-items-center"></div>
			</div>
		</div>
		<div class="col-12 col-sm-12 col-md-6 col-lg-3 col-xl-4">
			<div class="panel-info card version">
				<h5 class="card-header">Estado de los archivos</h5>
				<div id="status_pp" class="card-body text-center">
					{if $tsConfig.updated === '1'}
						<strong>Estas al día</strong>
					{else}
						<strong>Hay nuevas actualizaciones</strong>
						<br>
						<a href="javascript:admin.updated()">Actualizar ahora con Github</a>
					{/if}
				</div>
			</div>
			<div class="panel-info card">
				<h5 class="card-header">PHPost Risus</h5>
				<div id="ultima_version" class="list-group list-group-flush">
				  	<span class="list-clone list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
				    	<div class="me-auto">
				      	<div class="fw-bold">Versi&oacute;n instalada</div>
				      	<span class="text-body-secondary">{$tsConfig.version}</span>
				    	</div>
				  	</span>
				</div>
			</div>
			<div class="panel-info card">
				<h5 class="card-header">Administradores</h5>
				<ul class="list-group list-group-flush">
					{foreach from=$tsAdmins item=admin}
				  		<li class="list-group-item">
				  			<a class="text-decoration-none" href="{$tsConfig.url}/perfil/{$admin.user_name}">{$admin.user_name}</a>
				  		</li>
				  	{/foreach}
				</ul>
			</div>
			<div class="panel-info card">
				<h5 class="card-header">Instalaciones</h5>
				<div id="ulitmas_noticias" class="list-group list-group-flush">
					<span title="{$tsInst.0|fecha}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
					 	<div class="me-auto">
					   	<div class="fw-bold">Fundaci&oacute;n</div>
					 	</div>
		    			<span class="badge text-bg-primary rounded-pill">{$tsInst.0|hace}</span>
					</span>
					<span title="{$tsInst.1|fecha}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2">
					 	<div class="me-auto">
					   	<div class="fw-bold">Actualizado</div>
					 	</div>
		    			<span class="badge text-bg-primary rounded-pill">{$tsInst.1|hace}</span>
					</span>
				</div>                              
			</div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emoji-toolkit/extras/css/joypixels.min.css">
<script src="https://cdn.jsdelivr.net/combine/npm/timeago,npm/emoji-toolkit"></script>
{phpost js="versiones.js"}
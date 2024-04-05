<div class="boxy-title">
	<h3>Centro de Administraci&oacute;n</h3>
</div>
<div id="res" class="boxy-content">
	<p><strong>Bienvenido(a), {$tsUser->nick}!</strong><br />Este es tu &quot;Centro de Administraci&oacute;n de PHPost&quot;. Aqu&iacute; puedes modificar la configuraci&oacute;n de tu web, modificar usuarios, modificar posts, y muchas otras cosas.<br />Si tienes algun problema, por favor revisa la p&aacute;gina de &quot;Soporte y Cr&eacute;ditos&quot;.  Si esa informaci&oacute;n no te sirve, puedes <a href="https://phpost.es" target="_blank">visitarnos para solicitar ayuda</a> acerca de tu problema.</p>
	<hr>
	<p><h3>También pudes sumarte a nuestros grupos:</h3>
		<span style="display: block;">Discord <a href="https://discord.gg/mx25MxAwRe" target="_blank">PHPost '24</a></span>
		<span style="display: block;">Telegram <a href="https://t.me/PHPost23" target="_blank">PHPost '24</a></span>
	</p>
	 
	<hr class="separator" />
	<div class="phpost">
		<h4>PHPost en directo</h4>
		<ul id="ulitmas_noticias" class="pp_list">
			<div class="phpostAlfa">Cargando...</div>
		</ul>
		<h4 class="options">Último commit en Github
			<div class="text-end">
				<!-- Radio buttons as toggle buttons -->
				<input type="radio" name="branch" class="btn-check" id="main" checked>
				<label class="btn btn-outline-primary" for="main">Main</label>
				 
				<input type="radio" name="branch" class="btn-check" id="dev">
				<label class="btn btn-outline-primary" for="dev">Dev</label>
			</div>
		</h4>
		<ul id="lastCommit" class="pp_list">
			<div class="phpostAlfa">Cargando...</div>
		</ul>
	</div>
	<div class="phpost version">
		<h4>Estado</h4>
		<ul id="status_pp" class="pp_list">
			<li>
				<div class="title">Estado de los archivos</div>
				<div class="body">
					{if $tsConfig.updated === '1'}
						<strong>Estas al día</strong>
					{else}
						<strong>Hay nuevas actualizaciones</strong>
						<br>
						<a href="javascript:admin.updated()">Actualizar ahora con Github</a>
					{/if}
				</div>
			</li>
		</ul>
		<h4>PHPost Risus</h4>
		<ul id="version_pp" class="pp_list">
			<li>
				<div class="title">Versi&oacute;n instalada</div>
				<div class="body"><strong>{$tsConfig.version}</strong></div>
			</li>
		</ul>
		<h4>Administradores</h4>
		<ul class="pp_list">                                    
			{foreach from=$tsAdmins item=admin}
			<li><div class="title"><a href="{$tsConfig.url}/perfil/{$admin.user_name}">{$admin.user_name}</a></div></li>                                    
			{/foreach}
		</ul>
		<h4>Instalaciones</h4>
		<ul class="pp_list stats">
		 	<li><span>Fundaci&oacute;n</span><span title="{$tsInst.0|fecha}">{$tsInst.0|hace:true}</span></li>
		 	<li><span>Actualizado</span><span title="{$tsInst.1|fecha}">{$tsInst.1|hace:true}</span></li>
		</ul>                                  
	</div>
	<div class="clearBoth"></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/emoji-toolkit@8.0.0/lib/js/joypixels.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/emoji-toolkit@8.0.0/extras/css/joypixels.min.css" rel="stylesheet">
{phpost js="versiones.js"}
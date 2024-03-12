{include "main_header.tpl"}

	<div style="display:none" id="preview"></div>
	<div class="container mt-40"> <!-- was-validated -->
		{if $tsUser->is_admod || $tsUser->permisos.gopp}
			<form class="needs-validation was-validated" action="{$tsConfig.url}/agregar.php{if $tsAction == 'editar'}?action=editar&pid={$tsPid}{/if}" method="post" name="newpost" enctype="multipart/form-data" autocomplete="off" novalidate>
				<!-- Si es un borrador -->
				<input type="hidden" value="{$tsDraft.bid}" name="borrador_id"/>
				<div class="row">
					<div class="col-12 col-sm-12 col-lg-8 col-xl-8 col-xxl-8">
						{include "m.agregar_content.tpl"}
					</div>
					<div class="col-12 col-sm-12 col-lg-4 col-xl-4 col-xxl-4">
						{include "m.agregar_sidebar.tpl"}
					</div>
				</div>
			</form>
		{else}
			<div class="alert alert-danger text-center">Lo sentimos, pero no puedes publicar un nuevo post.</div>
		{/if}
	</div>

{include "main_footer.tpl"}
<div id="procesando"><div id="post"></div></div>
<div style="display: grid; grid-template-columns: 4.2rem 1fr;gap:1rem">
	<div class="">
		<div class="answerInfo avatar-xl position-relative">
			<img src="{$tsConfig.url}/files/avatar/{$tsUser->uid}_120.jpg" class="image avatar avatar-xl"/>
			<div id="gif_cargando" class="position-absolute avatar avatar-xl" style="background-color:rgba(0,0,0,.7);top: 0;left: 0;display:none;text-align: center;line-height: 5.5rem;">
				<iconify-icon icon="eos-icons:loading" style="font-size: 2rem;"></iconify-icon>
			</div>
		</div>
	</div>
	<div class="">
		<div class="answerTxt">
			<div class="Container">
				<div class="error"></div>
				<textarea id="body_comm" class="onblur_effect autogrow" tabindex="1" title="Escribir un comentario..." style="resize:none;">Escribir un comentario...</textarea>
				<div class="buttons mt-3">
					<input type="hidden" id="auser_post" value="{$tsPost.post_user}" />
					<input type="button" onclick="comentario.nuevo('true')" class="btn btn-primary" value="Enviar Comentario" tabindex="3" id="btnsComment"/>
				</div>
			</div>
		</div>
	</div>
</div>
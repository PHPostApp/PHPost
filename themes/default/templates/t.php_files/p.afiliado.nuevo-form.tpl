<div class="emptyData" id="AFStatus">
	<span>Ingresa los datos de tu web para afiliarte.</span>
</div>
<form name="AFormInputs">
	<div class="form-line">
		<label for="atitle">T&iacute;tulo</label>
		<input type="text" tabindex="1" name="atitle" id="atitle" maxlength="35" required />
		<div class="help"></div>
	</div>
	<div class="form-line">
		<label for="aurl">Direcci&oacute;n</label>
		<input type="url" tabindex="2" name="aurl" id="aurl" pattern="https://.*" placeholder="Direcci&oacute;n" required />
		<div class="help"></div>
	</div>
	<div class="form-line">
		<label for="aimg">Banner <small>(216x42px)</small></label>
		<input type="url" tabindex="3" name="aimg" id="aimg" pattern="https://.*" placeholder="Banner" />
	</div>
	<div class="form-line">
		<label for="atxt">Descripci&oacute;n</label>
		<textarea tabindex="4" rows="10" name="atxt" id="atxt" style="height:60px; width:100%"></textarea>
		<div class="help"></div>
	</div>
</form>
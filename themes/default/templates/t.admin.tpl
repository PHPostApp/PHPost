{include "main_header.tpl"}
<div id="borradores">
	<div class="clearfix">
		<div class="left" style="float:left;width:200px">
			<div class="boxy">
				<div class="boxy-title">
					<h3>Men&uacute;</h3>
					<span></span>
				</div><!-- boxy-title -->
				<div class="boxy-content" id="admin_menu">
					{include "m.admin_sidemenu.tpl"}
				</div><!-- boxy-content -->
			 </div>
		</div>
		<div class="right" style="float:left;margin-left:10px;width:730px">
			<div class="boxy" id="admin_panel">
				{include "m.admin_$tsAction.tpl"}
			</div>
		</div>
	</div>
</div>
<div style="clear:both"></div>
{include "main_footer.tpl"}
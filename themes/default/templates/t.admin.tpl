{include "main_header.tpl"}
                <script type="text/javascript" src="{$tsConfig.js}/admin.js"></script>
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
                            	{* Q WEBA PERO NO HAY DE OTRA xD*}
                            	{if $tsAction == ''}
                            	{include "m.admin_welcome.tpl"}
                                {elseif $tsAction == 'creditos'}
                            	{include "m.admin_creditos.tpl"}
                                {elseif $tsAction == 'configs'}
                            	{include "m.admin_configs.tpl"}
                                {elseif $tsAction == 'temas'}
                            	{include "m.admin_temas.tpl"}
                                {elseif $tsAction == 'news'}
                            	{include "m.admin_noticias.tpl"}
                                {elseif $tsAction == 'ads'}
                            	{include "m.admin_publicidad.tpl"}
                                {elseif $tsAction == 'medals'}
                            	{include "m.admin_medallas.tpl"}
								{elseif $tsAction == 'stats'}
                            	{include "m.admin_stats.tpl"}
								{elseif $tsAction == 'posts'}
                            	{include "m.admin_posts.tpl"}
								{elseif $tsAction == 'fotos'}
                            	{include "m.admin_fotos.tpl"}
                                {elseif $tsAction == 'afs'}
                            	{include "m.admin_afiliados.tpl"}
                                {elseif $tsAction == 'pconfigs'}
                            	{include "m.admin_posts_configs.tpl"}
                                {elseif $tsAction == 'cats'}
                            	{include "m.admin_cats.tpl"}
                                {elseif $tsAction == 'users'}
                            	{include "m.admin_users.tpl"}
								{elseif $tsAction == 'sesiones'}
                            	{include "m.admin_sesiones.tpl"}
								{elseif $tsAction == 'nicks'}
                            	{include "m.admin_nicks.tpl"}
                                {elseif $tsAction == 'blacklist'}
                            	{include "m.admin_blacklist.tpl"}
                                {elseif $tsAction == 'badwords'}
                                {include "m.admin_badwords.tpl"}
                                {elseif $tsAction == 'rangos'}
                            	{include "m.admin_rangos.tpl"}
                                {/if}
                            </div>
                        </div>
                    </div>
                </div>
                <div style="clear:both"></div>
{include "main_footer.tpl"}
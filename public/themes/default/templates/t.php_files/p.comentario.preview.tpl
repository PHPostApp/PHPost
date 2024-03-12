1:
{if $tsType == 'new'} 
<div id="div_cmnt_{$tsComunidadesment.0}" class="{if $tsComunidadesment.4 == $tsUser->uid}especial1{else}especial3{/if}">
    <span id="citar_comm_{$tsComunidadesment.0}" style="display:none">{$tsComunidadesment.2}</span>
    <div class="comentario-post clearbeta">
        <div class="avatar-box" style="z-index: 99;">
            <a href="{$tsConfig.url}/perfil/{$tsUser->info.user_name}">
                <img width="48" height="48" title="{$tsUser->info.user_name}" src="{$tsConfig.url}/files/avatar/{$tsUser->uid}_50.jpg" class="avatar-48 lazy" style="position: relative; z-index: 1; display: inline;"/>
            </a>
        </div>
        <div class="comment-box">
            <div class="dialog-c"></div>
            <div class="comment-info clearbeta">
                <div class="floatL">
                    <a href="{$tsConfig.url}/perfil/{$tsUser->nick}" class="nick">{$tsUser->nick}</a>  {if $tsUser->is_admod}(<span style="color:red;">IP:</span> <a href="{$tsConfig.url}/moderacion/buscador/1/1/{$tsComunidadesment.6}" class="geoip" target="_blank">{$tsComunidadesment.6}</a>){/if} dijo
                    <span>{$tsComunidadesment.3|hace}</span> :
                </div>
                <div class="floatR answerOptions">
                    <ul>{if $tsComunidadesment.0 > 0}
                    	{if $tsUser->is_member}
                        <li class="answerCitar">
                        	<a onclick="citar_comment({$tsComunidadesment.0}, '{$tsUser->nick}')">
                                <span class="citar-comentario"></span>
                            </a>
                        </li>
                        {/if}
						{if $tsUser->is_admod || $tsUser->permisos.goepc}
                    	<li>
                        	<a onclick="comentario.editar({$tsComunidadesment.0}, 'show')" title="Editar">
                                <span class="editar-comentario"></span>
                            </a>
                        </li>
						{/if}
						{if $tsUser->is_admod || $tsUser->permisos.godpc}
                        <li class="iconDelete">
                            <a onclick="borrar_com({$tsComunidadesment.0}, {$tsUser->uid})">
								<span class="borrar-comentario"></span>
							</a>
                        </li>
						{/if}
						{if $tsUser->is_admod || $tsUser->permisos.moaydcp}
						<li class="iconHide">
                                                    	
							<a onclick="ocultar_com({$tsComunidadesment.0}, {$tsUser->uid});" title="Ocultar/Mostrar">
															
								<span class="moderar-comentario"></span>
														
							</a>
                                                    
						</li>
						{/if}
                        {else}
                        <li><a><span style="color:red;width:auto;background:none;"><strong>VISTA PREVIA</strong></span></a></li>
                        {/if}
                    </ul>
                </div>
            </div>
            <div id="comment-body-{$tsComunidadesment.0}" class="comment-content" style="text-align:left;">
                {$tsComunidadesment.1|nl2br}
            </div>
        </div>
    </div>
</div>
{elseif $tsType == 'edit'}
<div id="preview" class="box_cuerpo" style="margin: -15px 0 0; font-size:13px; line-height: 1.4em; min-width:300px;max-width: 760px; padding: 12px 20px; overflow-y: auto; text-align: left; border-top:1px solid #CCC">
    <div id="new-com-html">{$tsComunidadesment.1|nl2br}</div>
    <div id="new-com-bbcode" style="display:none">{$tsComunidadesment.5}</div>
</div>
{/if}
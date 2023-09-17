<div style="width: 210px; float: right;" id="post-izquierda">
    <div class="categoriaList">
        <h6 style="margin:0;">Filtrar Actividad</h6>
        <ul>
            <li style="text-align:center;">Elige que notificaciones recibir y cuales no.</li>
            <li class="divider"></li>
            <li><strong>Mis Posts</strong></li>
            <li><label><span class="monac_icons ma_star"></span><input type="checkbox" id="1"{if $tsData.filtro.f1 == true} checked{/if} onclick="notifica.filter()"/> Favoritos</label></li>
            <li><label><span class="monac_icons ma_comment_post"></span><input type="checkbox" id="2"{if $tsData.filtro.f2 == true} checked{/if} onclick="notifica.filter()"/> Comentarios</label></li>
            <li><label><span class="monac_icons ma_points"></span><input type="checkbox" id="3"{if $tsData.filtro.f3 == true} checked{/if} onclick="notifica.filter()"/> Puntos Recibidos</label></li>
            <li><strong>Mis Comentarios</strong></li>
            <li><label><span class="monac_icons ma_voto"></span><input type="checkbox" id="8"{if $tsData.filtro.f8 == true} checked{/if} onclick="notifica.filter()"/> Votos</label></li>
            <li><label><span class="monac_icons ma_comment_resp"></span><input type="checkbox" id="9"{if $tsData.filtro.f9 == true} checked{/if} onclick="notifica.filter()"/> Respuestas</label></li>
            <li><strong>Usuarios que sigo</strong></li>
            <li><label><span class="monac_icons ma_follow"></span><input type="checkbox" id="4"{if $tsData.filtro.f4 == true} checked{/if} onclick="notifica.filter()"/> Nuevos</label></li>
            <li><label><span class="monac_icons ma_post"></span><input type="checkbox" id="5"{if $tsData.filtro.f5 == true} checked{/if} onclick="notifica.filter()"/> Posts</label></li>
            <li><label><span class="monac_icons ma_photo"></span><input type="checkbox" id="10"{if $tsData.filtro.f10 == true} checked{/if} onclick="notifica.filter()"/> Fotos</label></li>
            <li><label><span class="monac_icons ma_share"></span><input type="checkbox" id="6"{if $tsData.filtro.f6 == true} checked{/if} onclick="notifica.filter()"/> Recomendaciones</label></li>
            <li><strong>Posts que sigo</strong></li>
            <li><label><span class="monac_icons ma_blue_ball"></span><input type="checkbox" id="7"{if $tsData.filtro.f7 == true} checked{/if} onclick="notifica.filter()"/> Comentarios</label></li>
            <li><strong>Mis Fotos</strong></li>
            <li><label><span class="monac_icons ma_comment_post"></span><input type="checkbox" id="11"{if $tsData.filtro.f11 == true} checked{/if} onclick="notifica.filter()"/> Comentarios</label></li>
            <li><strong>Perfil</strong></li>
            <li><label><span class="monac_icons ma_status"></span><input type="checkbox" id="12"{if $tsData.filtro.f12 == true} checked{/if} onclick="notifica.filter()"/> Publicaciones</label></li>
            <li><label><span class="monac_icons ma_w_comment"></span><input type="checkbox" id="13"{if $tsData.filtro.f13 == true} checked{/if} onclick="notifica.filter()"/> Comentarios</label></li>
            <li><label><span class="monac_icons ma_w_like"></span><input type="checkbox" id="14"{if $tsData.filtro.f14 == true} checked{/if} onclick="notifica.filter()"/> Likes</label></li>

        </ul>
    </div>
    <div class="categoriaList estadisticasList">
        <h6>Estad&iacute;sticas</h6>
        <ul>
            <li class="clearfix"><a href="{$tsConfig.url}/monitor/seguidores"><span class="floatL">Seguidores</span><span class="floatR number">{$tsData.stats.seguidores}</span></a></li>
            <li class="clearfix"><a href="{$tsConfig.url}/monitor/siguiendo"><span class="floatL">Usuarios Siguiendo</span><span class="floatR number">{$tsData.stats.siguiendo}</span></a></li>
            <li class="clearfix"><a href="{$tsConfig.url}/monitor/posts"><span class="floatL">Posts</span><span class="floatR number">{$tsData.stats.posts}</span></a></li>
        </ul>
    </div>
    {if $tsConfig.c_allow_live == 1}
    <div class="categoriaList">
        <h6>Notificaciones Live</h6>
        <ul>
            <li class="clearfix"><label><input type="checkbox"{if $tsStatus.live_nots == 'ON'} checked{/if} onclick="live.ch_status('nots');"/> <strong>Mostrar notificaciones</strong></label></li>
            <li class="clearfix"><label><input type="checkbox"{if $tsStatus.live_mps == 'ON'} checked{/if} onclick="live.ch_status('mps');"/> <strong>Mostrar mensajes nuevos</strong></label></li>
            <li class="clearfix"><label><input type="checkbox"{if $tsStatus.live_sound == 'ON'} checked{/if} onclick="live.ch_status('sound');"/> <strong>Reproducir sonidos</strong></label></li>
        </ul>
    </div>
    {/if}
</div>
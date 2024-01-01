<div class="com_tema_cuerpo clearfix">
    <div class="ctc_share clearfix">
        <span class="ctc_date floatL qtip" title="{$tsTema.t_fecha_all}">{$tsTema.t_fecha|hace}</span>
        <div class="floatR clearfix">
            <div id="fb-root"></div>
            {literal}
            <script>(function(d, s, id) {
              var js, fjs = d.getElementsByTagName(s)[0];
              if (d.getElementById(id)) return;
              js = d.createElement(s); js.id = id;
              js.src = "//connect.facebook.net/es_LA/all.js#xfbml=1&appId=166217036892146";
              fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
			  window.___gcfg = {lang: 'es-419'};			
			  (function() {
				var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
				po.src = 'https://apis.google.com/js/platform.js';
				var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			  })();
			  !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
			</script>
            {/literal}
            <div class="fb-like" data-href="{$tsConfig.url}/comunidades/{$tsTema.c_nombre_corto}/{$tsTema.t_id}/{$tsTema.t_titulo|seo}.html" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>      
            <div class="g-plusone" data-size="medium"></div>
            <a onclick="share.facebook({$tsTema.t_id});" href="{$tsConfig.url}/comunidades/{$tsTema.c_nombre_corto}/{$tsTema.t_id}/{$tsTema.t_titulo|seo}.html" class="twitter-share-button" data-text="{$tsTema.t_titulo} - {$tsConfig.url}/comunidades/{$tsTema.c_nombre_corto}/{$tsTema.t_id}/{$tsTema.t_titulo|seo}.html" data-url="{$tsConfig.url}/comunidades/{$tsTema.c_nombre_corto}/{$tsTema.t_id}/{$tsTema.t_titulo|seo}.html" data-via="{$tsConfig.titulo}" data-lang="es" data-count="horizontal">Twittear</a>
        </div>
    </div>
    <h1 class="ctc_h1">{$tsTema.t_titulo}</h1>
    <div class="ctc_body">{$tsTema.t_cuerpo}</div>
</div>
<div class="com_tema_share clearfix">
	<ul class="cts_buttons">
    	<li class="ctsb_facebook">									 	
            <div class="fb-share-button" data-href="{$tsConfig.url}/comunidades/{$tsTema.c_nombre_corto}/{$tsTema.t_id}/{$tsTema.t_titulo|seo}.html" data-type="box_count"></div>
        </li>
    	<li class="ctsb_web">									 	
            <span class="share-t-count">{$tsTema.t_share|number_format:0:',':'.'}</span>            
            <a href="javascript:com.reco_tema()" class="share-t"></a>        
        </li>
    	<li class="ctsb_google">
        	<div class="g-plusone" data-size="tall" data-annotation="bubble"></div>
        </li>
    	<li class="ctsb_twitter">
        	<a onclick="share.facebook({$tsTema.t_id});" href="{$tsConfig.url}/comunidades/{$tsTema.c_nombre_corto}/{$tsTema.t_id}/{$tsTema.t_titulo|seo}.html" class="twitter-share-button" data-text="{$tsTema.t_titulo} - {$tsConfig.url}/comunidades/{$tsTema.c_nombre_corto}/{$tsTema.t_id}/{$tsTema.t_titulo|seo}.html" data-url="{$tsConfig.url}/comunidades/{$tsTema.c_nombre_corto}/{$tsTema.t_id}/{$tsTema.t_titulo|seo}.html" data-via="{$tsConfig.titulo}" data-lang="es" data-count="vertical">Twittear</a>
        </li>
    </ul>
</div>
<div class="com_tema_options clearfix">
	{if $tsUser->is_member}
	{if $tsTema.t_autor != $tsUser->uid}
	<a href="javascript:com.votar_tema('pos');" class="input_button"><i class="com_icon icon_like"></i> Me gusta</a>
    <a href="javascript:com.votar_tema('neg');" class="input_button"><i class="com_icon icon_dislike"></i></a>
    <a href="javascript:com.seguir_tema();" class="input_button" id="follow_tema" {if $tsTema.es_seguidor}style="display:none"{/if}><i class="com_icon icon_eye"></i>Seguir</a>
    <a href="javascript:com.seguir_tema();" class="input_button ibg" id="unfollow_tema" style="{if !$tsTema.es_seguidor}display:none{/if}"><i class="com_icon icon_eye"></i>Siguiendo</a>
    <a href="javascript:com.seguir_tema();" class="input_button ibr" id="unfollow_temar" style="display:none;padding: 7px 10px;">Dejar de seguir</a>
    <a href="javascript:com.add_favorito();" class="input_button add_favorito" {if $tsTema.mi_fav}style="display:none;"{/if} title="A&ntilde;adir a mis favoritos"><i class="com_icon icon_favs"></i></a>
    <a href="javascript:void(0);" class="input_button ibg add_favorito" {if !$tsTema.mi_fav}style="display:none;"{/if} title="Lo tienes en tus favoritos"><i class="com_icon icon_favs"></i></a>
    {/if}
    {else}
    <a href="{$tsConfig.url}/registro" class="input_button"><i class="com_icon icon_like"></i> Reg&iacute;strate para acceder a las opciones del tema</a>
    {/if}
    <ul class="cts_stats clearfix floatR">
    	<li>
        	<span id="favs_total">{$tsTema.t_favoritos|number_format:0:',':'.'}</span><i class="com_icon icon_favs"></i>
            <div>Favoritos</div>
        </li>
        <li>
        	<span>{$tsTema.t_visitas|number_format:0:',':'.'}</span><i class="com_icon icon_visitas"></i>
            <div>Visitas</div>
        </li>
        <li>
        	<span id="segs_total">{$tsTema.t_seguidores|number_format:0:',':'.'}</span><i class="com_icon icon_eye"></i>
            <div>Seguidores</div>
        </li>
        <li>
        	<span id="votos_total" style="color:{if $tsTema.t_votos_pos-$tsTema.t_votos_neg > 0}green{elseif $tsTema.t_votos_pos-$tsTema.t_votos_neg < 0}red{else}#222{/if}">{$tsTema.t_votos_pos-$tsTema.t_votos_neg|number_format:0:',':'.'}</span><i class="com_icon icon_like"></i>
            <div>Calificaci&oacute;n</div>
        </li>
    </ul>
</div>

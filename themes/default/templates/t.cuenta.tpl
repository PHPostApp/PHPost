{include "main_header.tpl"}
<script>
   $(document).ready(function(){
      avatar.uid = '{$tsUser->uid}';
      avatar.current = '{$tsConfig.url}/files/avatar/{if $tsPerfil.p_avatar}{$tsPerfil.user_id}{else}avatar{/if}.jpg';
      avatar.url = '{$tsConfig.avatar}/$1_120.jpg';
      if (typeof location.href.split('#')[1] != 'undefined') {
         $('ul.menu-tab > li > a:contains('+location.href.split('#')[1]+')').click();
      }
   });
</script>
                <div class="tabbed-d">
                	<div class="floatL">
                        <ul class="menu-tab">
                            <li class="active"><a onclick="cuenta.chgtab(this)">Cuenta</a></li>
                            <li><a onclick="cuenta.chgtab(this)">Perfil</a></li>    
                            <li><a onclick="cuenta.chgtab(this)">Bloqueados</a></li>
                            <li><a onclick="cuenta.chgtab(this)">Cambiar Clave</a></li>
							<li><a onclick="cuenta.chgtab(this)">Cambiar Nick</a></li>
                            <li class="privacy"><a onclick="cuenta.chgtab(this)">Privacidad</a></li>
                        </ul>
                        <a name="alert-cuenta"></a>
                        <form class="horizontal" method="post" action="" name="editarcuenta">
                        	{include "m.cuenta_cuenta.tpl"}
                            {include "m.cuenta_perfil.tpl"}
                            {include "m.cuenta_block.tpl"}
                            {include "m.cuenta_clave.tpl"}
							{include "m.cuenta_nick.tpl"}
                            {include "m.cuenta_config.tpl"}
                        </form>
                    </div>
                    <div class="floatR">
	                    {include "m.cuenta_sidebar.tpl"}
                    </div>
                </div>
                <div style="clear:both"></div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/croppr@2.3.1/dist/croppr.min.css">
<script src="https://cdn.jsdelivr.net/npm/croppr@2.3.1/dist/croppr.min.js"></script>
<script src="{$tsConfig.js}/cuenta.js?{$smarty.now}"></script>
{include "main_footer.tpl"}
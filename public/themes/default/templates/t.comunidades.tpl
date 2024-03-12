{include "main_header.tpl"}

   {if $tsComunidades.c_id}
      <style>
         body {
            --bg-color: #fff;
            --bg-repeat: {if $tsComunidades.c_back_repeat}repeat{else}no-repeat{/if};
         }
      </style>
	{/if}
   {if $tsTema.t_estado == 1}
      <div class="com_bigmsj_red">Este tema est&aacute; eliminado</div>
   {/if}
    
   {if $tsAction == '' || $tsAction == 'home'}
    	<div class="com_left">
    		{include "c.inicio_left.tpl"}
         {include "c.inicio_center.tpl"}
        </div>
        <div class="com_right">
        	{include "c.inicio_right.tpl"}
            <br class="spacer"/>
            {include "m.global_ads_160.tpl"}
        </div>
    {elseif $tsAction == 'crear' || $tsAction == 'editar'}    
		<form action="" method="post" id="add_new_com" enctype="multipart/form-data">
            <div class="com_left">
            	{include "c.crear_left.tpl"}
            </div>
            <div class="com_right">
            	{include "c.crear_right.tpl"}
            </div>
        </form>
    {elseif $tsAction == 'agregar' || $tsAction == 'editar-tema'}
    	{include "c.agregar_tema.tpl"}
    {elseif $tsAction == 'tema'}
    	<div class="com_left">
            {include "c.com_info.tpl"}
            {include "c.tema_cuerpo.tpl"}
            {include "c.tema_comentarios.tpl"}
        </div>
        <div class="com_right">
        	{include "c.tema_autor.tpl"}
        </div>
    {elseif $tsAction == 'mis-comunidades'}
    	<div class="com_left">
            {include file='comunidades/c.mis-comunidades_left.tpl'}
        </div>
        <div class="com_right">
        </div>
    {elseif $tsAction == 'miembros'}
    	<div class="com_left">
            {include "c.miembros_left.tpl"}
        </div>
        <div class="com_right">
        	{include "c.miembros_right.tpl"}
        </div>
    {elseif $tsAction == 'dir'}
    	<div class="com_left">
	    	{include "c.directorio_left.tpl"}
        </div>
        <div class="com_right">
	    	{include "c.directorio_right.tpl"}
        </div>
    {elseif $tsAction == 'mod-history'}
	    {include "c.historial.tpl"}
    {elseif $tsAction == 'com-mod-history'}
	    {include "c.com_historial.tpl"}
    {elseif $tsAction == 'buscar'}
        <div class="com_left">
            {include "c.buscar_left.tpl"}
        </div>
        <div class="com_right">
            {include "c.buscar_right.tpl"}
        </div>
	{elseif $tsAction == 'favoritos'}
    	{if $tsFavoritos.data}
        <div class="com_left">
            {include "c.favoritos_left.tpl"}
        </div>
        <div class="com_right">
            {include "c.favoritos_right.tpl"}
        </div>
        {else}
        <div class="com_bigmsj_blue">No has agregado temas a tus favoritos a&uacute;n</div>
        <br>
        {/if}
    {elseif $tsAction == 'borradores'}
    	{if $tsBorradores.data}
        <div class="com_left">
            {include "c.borradores_left.tpl"}
        </div>
        <div class="com_right">
            {include "c.borradores_right.tpl"}
        </div>
        {else}
        <div class="com_bigmsj_blue">No tienes ning&uacute;n borrador a&uacute;n</div>
        <br>
        {/if}
    {else}
        <div class="com_left">
            {include "c.com_info.tpl"}
            {include "c.com_temas.tpl"}
        </div>
        <div class="com_right">
        	{include "c.com_right.tpl"}
        </div>
    {/if}
	<div align="center" style="opacity: 0.3;clear: both;font-size: 11px;">Secci&oacute;n comunidades creada por <a href="http://www.phpost.net/user/6266-kmario19/">Kmario19</a> para <a href="http://www.phpost.net/">PHPost</a></div>
{include "main_footer.tpl"}
{include "main_header.tpl"}

{if $tsAction == 'ayuda'}
	<div class="container my-4">
	   <div class="alert text-center border-0">Hola <u>{$tsUser->nick}</u>, S&iacute; necesitas ayuda, por favor cont&aacute;ctanos a trav&eacute;s del siguiente <a href="{$tsConfig.url}/pages/contacto/" rel="internal" alt="formulario de contacto" class="color-primary stretched-link" title="Contacto">formulario</a>.</div>
   </div>
{else}
   {include "m.pages_$tsAction.tpl"}
{/if}
       
{include "main_footer.tpl"}
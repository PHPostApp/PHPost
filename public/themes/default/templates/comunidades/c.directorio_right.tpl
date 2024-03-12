<div class="com_new_box">
    <div class="com_box_title clearfix"><h2>Comunidades por pa&iacute;s</h2></div>
    <div class="com_box_body">
    	<div class="com_list_element"><a href="{$tsConfig.url}/comunidades/dir/Internacional/">Todos los pa&iacute;ses</a></div>
        {foreach from=$tsPaises.data item=p}
        <div class="com_list_element">
            <a href="{$tsConfig.url}/comunidades/dir/{$p.c_pais}/">{$p.pais}</a>
            <span class="cle_number">{$p.total}</span>
        </div>
        {/foreach}
    </div>
</div>
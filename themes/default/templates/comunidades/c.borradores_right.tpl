<div class="com_box_title clearfix"><h2>Comunidades</h2></div>
<div class="com_box_body">
	<div class="com_list_element">
        <a href="javascript:com.borrador_filter('all')">Todas</a>
        <span class="cle_number">{$tsBorradores.total}</span>
    </div>
	{foreach from=$tsBorradores.comus item=c}
    <div class="com_list_element">
        <a href="javascript:com.borrador_filter('{$c.c_id}')">{$c.c_nombre}</a>
        <span class="cle_number">{$c.total}</span>
    </div>
    {/foreach}
</div>
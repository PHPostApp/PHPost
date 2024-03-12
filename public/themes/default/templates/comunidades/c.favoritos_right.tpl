<div class="com_box_title clearfix"><h2>Categor&iacute;as</h2></div>
<div class="com_box_body">
	<div class="com_list_element">
        <a href="javascript:com.fav_filter('all')">Todas</a>
        <span class="cle_number">{$tsFavoritos.total}</span>
    </div>
	{foreach from=$tsFavoritos.cat item=c}
    <div class="com_list_element">
        <a href="javascript:com.fav_filter('{$c.c_seo}')">{$c.c_nombre}</a>
        <span class="cle_number">{$c.total}</span>
    </div>
    {/foreach}
</div>
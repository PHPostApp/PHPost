<div class="com_new_box">
    <div class="com_box_title clearfix"><h2>&Uacute;ltimos miembros</h2></div>
    <div class="clearfix">
        {foreach from=$tsUltimos item=a}
        <a href="{$tsConfig.url}/perfil/{$a.user_name}" class="floatL hovercard" uid="{$a.m_user}" style="margin-right:1px;margin-bottom:1px;"><img src="{$tsConfig.url}/files/avatar/{$a.m_user}_50.jpg" width="35" height="35" /></a>
        {/foreach}
    </div>
</div>
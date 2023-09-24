<script>
   var action_menu = '{$tsAction}';
   $(() => {
      if(!empty(action_menu)) $('.page-' + action_menu).addClass('active');
      else $('.page-main').addClass('active');
   });
</script>
<nav class="sidebar offcanvas-start offcanvas-md" tabindex="-1" id="SidebarAdmin">
   <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
      <a class="sidebar-brand" href="{$tsConfig.url}">
      	<img src="{$tsConfig.public}/images/logo-64.png" alt="Logo" width="24" height="24" class="d-inline-block align-text-top">{$tsConfig.titulo}</a>
      <button type="button" class="btn-close d-md-none" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#SidebarAdmin"></button>
   </div>
   <div class="offcanvas-body">
      <ul class="sidebar-nav">
         {include "m.{$tsPage}_sidemenu.tpl"}
      </ul>
   </div>
</nav>
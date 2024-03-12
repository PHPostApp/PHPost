<header id="theme-header-one" class="theme-header-main header-style-one">
	<div class="theme-header-area">
		<div class="px-5">
			<div class="row align-items-center">
				<!-- Logo -->
				<div class="col-lg-2">
					<div class="logo theme-logo">
						<a href="{$tsConfig.url}" rel="internal" title="{$tsConfig.titulo}" alt="Nombre del sitio">{$tsConfig.titulo}</a>
					</div>
				</div>
				<!-- Menu -->
				<div class="col-lg-6">
					<div class="nav-menu-wrapper">
						<div class="mainmenu">
							<nav class="nav-main-wrap">
								<ul class="theme-navigation-wrap theme-main-menu">
									{if $tsConfig.c_allow_portal && $tsUser->is_member == true}
										<li><a href="{$tsConfig.url}/mi/">Portal</a></li>
									{/if}
									
									<li class="has-children">
										<a href="{$tsConfig.url}/posts/">Posts</a>
										<ul class="dropdown sub-menu">
											<li><a title="Inicio" href="{$tsConfig.url}/{if $tsPage == 'home' || $tsPage == 'posts'}posts/{/if}">Inicio</a></li>
											<li><a class=vctip title="Buscador" href="{$tsConfig.url}/buscador/">Buscador</a></li>
											{if $tsUser->is_member}
												{if $tsUser->is_admod || $tsUser->permisos.gopp}
													<li><a title="Agregar Post" href="{$tsConfig.url}/agregar/">Agregar Post</a></li>
												{/if}
												<li><a title="Historial de Moderaci&oacute;n" href="{$tsConfig.url}/mod-history/">Historial</a></li>
												{if $tsUser->is_admod || $tsUser->permisos.moacp}
													<li><a title="Panel de Moderador" href="{$tsConfig.url}/moderacion/">Moderaci&oacute;n {if $tsConfig.c_see_mod && $tsConfig.novemods.total}<span class="cadGe cadGe_{if $tsConfig.novemods.total < 10}green{elseif $tsConfig.novemods.total < 30}purple{else}red{/if}">{$tsConfig.novemods.total}</span>{/if}</a></li>
												{/if}
											{/if}
										</ul>
									</li>
									{if $tsConfig.c_fotos_private == 1 || $tsUser->is_member}
									<li class="has-children">
										<a title="Ir a Fotos" href="{$tsConfig.url}/fotos/">Fotos</a>
										<ul class="dropdown sub-menu">
											<li><a href="{$tsConfig.url}/fotos/">Inicio</a></li>
											{if $tsUser->is_admod || $tsUser->permisos.gopf}
												<li><a href="{$tsConfig.url}/fotos/agregar.php">Agregar Foto</a></li>
											{/if}
											<li><a href="{$tsConfig.url}/fotos/{$tsUser->nick}">Mis Fotos</a></li>
										</ul>
									</li>
									{/if}
									<li class="has-children">
										<a title="Ir a TOPs" href="{$tsConfig.url}/top/">Tops</a>
										<ul class="dropdown sub-menu">
										  <li><a href="{$tsConfig.url}/top/posts/">Posts</a></li>
										  <li><a href="{$tsConfig.url}/top/usuarios/">Usuarios</a></li>
										  <li><a href="{$tsConfig.url}/top/comunidades/">Comunidades</a></li>
										  <li><a href="{$tsConfig.url}/top/temas/">Temas</a></li>
										</ul>
									</li>
									<li class="has-children">
										<a title="Ir a Comunidades" href="{$tsConfig.url}/comunidades/">Comunidades</a>
										<ul class="dropdown sub-menu">
											<li><a href="{$tsConfig.url}/comunidades/">Inicio</a></li>
											{if $tsUser->is_member}
												<li><a href="{$tsConfig.url}/comunidades/mis-comunidades/">Mis Comunidades</a></li>
											{/if}
											<li><a href="{$tsConfig.url}/comunidades/dir/">Directorio</a></li>
											<li><a href="{$tsConfig.url}/comunidades/buscar/">Buscar</a></li>
											{if $tsUser->is_member}
												<li><a href="{$tsConfig.url}/comunidades/favoritos/">Favoritos</a></li>
												<li><a href="{$tsConfig.url}/comunidades/borradores/">Borradores</a></li>
											{/if}
											<li><a href="{$tsConfig.url}/comunidades/mod-history/">Historial</a></li>
										</ul>
									</li>
									{if !$tsUser->is_member}
										<li><a href="{$tsConfig.url}/login">Iniciar sesi&oacute;n</a></li>
										<li><a href="{$tsConfig.url}/registro">Crear cuenta</a></li>
									{/if}
								</ul>
							</nav>
						</div>
					</div>
				</div>
				<!-- Menu usuario -->
				<div class="col-lg-4">
					<div class="header-right-wrapper">
						<div class="header_search_wrap">
							<div class="search-icon theme-search-custom-iconn">
								<iconify-icon icon="dashicons:search"></iconify-icon>
							</div>
							<div id="theme-serach-box_Inner">
								<div class="theme-serach-box_inner_wrapper d-flex align-items-center">
									<form role="search" id="searchform" class="search-form" name="top_search_box" action="{$tsConfig.url}/buscador/">
										<div class="form-group">
                                	<input type="hidden" name="e" value="web" />
											<input type="text" class="search-input" id="popup-search" value="" name="q" placeholder="Ingrese un término de búsqueda...">
										</div>
										<button type="submit" id="serach-popup-btn-box" class="search-button submit-btn"><iconify-icon icon="dashicons:search"></iconify-icon></button>
									</form>
								</div>
							</div>
						</div>
						{if $tsUser->is_member}
						<div class="header-burger-menu">
							<div class="burger-nav-bar">
								<span class="tp-header__bars tp-menu-bar" style="cursor: pointer;"><iconify-icon icon="ri:menu-fill"></iconify-icon></span>
							</div>
						</div>
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!-- tp-offcanvus-area-start -->
<div class="tp-offcanvas-area">
	<div class="tpoffcanvas">
		<div class="tpoffcanvas__close-btn">
			<button class="close-btn"><iconify-icon icon="ci:close-md"></iconify-icon></button>
		</div>
		{if $tsUser->is_member}
		<div class="tpoffcanvas__logo offcanvas-logo mb-3">
			<a href="{$tsConfig.url}/perfil/{$tsUser->info.user_name}" class="d-block text-center">
				<img src="{$tsConfig.avatar}/{$tsUser->uid}_120.jpg?{$smarty.now}" class="image rounded-circle mb-2" alt="Logo">
				<h3 class="py-2">{$tsUser->nick}</h3>
				<small class="d-block fw-bold py-2 text-uppercase">{$tsUser->email}</small>
			</a>
		</div>
		{/if}
		<div class="tpoffcanvas__text offcanvas-content">
			<div class="main-canvas-inner">
				{if $tsUser->is_member}
					<div class="item-canvas">
						<a href="{$tsConfig.url}/perfil/{$tsUser->info.user_name}" class="fs-4">Mi perfil</a>
					</div>
					<div class="item-canvas">
						<a href="{$tsConfig.url}/cuenta/" class="fs-4">Mi cuenta</a>
					</div>
					<div class="item-canvas">
						<a href="{$tsConfig.url}/monitor/" class="fs-4">Notificaciones</a>
						<span id="alerta_avs" class="alertas fs-4" title="{$tsNots} notificacion{if $tsNots != 1}es{/if}">{$tsNots}</span>
					</div>
					<div class="item-canvas">
						<a href="{$tsConfig.url}/mensajes/" class="fs-4">Mensajes</a>
						<span id="alerta_avs" class="alertas fs-4" title="{$tsMPs} mensaje{if $tsMPs != 1}s{/if}">{$tsMPs}</span>
					</div>
					{if $tsAvisos}
						<div class="item-canvas">
							<a href="{$tsConfig.url}/mensajes/avisos/" class="fs-4">Avisos</a>
							<span id="alerta_avs" class="alertas fs-4" title="{$tsAvisos} aviso{if $tsAvisos != 1}s{/if}">{$tsAvisos}</span>
						</div>
					{/if}
					<div class="item-canvas">
						<a href="{$tsConfig.url}/favoritos.php" class="fs-4">Mis Favoritos</a>
					</div>
					<div class="item-canvas">
						<a href="{$tsConfig.url}/borradores.php" class="fs-4">Mis Borradores</a>
					</div>
					{if $tsUser->is_admod == 1}
						<div class="item-canvas">
							<a href="{$tsConfig.url}/admin/" class="fs-4">Administraci&oacute;n</a>
						</div>
					{/if}
					<div class="item-canvas">
						<a href="{$tsConfig.url}/login-salir.php" class="fs-4">Cerrar sesión</a>
					</div>
				{/if}

			</div>
			<div class="mobile-canvas-content">
				<div class="canvas-nav-menu-wrapper"></div>
			</div>
		</div>
	</div>
</div>
<div class="body-overlay"></div>
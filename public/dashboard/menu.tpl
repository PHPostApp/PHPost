<nav class="navbar navbar-expand-md navbar-admin">
  	<div class="container-fluid">
    	<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#MenuUsuario" aria-controls="MenuUsuario" aria-expanded="false" aria-label="Toggle navigation">
      	<span class="navbar-toggler-icon"></span>
    	</button>
		<a href="#SidebarAdmin" role="button" class="btn btn-primary" data-bs-toggle="offcanvas">→</a>
    	<div class="collapse navbar-collapse" id="MenuUsuario">
      	<ul class="navbar-nav ms-auto">
	        	<li class="nav-item dropdown">
	          	<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">{$tsUser->nick}</a>
	          	<ul class="dropdown-menu dropdown-menu-end mt-md-2 rounded-top-0">
	            	<li><a class="dropdown-item" href="{$tsConfig.url}/perfil/{$tsUser->nick}">Mi perfil</a></li>
	            	<li><a class="dropdown-item" href="{$tsConfig.url}/cuenta/">Cuenta</a></li>
	            	<li><hr class="dropdown-divider"></li>
	            	<li><a class="dropdown-item" href="{$tsConfig.url}/login-salir.php">Cerrar sesión</a></li>
	          	</ul>
	        	</li>
	      </ul>
    	</div>
  	</div>
</nav>
<?php

/**
 * @name install.php
 * @author Miguel92 & PHPost.es
 * @copyright 2011-2024
 */

require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "config_path.php";
require_once TS_EXTRA . "install.fn.php";

error_reporting(1);
session_start();
//
$step = (int)$_GET['step'] ?? 0;
$next = true; // CONTINUAR

$Install = new Installer;

if(file_exists(TS_LOCK)) header("Location: ../");

switch ($step) {
	case 0:
		$Install->folderFilePerms();
		$_SESSION['license'] = false;
	break;
	// OBTENER PERMISOS
	case 1:
		if (isset($_POST['license'])) {
			$verify = [
				"config" => str_replace(TS_ROOT, TS_PATH, TS_CONFIG),
				"cache" => str_replace(TS_ROOT, TS_PATH, TS_CACHE),
				"avatar" => str_replace(TS_ROOT, TS_PATH, TS_AVATAR),
				"uploads" => str_replace(TS_ROOT, TS_PATH, TS_UPLOADS)
			];
			foreach ($verify as $key => $val) {
				$rutaVal = ".." . TS_PATH . ".." . $val;
				$permisos[$key]['chmod'] = (int)substr(sprintf('%o', fileperms($rutaVal)), -3);
				$permisos[$key]['css'] = 'OK';
				$permisos[$key]['route'] = $val;
				if ($key === 'config' && $permisos[$key]['chmod'] != 666) {
					$permisos[$key]['css'] = 'NO';
					$next = false;
				} elseif ($key != 'config' && $permisos[$key]['chmod'] != 777) {
					$permisos[$key]['css'] = 'NO';
					$next = false;
				}
			}
			$vphp = '7.4.33';
			$statusPHP = version_compare(PHP_VERSION, $vphp, '>=');
			$versiones['php']['message'] = $statusPHP ? "Compatible desde PHP $vphp" : "Tu versión es inferior a PHP $vphp";
			$versiones['php']['status'] = $statusPHP;

			require_once TS_SMARTY . 'Smarty.class.php';
			$versiones['smarty']['message'] = "Versión: " . Smarty::SMARTY_VERSION;
			$versiones['smarty']['status'] = true;

			$statusGD = (extension_loaded('gd') || function_exists('gd_info'));
			$versiones['gd']['message'] = $statusGD ? "Versión: " . gd_info()['GD Version'] : "La extensión GD no está habilitada!";
			$versiones['gd']['status'] = $statusGD;

			$statusCurl = (extension_loaded('curl'));
			$versiones['curl']['message'] = $statusCurl ? "Versión: " . curl_version()['version'] : "La extensión cURL no está habilitada!";
			$versiones['curl']['status'] = $statusCurl;

			$_SESSION['license'] = false;
			if($statusGD && $statusCurl && $next) {
				$next = true;
				$_SESSION['license'] = true;
			}
		} else header("Location: ./");
	break;
	// Comprobando...
	case 2:
		// No saltar la licensia
		if (!$_SESSION['license']) header("Location: ./");
		// Step
		$next = false;
		if (isset($_POST['save'])) {
         // Con esto evitamos escribir todos los campos
         foreach ($_POST['db'] as $key => $val) $db[$key] =  htmlspecialchars($val ?? '');

         $mysqli = $Install->conn($db, $tsMessage, $next);
			if($next) {
				$Install->save($db);
				# Para evitar problemas obtenemos todas las tablas y eliminamos (solo si existen)
		     	if ($results = $mysqli->query("SHOW TABLES")) {
		     	   while ($row = $results->fetch_row()) {
		     	      $mysqli->query("DROP TABLE IF EXISTS {$row[0]}");
		     	   }
		     	   $results->close();
		     	} else {
		     	   $tsMessage = "Error en la consulta: " . $mysqli->error;
		     	}
		     	# INSTALAMOS LA NUEVA BASE DE DATOS
				include_once DATABASE;
				$error = '';
				foreach ($phpost_sql as $key => $sentencia) {
					if ($mysqli->query($sentencia)) $exe[$key] = 1;
					else {
						$exe[$key] = 0;
						$error .= '<br/>' . $mysqli->error;
					}
				}
				$mysqli->close();
				if (!in_array(0, $exe)) header("Location: ./?step=" . ($step + 1));
				else {
					$tsMessage = 'Lo sentimos, pero ocurrió un problema. Inténtalo nuevamente; borra las tablas que se hayan guardado en tu base de datos: ' . $error;
				}
			}
		}
	break;
	// DATOS DEL SITIO
	case 3:
		// No saltar la licencia
		if (!$_SESSION['license']) header("Location: ./");
		$next = false;
		if (isset($_POST['save'])) {
         // Con esto evitamos escribir todos los campos
         foreach($_POST['web'] as $key => $val) $web[$key] = htmlspecialchars($val);
			// Verificamos que todos los campos esten llenos
         if (in_array('', $web)) $message = 'Todos los campos son requeridos';
			else {
				define('TS_HEADER', true);
				// DATOS DE CONEXION
				require_once TS_CONFIG;
            // CONECTAMOS
            $mysqli = $Install->conn($db, $tsMessage, $next);
				$version = SCRIPT_NAME_VERSION;
				$version_code = SCRIPT_VERSION_CODE;
            //
            $results = $mysqli->query("SELECT user_id FROM u_miembros WHERE user_id = 1 AND user_rango = 1");
				if($db['hostname'] === 'dbhost' OR $results->num_rows > 0) {
					$tsMessage = 'Vuelva al paso anterior, no se han guardado los datos de acceso correctamente.';
					$next = false;
				}
				// Cambia el nombre de la categoría Taringa! por el del sitio web creado
            require_once TS_PLUGINS. "modifier.seo.php";
            $name = $mysqli->real_escape_string($web['name']);
				$seo = smarty_modifier_seo($name);
				# ACTUALIZAMOS LA CATEGORÍA N°30
				$mysqli->query("UPDATE `p_categorias` SET c_nombre = '$catename', c_seo = '$cateseo' WHERE cid = 30 LIMIT 1");
            // Insertamos en w_temas
            $copy = 'Miguel92 &copy; ' . date('Y');
            $mysqli->query("INSERT INTO w_temas VALUES(1, '$version', '{$web['url']}/themes/default', 'default', '$copy')");
				// SEO TITLE
				$seoTitle = "{$web['name']} - {$web['slogan']}";
				// SEO DESCRIPTION
				$seoDecription = "Únete a nuestra comunidad para compartir experiencias y conocer gente nueva. ¡Conéctate hoy mismo!";
				// SEO KEYWORDS
				$seoKeys = "comunidad, conocer, red, ampliar, interaccion, compartir, amigos, conectar, relaciones, intereses, encuentros, virtual, actualizada, mejorada";
				// SEO IMAGES
				$seoFavicon = $web['url'] . '/assets/images/logos/logo_16.webp';
				$seoPortada = $web['url'] . '/assets/images/logos/logo_512.webp';
				$seoImages = json_encode([
					'16' => $seoFavicon,
					'32' => $web['url'] . '/assets/images/logos/logo_32.webp',
					'64' => $web['url'] . '/assets/images/logos/logo_64.webp'
				], JSON_FORCE_OBJECT);
				$mysqli->query("UPDATE `w_site_seo` SET seo_titulo = '$seoTitle', seo_descripcion = '$seoDecription', seo_portada = '$seoPortada', seo_favicon = '$seoFavicon', seo_keywords = '$seoKeys', seo_images = '$seoImages', seo_robots = 0, seo_sitemap = 0 WHERE seo_id = 1");
				// Publicidad
				$alt = "Script para ZCode";
				$github = "https://scriptparaphpost.github.io/grupos/";
				$tamanos = ['160x600','300x250','468x60','728x90'];
				foreach($tamanos as $tamano) {
					$size = explode('x', $tamano);
					$html = "<a href=\"$github\" target=\"_blank\" style=\"display:block;\"><img loading=\"lazy\" alt=\"Publicidad de $tamano\" title=\"$alt\" width=\"{$size[0]}\" height=\"{$size[1]}\" src=\"{$web['url']}/assets/images/ad$tamano.webp\"></a>";
					$insert[] = "ads_".substr($tamano, 0, 3)." = '".html_entity_decode($html)."'";
				}
				$publicidades = join(',', $insert);
				// UPDATE
				if ($mysqli->query("UPDATE w_configuracion SET 
					titulo = '{$web['name']}', 
					slogan = '{$web['slogan']}', 
					url = '{$web['url']}', 
					email = '{$web['mail']}', 
					$publicidades, 
					version = '$version', 
					version_code = '$version_code', 
					pkey = '{$web['pkey']}', 
					skey = '{$web['skey']}' WHERE tscript_id = 1")) {
					$mysqli->close();
					header("Location: ./?step=" . ($step + 1));
				} else $tsMessage = $mysqli->error;
			}
		}
	break;
	// ADMINISTRADOR
	case 4:
		// No saltar la licencia
		if (!$_SESSION['license']) header("Location: ./");

		// Step
		$next = false;
		if (isset($_POST['save'])) {
         // Con esto evitamos escribir todos los campos
         foreach ($_POST['user'] as $key => $val) $user[$key] = htmlspecialchars($val);
			// Evitamos que los campos esten vacios
         if(in_array('', $user)) {
         	$tsMessage = 'Todos los campos son requeridos';
         	$next = false;
         } else {
         	if(!ctype_alnum($user['name'])) {
               $tsMessage = 'Introduzca un nombre de usuario alfanum&eacute;rico';
               $next = false;
         	}
            //
            if(!filter_var($user['mail'], FILTER_VALIDATE_EMAIL)) {
               $tsMessage = 'Introduzca un email correcto.';
               $next = false;
            }
            //
            if($user['pass'] !== $user['passc']) {
               $tsMessage = 'Las contrase&ntilde;as no coinciden.';
               $next = false;
            }
            // Generamos una nueva contraseña más segura
            $time = time();
				// DATOS DE CONEXION
				define('TS_HEADER', true);
				require_once TS_CONFIG;
            // CONECTAMOS
            $mysqli = $Install->conn($db, $tsMessage, $next);
            // Creamos contraseña
            $key = $Install->createPassword($user['name'], $user['passc']);
           
            //COMPROBAMOS QUE NO HAYA ADMINISTRADORES Y/O EL PRIMER USUARIO REGISTRADO
            if($mysqli->query("SELECT user_id FROM u_miembros WHERE user_id = 1 OR user_rango = 1 LIMIT 1")->num_rows > 0) {
               $tsMessage = 'No se puede registrar, ya existe un administrador.';
               $body = "<html><head></head><body><h2>Un lammer ha entrado a su instalador.</h2><br><p><strong>Sitio web:</strong> {$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}<br> <strong>Usuario:</strong> {$user['name']}<br><strong>Password:</strong> {$user['pass']}<br><strong>Email:</strong> {$user['mail']}<br> <strong>Dirección IP:</strong> {$_SERVER['REMOTE_ADDR']}</p></body></html>";
               mail('isidro@phpost.net', 'Lammer detectado', $body, 'Content-type: text/html; charset=iso-8859-15');
            } else {
               //INSERTAMOS AL FUNDADOR DE LA WEB
					$mysqli->query("INSERT INTO u_miembros (user_name, user_password, user_email, user_rango, user_registro, user_puntosxdar, user_activo) VALUES ('{$user['name']}', '$key', '{$user['mail']}', 1, $time, 50, 1)");
					$uid = (int)$mysqli->insert_id;
               // DEMAS TABLAS
               $avatar = "https://ui-avatars.com/api/?name={$user['name']}&background=D6030B&color=fff&size=$1&font-size=0.50&bold=false&length=2";
               foreach ([50, 120] as $size) {
                  copy(
                  	str_replace('$1', $size, $avatar), 
                  	TS_AVATAR . "{$uid}_$size.jpg"
                  );
               }
               $mysqli->query("INSERT INTO u_perfil (user_id, p_avatar) VALUES ($uid, 1)");
               $mysqli->query("INSERT INTO u_portal (user_id) VALUES ($uid)");
               // UPDATE
               $mysqli->query("UPDATE p_posts SET post_user = $uid, post_category = 30, post_date = $time WHERE post_id = 1");
               $mysqli->query("UPDATE w_stats SET stats_time_foundation = $time, stats_time_upgrade = $time WHERE stats_no = 1");
               // DAMOS BIENVENIDA POR CORREO
	            $body = "<html><head></head><body><h2>Su nueva comunidad Link Sharing est&aacute; lista!</h2><br>	<p>Estas son sus credenciales de acceso:<br><strong>Usuario:</strong> {$user['name']}<br><strong>Contrase&ntilde;a:</strong> {$user['pass']}</p><br><hr><br><p>Gracias por usar <a href=\"https://phpost.es\">PHPost</a> para compartir enlaces :)</p></body></html>";
	            mail($user['email'], 'Su comunidad ya puede ser usada', $body, 'Content-type: text/html; charset=iso-8859-15');
	            $mysqli->close();
	            header("Location: ./?step=" . ($step + 1) . "?uid=$uid");
         	}
      	}
      }
   break;
	case 5:
		// No saltar la licensia
		if (!$_SESSION['license']) header("Location: ./");
		// DATOS DE CONEXION
		define('TS_HEADER', true);
		require_once TS_CONFIG;
     	// CONECTAMOS
     	$mysqli = $Install->conn($db, $tsMessage, $next);
		//
		$data = $mysqli->query("SELECT titulo, slogan, url, version_code FROM w_configuracion WHERE tscript_id = 1")->fetch_assoc();
		if (isset($_POST['save'])) header("Location: {$data['url']}");
		// CONSULTA
		$time = time();
		$uid = (int)$_GET['uid'];
		$user = $mysqli->query("SELECT user_id, user_name FROM u_miembros WHERE user_id = $uid")->fetch_assoc();
		// ESTADISTICAS
	   $code = [
	      'title' => $data['titulo'], 
	      'url' => $data['url'], 
	      'version' => SCRIPT_NAME_VERSION, 
	      'admin' => $user['user_name'], 
	      'id' => $user['user_id']
	   ];
	   $key = base64_encode(serialize($code));
	   $key .= '&verification=' . base64_encode("{$data['url']} - " . SCRIPT_VERSION . " - " . SCRIPT_KEY);
		#$tsAction = $data['url'];
		$tsAction = "https://zcode.newluckies.com/feed/?from=" . SCRIPT_NAME . "&type=install&key=$key";
		// Abrir el archivo en modo de escritura ("w")
	   $handle = fopen(TS_LOCK, "w");
	   // Escribir los datos en el archivo
	   fwrite($handle, $key);
	   // Cerrar el archivo
	   fclose($handle);
	   $mysqli->close();
		
	break;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="Miguel92 & PHPost <?= SCRIPT_VERSION ?>" />
<title>Instalaci&oacute;n de <?= SCRIPT_NAME_VERSION ?></title>
<link rel="icon" href="<?=$Install->getURL('assets/images')?>/logos/logo_16.webp" type="image/webp">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="<?= $Install->getStyleInstall() ?>" type="text/css" />
</head>
<body>

	<main class="container">
		<header class="d-flex justify-content-between align-items-center p-3 border-bottom">
			<a href="https://www.phpost.es" rel="noreferrer" target="_blank">
				<img src="<?=$Install->getURL('assets/images')?>/logo.png" alt="logo <?= SCRIPT_NAME_VERSION ?>" title="Instalaci&oacute;n de <?= SCRIPT_NAME_VERSION ?>" />
			</a>
			<h3 class="m-0 fs-5 text-end">Programa de instalaci&oacute;n: <strong class="d-block"><?= SCRIPT_NAME_VERSION ?></strong></h3>
		</header>
		<section class="d-grid gap-3 p-3" style="grid-template-columns: 300px 1fr;">
			<aside class="mt-3">
				<ul class="nav nav-pills flex-column">
					<?php 
						$active = ' bg-success text-white fw-semibold';
						$off = ' disabled';
						$navbar = [
							'Licencia', 
							'Permisos de escritura',
							'Base de datos',
							'Datos de la web',
							'Administrador',
							'Bienvenido'
						];
						foreach($navbar as $key => $nav):
					?>
						<li class="nav-item mb-3"><span class="nav-link<?=($step >= $key ? $active : $off)?>"><?=$nav?></span></li>
					<?php endforeach; ?>
				</ul>
			</aside>
			<section>
				<?php if ($step == 0) { ?>
					<form action="./<?=($next ? '?step=1' : '')?>" method="post">
				  		<fieldset>
							<legend>Licencia</legend>
							<p>Para utilizar <strong><?= SCRIPT_NAME_VERSION ?></strong> debes estar de acuerdo con nuestra licencia de uso.</p>
							<textarea name="license" class="form-control"><?= LICENSE ?></textarea>
							<p class="text-center"><input type="submit" class="btn btn-primary mt-3" value="Acepto"/></p>
				  		</fieldset>
				  	</form>
				<?php } elseif ($step == 1) { ?>
					<form action="./<?=($next ? '?step=2' : '')?>" method="post">
					  	<fieldset>
					  		<div class="row">
					  			<div class="col-12 col-lg-6">
					  				<legend>Permisos de escritura</legend>
									<p>Los siguientes archivos y directorios requieren de permisos especiales, debes cambiarlos desde tu cliente FTP, los archivos deben tener permiso <strong>666</strong> y los direcorios <strong>777</strong></p>
									<?php foreach($permisos as $nombre => $permiso): ?>
										<div class="mb-1 p-2 rounded border border-<?= strtolower($permiso['css']) ?>">
											<h5 class="fs-6 mb-0 d-flex justify-content-between align-items-center"><strong class="text-capitalize"><?= $nombre ?></strong> <span class="status badge badge-<?= strtolower($permiso['css']) ?>"><?= $permiso['css'] ?></span></h5>
											<code>..<?= $permiso['route'] ?></code>
										</div>
									<?php endforeach; ?>
					  			</div>
					  			<div class="col-12 col-lg-6">
									<legend>Verificaciones del sistema</legend>
									<p>Las siguientes verificaciones son necesarias para el correcto funcionamiento del script, ya que este puede hacer que funcione de una manera no deseada</p>
									<?php foreach($versiones as $nombre => $version_actual): ?>
										<div class="mb-1 p-2 rounded border border-<?= ($version_actual['status'] ? 'ok' : 'no') ?>">
											<h5 class="fs-6 mb-0 d-flex justify-content-between align-items-center"><strong class="text-uppercase"><?= $nombre ?></strong> <span class="status badge badge-<?= ($version_actual['status'] ? 'ok' : 'no') ?>"><?= ($version_actual['status'] ? 'OK' : 'NO') ?></span></h5>
											<code><?= $version_actual['message'] ?></code>
										</div>
									<?php endforeach; ?>
					  			</div>
					  		</div>
									
							<p class="text-center"><input type="submit" class="btn btn-primary mt-3" value="<?=($next ? 'Continuar &raquo;' : 'Volver a verificar')?>"/></p>
						</fieldset>
					</form>
				<?php } elseif ($step == 2) { ?>
					<form action="./?step=<?=($next ? 3 : 2)?>" method="post">
					  	<fieldset>
							<legend>Base de datos</legend>
							<p>Ingresa tus datos de conexi&oacute;n a la base de datos.</p>
							<?=(isset($tsMessage) ? "<div class=\"alert alert-danger\">$tsMessage</div>" : "")?>
							<div class="row">
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="text" class="form-control" id="servername" name="db[hostname]" placeholder="localhost" value="<?=($db['hostname'] ?? '')?>" required>
									  	<label for="servername">Servidor</label>
									  	<div class="form-text" id="servername">Donde est&aacute; la base de datos, ej: <strong>localhost</strong></div>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="text" class="form-control" id="username" name="db[username]" placeholder="root" value="<?=($db['username'] ?? '')?>" required>
									  	<label for="username">Usuario</label>
									  	<div class="form-text" id="username">El usuario de tu base de datos.</div>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="password" class="form-control" id="password" name="db[password]" placeholder="" value="<?=($db['password'] ?? '')?>">
									  	<label for="password">Contrase&ntilde;a</label>
									  	<div class="form-text" id="password">Para acceder a la base de datos.</div>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="text" class="form-control" id="database" name="db[database]" placeholder="root" value="<?=($db['database'] ?? '')?>" required>
									  	<label for="database">Base de datos</label>
									  	<div class="form-text" id="database">Nombre de la base de datos para tu web.</div>
									</div>
								</div>
							</div>
							<p class="text-center"><input type="submit" class="btn btn-primary mt-3" name="save" value="Continuar &raquo;"/></p>
					  	</fieldset>
					</form>
				<?php } elseif ($step == 3) { ?>
					<form action="./?step=<?=($next ? 4 : 3)?>" method="post" class="mb-5">
					  	<fieldset>
							<legend>Datos del sitio</legend>
							<?=(isset($tsMessage) ? "<div class=\"alert alert-danger\">$tsMessage</div>" : "")?>
							<div class="row">
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="text" class="form-control" id="sitename" name="web[name]" placeholder="PHPost" value="<?=($web['name'] ?? '')?>" required>
									  	<label for="sitename">Nombre</label>
									  	<div class="form-text" id="sitename">El t&iacute;tulo de tu web</div>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="text" class="form-control" id="siteslogan" name="web[slogan]" placeholder="Inteligencia renovada" value="<?=($web['slogan'] ?? '')?>" required>
									  	<label for="siteslogan">Slogan</label>
									  	<div class="form-text" id="siteslogan">Una breve descripción</div>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="url" class="form-control" id="siteurl" name="web[url]" placeholder="Inteligencia renovada" value="<?=($web['url'] ?? $Install->getURL('./'))?>" required>
									  	<label for="siteurl">Direcci&oacute;n</label>
									  	<div class="form-text" id="siteurl">Ingresa la url donde  est&aacute; alojada tu web, sin la &uacute;ltima diagonal <strong>/</strong></div>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="email" class="form-control" id="sitemail" name="web[mail]" placeholder="example@server.com" value="<?=($web['mail'] ?? '')?>" required>
									  	<label for="sitemail">Email</label>
									  	<div class="form-text" id="sitemail">Email de la web o del administrador</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 mb-3">
									<h5 class="m-0">Datos de reCAPTCHA</h5>
									<p class="d-block m-0 small">Obtén tu clave desde <a href="https://www.google.com/recaptcha/admin" target="_blank" class="text-decoration-none fw-semibold text-primary">google.com/recaptcha/admin</a></p>
								</div>
								<div class="col-6 mb-3">
									<div class="form-floating">
									  	<input type="text" class="form-control" id="sitepkey" name="web[pkey]" placeholder="key" value="<?=($web['pkey'] ?? '')?>" required>
									  	<label for="sitepkey">Clave pública del sitio</label>
									</div>
								</div>
								<div class="col-6 mb-3">
									<div class="form-floating">
									  	<input type="text" class="form-control" id="siteskey" name="web[skey]" placeholder="key" value="<?=($web['skey'] ?? '')?>" required>
									  	<label for="siteskey">Clave secreta</label>
									</div>
								</div>
							</div>
							<p class="text-center"><input type="submit" name="save" class="btn btn-primary mt-3" value="Continuar &raquo;"/></p>
					  </fieldset>
					</form>
					<br>
				<?php } elseif ($step == 4) { ?>
					<form action="./?step=<?=($next ? 5 : 4)?>" method="post">
					  	<fieldset>
							<legend>Administrador</legend>
							<?=(isset($tsMessage) ? "<div class=\"alert alert-danger\">$tsMessage</div>" : "")?>
							<div class="row">
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="text" class="form-control" id="username" name="user[name]" placeholder="JhonDoe" value="<?=($user['name'] ?? '')?>" required>
									  	<label for="username">Nombre de usuario</label>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="password" class="form-control" id="userpass" name="user[pass]" placeholder="123456" value="<?=($user['pass'] ?? '')?>" required>
									  	<label for="userpass">Contrase&ntilde;a</label>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="password" class="form-control" id="userpassc" name="user[passc]" placeholder="123456" value="<?=($user['passc'] ?? '')?>" required>
									  	<label for="userpassc">Confirmar contrase&ntilde;a</label>
									</div>
								</div>
								<div class="col-6 mb-3 p-3">
									<div class="form-floating">
									  	<input type="email" class="form-control" id="usermail" name="user[mail]" placeholder="123456" value="<?=($user['mail'] ?? '')?>" required>
									  	<label for="usermail">Email</label>
									</div>
								</div>
							</div>

							<div class="alert alert-info">Ingresa tus datos de usuario, m&aacute;s adelante puedes editar tu cuenta!.</div>
							<p class="text-center"><input type="submit" name="save" class="btn btn-primary mt-3" value="Continuar &raquo;"/></p>
					  	</fieldset>
					</form>
				<?php } elseif ($step == 5) {?>
					<h2 class="s16">Bienvenido a <?= SCRIPT_NAME_VERSION ?></h2>
					<!-- ESTADISTICAS -->
					<form action="<?=$tsAction?>" method="post">
					  	<div class="alert alert-danger">Ingresa a tu FTP y borra la carpeta <strong><?php echo basename(getcwd()); ?></strong> antes de usar el script.</div>
					  	<fieldset>
							<p>Gracias por instalar <strong><?= SCRIPT_NAME_VERSION ?></strong>, ya est&aacute; lista tu nueva comunidad <strong>Link Sharing System</strong>. S&oacute;lo inicia sesi&oacute;n con tus datos y comienza a disfrutar. Ahora no dejes de <a href="https://www.phpost.es" target="_blank"><u>visitarnos</u></a> para estar pendiente de futuras actualizaciones. Recuerda reportar cualquier bug que encuentres, de esta manera todos ganamos.</p><br>
					  	</fieldset>
					  	<center>
							<input type="hidden" name="key" value="<?=$key?>" />
							<i class="text-center"nput type="submit" value="Finalizar" class="btn btn-primary mt-3"  />
					  	</center>
					</form>
				<?php } ?>
			</section>
		</section>
		<footer class="pt-3 mt-3 border-top text-center position-fixed bottom-0 w-100 start-0 bg-white shadow z-3">
			<p class="d-block mb-0">Powered by <a href="https://www.phpost.es" target="_blank" class="text-primary fw-semibold text-decoration-none"><?= SCRIPT_NAME_VERSION ?></a></p>
			<div class="bottom small">
				<p>Sumate a nuestro servidor:
					<a href="https://discord.gg/mx25MxAwRe" target="_blank" class="text-primary fw-semibold text-decoration-none">Discord</a>
				</p>
			</div>
			<!-- Actualizado <span class="text-primary fw-semibold">Miguel92</span> -->
		</footer>
	</main>

</body>
</html>
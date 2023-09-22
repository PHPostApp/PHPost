<?php

/**
 * @name install.php
 * @author PHPost Team
 * @copyright 2011-2023
 */

define('DS', DIRECTORY_SEPARATOR);
define('SCRIPT_ROOT', realpath('../') . DS);
define('INSTALL_ROOT', realpath('./') . DS);
define('CONFIGINC', SCRIPT_ROOT . 'config.inc.php');
define('CONFIGINC2', INSTALL_ROOT . 'config.copy.php');
define('LICENSE', SCRIPT_ROOT . 'license.txt');
define('BLOCKED', SCRIPT_ROOT . '.lock');

error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
session_start();
//
$version_id = "1.3.006";
$version_title = "Risus $version_id";
$wversion_code = str_replace([' ', '.'], '_', strtolower($version_title));

$step = empty($_GET['step']) ? 0 : $_GET['step'];
$step = htmlspecialchars(intval($step));
$next = true; // CONTINUAR

$theme = [
	'tid' => 1, 
	't_name' => 'PHPost', 
	't_url' => '/themes/default', 
	't_path' => 'default', 
	't_copy' => 'PHPost & Miguel92'
];

// Intento de sistema de dirección automática
$ssl = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
   $ssl = 'https';
}
$local = dirname(dirname($_SERVER["REQUEST_URI"]));
// Creando las url base e install
$url = "$ssl://" . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . $local;
$base_install = $url . "/install";

require_once INSTALL_ROOT . "functions.php";

if(file_exists(BLOCKED)) header("Location: ../");

switch ($step) {
	case 0:
		// Copiamos el archivo a la ruta del sitio
		if(!file_exists(CONFIGINC)) {
			copy(CONFIGINC2, CONFIGINC);
			// Forzamos los permisos
			chmod(CONFIGINC, 0666);
		}
		// Creamos la carpeta en caso que no exista!
		if(!is_dir(SCRIPT_ROOT . 'cache')) {
			mkdir(SCRIPT_ROOT . 'cache', 0777);
			// Forzamos los permisos
			chmod(SCRIPT_ROOT . 'cache', 0777);
		}
		// Forzamos los permisos
		chmod(SCRIPT_ROOT . 'files' . DS . 'avatar', 0777);
		chmod(SCRIPT_ROOT . 'files' . DS . 'uploads', 0777);
		$_SESSION['license'] = false;
		$license = file_get_contents(LICENSE);
	break;
	// OBTENER PERMISOS
	case 1:
		if ($_POST['license']) {
			$all = [
				"config" => '../config.inc.php',
				"cache" => '../cache/',
				"avatar" => '../files/avatar/',
				"uploads" => '../files/uploads/'
			];
			foreach ($all as $key => $val) {
				$permisos[$key]['chmod'] = (int)substr(sprintf('%o', fileperms($val)), -3);
				$permisos[$key]['css'] = 'OK';
				if ($key === 'config' && $permisos[$key]['chmod'] != 666) {
					$permisos[$key]['css'] = 'NO';
					$next = false;
				} elseif ($key != 'config' && $permisos[$key]['chmod'] != 777) {
					$permisos[$key]['css'] = 'NO';
					$next = false;
				}
			}
			$_SESSION['license'] = true;
		} else header("Location: index.php");
	break;
	// Comprobando...
	case 2:
		// No saltar la licensia
		if (!$_SESSION['license']) header("Location: index.php");
	
		$compare = version_compare(PHP_VERSION, '7.0.0', '>');
		$all = [
			'php' => [
				'name' => 'PHP', 
				'status' => PHP_VERSION,
				'css' => $compare ? 'ok' :'no'
			],
			'gd' => [
				'name' => 'Extensión GD',
				'status' => $extension->loaderGD('message'),
				'css' => $extension->loaderGD('status') ? 'ok' :'no'
			],
			'curl' => [
				'name' => 'Extensión cURL',
				'status' => $extension->loaderCURL('message'),
				'css' => $extension->loaderCURL('status') ? 'ok' :'no'
			],
			'openssl' => [
				'name' => 'Extensión OpenSSL',
				'status' => $extension->loaderOpenSSL('message'),
				'css' => $extension->loaderOpenSSL('status') ? 'ok' :'no'
			]
		];

		$_SESSION['license'] = true;
	break;
	// COMPROBAR BASE DE DATOS
	case 3:
		// No saltar la licensia
		if (!$_SESSION['license']) header("Location: index.php");
		// Step
		$next = false;
		if (isset($_POST['save'])) {
         // Con esto evitamos escribir todos los campos
         foreach ($_POST['db'] as $key => $val) 
         	$db[$key] = empty($val) ? '' : htmlspecialchars($val);
			// CONECTAMOS
			$db_link = mysqli_connect($db['hostname'], $db['username'], $db['password'], $db['database']);
			// NO SE PUDO CONECTAR?
         $database->db = $db;
         $database->db_link = $database->conn();
			if (empty($database->db_link)) {
				$message = 'Tus datos de conexi&oacute;n son incorrectos.';
				$next = false;
			} else {
				$database->setNames();
				// GUARDAR LOS DATOS DE CONEXION
				$config = file_get_contents(CONFIGINC);
				$config = str_replace(['dbhost', 'dbuser', 'dbpass', 'dbname'], $db, $config);
				file_put_contents(CONFIGINC, $config);
            // ELIMINAMOS LAS TABLAS QUE EXISTAN EN LA BASE
            $result = $database->query("SHOW TABLES");
            while ($row = $result->fetch_row()) $database->query("DROP TABLE {$row[0]}");
				// INSERTAR DB
				require_once INSTALL_ROOT . 'database.php';
				$bderror = '';
				foreach ($phpost_sql as $tbl => $sentecia) {
					if ($database->query($sentecia)) $exe[$tbl] = 1;
					else {
						$exe[$tbl] = 0;
						$bderror .= '<br/>' . mysqli_error($db_link);
					}
				}
				if (!in_array(0, $exe)) header("Location: index.php?step=4");
				else {
					$message = 'Lo sentimos, pero ocurrió un problema. Inténtalo nuevamente; borra las tablas que se hayan guardado en tu base de datos: ' . $bderror;
				}
			}
		}
	break;
	// DATOS DEL SITIO
	case 4:
		// No saltar la licensia
		if (!$_SESSION['license']) header("Location: index.php");
		$next = false;
		if ($_POST['save']) {
         // Con esto evitamos escribir todos los campos
         foreach($_POST['web'] as $key => $val) $web[$key] = htmlspecialchars($val);
			// Verificamos que todos los campos esten llenos
         if (in_array('', $web)) $message = 'Todos los campos son requeridos';
			else {
				define('TS_HEADER', true);
				// DATOS DE CONEXION
				require_once CONFIGINC;
            // CONECTAMOS
            $database->db = $db;
            $database->db_link = $database->conn();
            $database->setNames();
            //
            if ($db['hostname'] === 'dbhost' OR $database->num_rows('SELECT user_id FROM u_miembros WHERE user_id = 1 || user_rango = 1')) $message = 'Vuelva al paso anterior, no se han guardado los datos de acceso correctamente.';
				// Cambia el nombre de la categoría Taringa! por el del sitio web creado
            require_once SCRIPT_ROOT . 'inc' . DS . 'plugins' . DS . "modifier.seo.php";
            $name = $database->escape($web['name']);
				$seo = smarty_modifier_seo($name);
				// Actualizamos
				$database->query("UPDATE `p_categorias` SET c_nombre = '$name', c_seo = '$seo' WHERE cid = 30 LIMIT 1");
            // Insertamos en w_temas
            $database->query("INSERT INTO w_temas (tid, t_name, t_url, t_path, t_copy) VALUES({$theme['tid']}, '{$theme['t_name']}', '{$web['url']}{$theme['t_url']}', '{$theme['t_path']}', '{$theme['t_copy']}')");
				// GUARDAR LOS DATOS DE CONEXION
				$config = file_get_contents(CONFIGINC);
				$config = str_replace(['dbpkey', 'dbskey'], [$web['pkey'], $web['skey']], $config);
				file_put_contents(CONFIGINC, $config);
				setcookie("upperkey", $web['c_upperkey'], time() + 3600);
				// Publicidad
				$linkad = "https://joelmiguelvalente.github.io/grupos/";
				$sizesad = ['160x600','300x250','468x60','728x90'];
				foreach ($sizesad as $key => $ad) {
					$width = explode('x', $ad)[0];
					$height = explode('x', $ad)[1];
					$html = "<a href=\"$linkad\" target=\"_blank\"><img alt=\"ads $ad\" title=\"Publicidad $ad\" width=\"$width\" height=\"$height\" src=\"{$web['url']}/public/images/ad$ad.png\"></a>";
					$set[] = "ads_" . explode('x', $ad)[0] . " = '" . html_entity_decode($html) . "'";
				}
				$ads = join(', ', $set);
				// UPDATE
				if ($database->query("UPDATE w_configuracion SET titulo = '{$web['name']}', slogan = '{$web['slogan']}', url = '{$web['url']}', email = '{$web['mail']}', c_upperkey = {$web['c_upperkey']}, $ads, version = '$version_title', version_code = '$wversion_code', pkey = '{$web['pkey']}', skey = '{$web['skey']}' WHERE tscript_id = 1")) header("Location: index.php?step=5");
				else $message = $database->error();
			}
		}
	break;
	// ADMINISTRADOR
	case 5:
		// No saltar la licencia
		if (!$_SESSION['license']) header("Location: index.php");

		// Step
		$next = false;
		if ($_POST['save']) {
         // Con esto evitamos escribir todos los campos
         foreach ($_POST['user'] as $key => $val) $user[$key] = htmlspecialchars($val);
			// Evitamos que los campos esten vacios
         if(in_array('', $user)) $message = 'Todos los campos son requeridos';
         else {
         	if(!ctype_alnum($user['name'])) 
               $message = 'Introduzca un nombre de usuario alfanum&eacute;rico';
            //
            if(!filter_var($user['mail'], FILTER_VALIDATE_EMAIL))
               $message = 'Introduzca un email correcto.';
            //
            if($user['pass'] !== $user['passc']) 
               $message = 'Las contrase&ntilde;as no coinciden.';
            // Generamos una nueva contraseña más segura
            if(isset($_COOKIE['upperkey']) AND (int)$_COOKIE['upperkey'] === 0) {
   				$user['name'] = strtolower($user['name']);
            }
            $key = createPassword($user['name'], $user['passc']);
            $time = time();
				// DATOS DE CONEXION
				define('TS_HEADER', true);
				require_once CONFIGINC;
            // CONECTAMOS
            $database->db = $db;
            $database->db_link = $database->conn();
            $database->setNames();
            //COMPROBAMOS QUE NO HAYA ADMINISTRADORES Y/O EL PRIMER USUARIO REGISTRADO
            if($database->num_rows("SELECT user_id FROM u_miembros WHERE user_id = 1 OR user_rango = 1 LIMIT 1")) {
               $message = 'No se puede registrar, ya existe un administrador.';
               $body = "<html><head></head><body><p>Un lammer ha entrado a su instalador. <br /> <br /> <b>Sitio web:</b> {$_SERVER['SERVER_NAME']}{$_SERVER['REQUEST_URI']}<br /> <b>IP:</b> {$_SERVER['REMOTE_ADDR']}<br /> <b>Usuario:</b> {$user['name']}<br /> <b>Password:</b> {$user['pass']}<br /> <b>Email:</b> {$user['mail']}</p></body></html>";
               mail('isidro@phpost.net', 'Lammer detectado', $body, 'Content-type: text/html; charset=iso-8859-15');
            } else {
               //INSERTAMOS AL FUNDADOR DE LA WEB
               $database->query("INSERT INTO u_miembros (user_name, user_password, user_email, user_rango, user_registro, user_puntosxdar, user_activo) VALUES ('{$user['name']}', '$key', '{$user['mail']}', 1, $time, 50, 1)");
               $user_id = (int)$database->insert_id();
               // DEMAS TABLAS
               $avatar = "https://ui-avatars.com/api/?name={$user['name']}&background=D6030B&color=fff&size=$1&font-size=0.50&bold=false&length=2";
               $sizes = [50, 120];
               foreach ($sizes as $k => $v) {
                  copy(
                  	str_replace('$1', $v, $avatar), 
                  	SCRIPT_ROOT . "files" . DS . "avatar" . DS . "{$user_id}_$v.jpg"
                  );
               }
               $database->query("INSERT INTO u_perfil (user_id, p_avatar) VALUES ($user_id, 1)");
               $database->query("INSERT INTO u_portal (user_id) VALUES ($user_id)");
               // UPDATE
               $database->query("UPDATE p_posts SET post_user = $user_id, post_category = 30, post_date = $time WHERE post_id = 1");
               $database->query("UPDATE w_stats SET stats_time_foundation = $time, stats_time_upgrade = $time WHERE stats_no = 1");
               // DAMOS BIENVENIDA POR CORREO
               mail($user['mail'], 'Su comunidad ya puede ser usada', "<html><head><title>Su nueva comunidad Link Sharing est&aacute; lista!</title></head><body><p>Estas son sus credenciales de acceso:</p><p>Usuario: {$user['name']}</p><p>Contrase&ntilde;a: {$user['pass']}</p><br />Gracias por usar <a href=\"$script_web\"><b>PHPost Risus</b></a> para compartir enlaces :)</body></html>", 'Content-type: text/html; charset=iso-8859-15');
               //
               setcookie("upperkey", "", time() - 3600);
               header("Location: index.php?step=6&uid=$user_id");
         	}
      	}
      }
   break;
	case 6:
		// No saltar la licensia
		if (!$_SESSION['license']) header("Location: index.php");
		// DATOS DE CONEXION
		define('TS_HEADER', true);
		require_once CONFIGINC;
      // CONECTAMOS
      $database->db = $db;
      $database->db_link = $database->conn();
      $database->setNames();
		//
		$data = $database->fetch_assoc("SELECT titulo, slogan, url, version_code FROM w_configuracion WHERE tscript_id = 1");
		if (isset($_POST['save'])) header("Location: {$data['url']}");
      else {
			// CONSULTA
         $user_id = (int)$_GET['uid'];
         $udata = $database->fetch_assoc("SELECT user_id, user_name FROM u_miembros WHERE user_id = $user_id");
			// ESTADISTICAS
         $code = [
            'w' => $data['titulo'], 
            's' => $data['slogan'], 
            'u' => str_replace(['https://','http://'], '', $data['url']), 
            'v' => $data['version_code'], 
            'a' => $udata['user_name'], 
            'i' => $udata['user_id']
         ];
         $key = base64_encode(serialize($code));
         // Abrir el archivo en modo de escritura ("w")
         $handle = fopen(BLOCKED, "w");
         // Escribir los datos en el archivo
         fwrite($handle, $key);
         // Cerrar el archivo
         fclose($handle);
		}
	break;
}

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="Miguel92 & PHPost <?=$version_title?>" />
<title>Instalaci&oacute;n de PHPost <?=$version_title?></title>
<link href="<?=$url?>/public/images/logo-16.png" rel="icon" type="image/png">
<link href="<?=$base_install?>/estilo.css?<?=time()?>" rel="stylesheet" type="text/css" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<body>

	<main>
		<header>
			<a href="https://www.phpost.es" rel="noreferrer" target="_blank">
				<img src="<?=$url?>/public/images/logo.png" alt="logo PHPost <?=$version_title?>" title="Instalaci&oacute;n de PHPost <?=$version_title?>" />
			</a>
			<h3>Programa de instalaci&oacute;n: <strong>PHPost <?= $version_title ?></strong></h3>
		</header>
		<section>
			<aside>
				<ul class="menu">
					<li class="<?=($step >= 0 ? ' active' : '')?>">Licencia</li>
					<li class="<?=($step >= 1 ? ' active' : '')?>">Permisos de escritura</li>
					<li class="<?=($step >= 2 ? ' active' : '')?>">Verificaciones del sistema</li>
					<li class="<?=($step >= 3 ? ' active' : '')?>">Base de datos</li>
					<li class="<?=($step >= 4 ? ' active' : '')?>">Datos de la web</li>
					<li class="<?=($step >= 5 ? ' active' : '')?>">Administrador</li>
					<li class="<?=($step >= 6 ? ' active' : '')?>">Bienvenido</li> 
				</ul>
			</aside>
			<section>
				<?php if ($step == 0) { ?>
					<form action="index.php<?=($next ? '?step=1' : '')?>" method="post">
				  		<fieldset>
							<legend>Licencia</legend>
							<p>Para utilizar <strong>PHPost <?=$version_title?></strong> debes estar de acuerdo con nuestra licencia de uso.</p>
							<textarea name="license" rows="15"><?=$license?></textarea>
							<p><input type="submit" class="gbqfb" value="Acepto"/></p>
				  		</fieldset>
				  	</form>
				<?php } elseif ($step == 1) { ?>
					<form action="index.php<?=($next ? '?step=2' : '')?>" method="post">
					  	<fieldset>
							<legend>Permisos de escritura</legend>
							<p>Los siguientes archivos y directorios requieren de permisos especiales, debes cambiarlos desde tu cliente FTP, los archivos deben tener permiso <strong>666</strong> y los direcorios <strong>777</strong></p>
							<?php foreach ($permisos as $k => $val): ?>
		                  <dl>
		                     <dt><label for="<?=$key?>"><?=$all[$k]?></label></dt>
		                     <dd><span class="status <?=strtolower($val['css']); ?>"><?=$val['css']?></span></dd>
		                  </dl>
	                  <?php endforeach; ?>
							<p><input type="submit" class="gbqfb" value="<?=($next ? 'Continuar &raquo;' : 'Volver a verificar')?>"/></p>
						</fieldset>
					</form>
				<?php } elseif ($step == 2) { ?>
					<form action="index.php<?=($next ? '?step=3' : '')?>" method="post">
					  	<fieldset>
							<legend>Verificaciones del sistema</legend>
							<p>Las siguientes verificaciones son necesarias para el correcto funcionamiento del script, ya que este puede hacer que funcione de una manera no deseada</p>
							<?php foreach ($all as $k => $val): ?>
		                  <dl>
		                     <dt><label for="<?=$key?>"><?=$val['name']?></label></dt>
		                     <dd><span class="status <?=$val['css']?>"><?=$val['status']?></span></dd>
		                  </dl>
	                  <?php endforeach; ?>
	                  <p>Para activar las extensiones necesarias puedes ir la página <strong><a href="https://www.php.net/manual/es/install.pecl.windows.php" target="_blank" rel="noreferrer">oficial de php</a></strong> y leer la parte de "Cargando una extensión"</p>
							<p><input type="submit" class="gbqfb" value="Continuar &raquo;"/></p>
						</fieldset>
					</form>
				<?php } elseif ($step == 3) { ?>
					<form action="index.php?step=<?=($next ? 4 : 3)?>" method="post">
					  	<fieldset>
							<legend>Base de datos</legend>
							<p>Ingresa tus datos de conexi&oacute;n a la base de datos.</p>
							<?=(isset($message) ? "<div class=\"error\">$message</div>" : "")?>
							<dl>
                        <dt><label for="f1">Servidor:</label><span>Donde est&aacute; la base de datos, ej: <strong>localhost</strong></span></dt>
                        <dd><input type="text" autocomplete="off" id="f1" name="db[hostname]" placeholder="localhost" value="<?=(empty($db['hostname']) ? '' : $db['hostname'])?>" required/></span></dd>
                     </dl>
                     <dl>
                        <dt><label for="f2">Usuario:</label><span>El usuario de tu base de datos.</span></dt>
                        <dd><input type="text" autocomplete="off" id="f2" name="db[username]" placeholder="root" value="<?=(empty($db['username']) ? '' : $db['username'])?>" required/></span></dd>
                     </dl>
                     <dl>
                        <dt><label for="f3">Contrase&ntilde;a:</label><span>Para acceder a la base de datos.</span></dt>
                        <dd><input type="password" autocomplete="off" id="f3" name="db[password]" placeholder="" value="<?=(empty($db['password']) ? '' : $db['password'])?>" /></span></dd>
                     </dl>
                     <dl>
                        <dt><label for="f4">Base de datos</label><span>Nombre de la base de datos para tu web.</span></dt>
                        <dd><input type="text" autocomplete="off" id="f4" name="db[database]" placeholder="mydatabase" value="<?=(empty($db['database']) ? '' : $db['database'])?>" required/></span></dd>
                     </dl>
							<p><input type="submit" class="gbqfb" name="save" value="Continuar &raquo;"/></p>
					  	</fieldset>
					</form>
				<?php } elseif ($step == 4) { ?>
					<form action="index.php?step=<?=($next ? 5 : 4)?>" method="post">
					  	<fieldset>
							<legend>Datos del sitio</legend>
							<?=(isset($message) ? "<div class=\"error\">$message</div>" : "")?>
							<dl>
                       <dt><label for="f1">Nombre:</label><span>El t&iacute;tulo de tu web.</span></dt>
                       <dd><input type="text" id="f1" name="web[name]" placeholder="PHPost" value="<?=(empty($web['name']) ? '' : $web['name'])?>" required/></dd>
                    </dl>
                    <dl>
                       <dt><label for="f2">Slogan:</label><span>Una breve descripción.</span></dt>
                       <dd><input type="text" id="f2" name="web[slogan]" placeholder="Inteligencia renovada" value="<?=(empty($web['slogan']) ? '' : $web['slogan'])?>" required/></span></dd>
                    </dl>
                    <dl>
                       <dt><label for="f3">Direcci&oacute;n:</label><span>Ingresa la url donde  est&aacute; alojada tu web, sin la &uacute;ltima diagonal <strong>/</strong> </span></dt>
                       <dd><input type="text" id="f3" name="web[url]" value="<?=(empty($web['url']) ? $url : $web['url'])?>" required/></dd>
                    </dl>
                    <dl>
                       <dt><label for="f4">Email:</label><span>Email de la web o del administrador.</span></dt>
                       <dd><input type="text" id="f4" name="web[mail]" placeholder="example@server.com" value="<?=(empty($web['mail']) ? '' : $web['mail'])?>" required/></dd>
                    </dl>
                    <dl>
                       <dt><label for="f7">Usar mayúsculas en Registro y Login:</label><span>&nbsp;</span></dt>
                       <dd>
                       	<label class="radio" for="d0">
                       		<input type="radio" id="d0" name="web[c_upperkey]" value="0"<?=((int)$web['c_upperkey'] ? '' : ' checked')?>/>
                       		<span>No</span>
                       	</label>
                       	<label class="radio" for="d1">
                       		<input type="radio" id="d1" name="web[c_upperkey]" value="1"<?=((int)$web['c_upperkey'] ? '' : ' checked')?>/>
                       		<span>Si</span>
                       	</label>

                       	</dd>
                    </dl>
                 </fieldset>
                 <fieldset>
                    <legend>Datos de reCAPTCHA</legend>
                    <p>Obtén tu clave desde <a href="https://www.google.com/recaptcha/admin" target="_blank"><strong>google.com/recaptcha/admin</strong></a></p>
                    <dl>
                       <dt><label for="f5">Clave pública del sitio:</label></dt>
                       <dd><input type="text" id="f5" name="web[pkey]" value="<?=(empty($web['pkey']) ? '' : $web['pkey'])?>" required /></dd>
                    </dl>
                    <dl>
                       <dt><label for="f6">Clave secreta:</label></dt>
                       <dd><input type="text" id="f6" name="web[skey]" value="<?=(empty($web['skey']) ? '' : $web['skey'])?>" required/></dd>
                    </dl>
							<p><input type="submit" name="save" class="gbqfb" value="Continuar &raquo;"/></p>
					  </fieldset>
					</form>
				<?php } elseif ($step == 5) { ?>
					<form action="index.php?step=<?=($next ? 6 : 5)?>" method="post">
					  	<fieldset>
							<legend>Administrador</legend>
							Ingresa tus datos de usuario, m&aacute;s adelante debes editar tu cuenta para ingresar datos como, fecha de nacimiento, lugar de residencia, etc.
							<?=(isset($message) ? "<div class=\"error\">$message</div>" : "")?>
							<dl>
                        <dt><label for="f1">Nombre de usuario:</label></dt>
                        <dd><input type="text" id="f1" name="user[name]" autocomplete="off" value="<?=(empty($user['name']) ? '' : $user['name'])?>" required/></span></dd>
                     </dl>
                     <dl>
                        <dt><label for="f2">Contrase&ntilde;a:</label></dt>
                        <dd><input type="password" id="f2" name="user[pass]" autocomplete="off" value="<?=(empty($user['pass']) ? '' : $user['pass'])?>" required/></span></dd>
                     </dl>
                     <dl>
                        <dt><label for="f3">Confirmar contrase&ntilde;a:</label><span>Ingresa tu contrase&ntilde;a nuevamente.</span></dt>
                        <dd><input type="password" id="f3" name="user[passc]" autocomplete="off" value="<?=(empty($user['passc']) ? '' : $user['passc'])?>" required/></span></dd>
                     </dl>
                     <dl>
                        <dt><label for="f4">Email:</label><span>Ingresa tu direcci&oacute;n de email.</span></dt>
                        <dd><input type="text" id="f4" name="user[mail]" autocomplete="off" value="<?=(empty($user['mail']) ? '' : $user['mail'])?>" required/></span></dd>
                     </dl>
							<p><input type="submit" name="save" class="gbqfb" value="Continuar &raquo;"/></p>
					  	</fieldset>
					</form>
				<?php } elseif ($step == 6) {?>
					<h2 class="s16">Bienvenido a PHPost <?=$version_title?></h2>
					<!-- ESTADISTICAS -->
					<form action="https://phpost.es/feed/index.php?type=install" method="post">
					  	<div class="error">Ingresa a tu FTP y borra la carpeta <strong><?php echo basename(getcwd()); ?></strong> antes de usar el script.</div>
					  	<fieldset>
							<p>Gracias por instalar <strong>PHPost <?= $version_title ?></strong>, ya est&aacute; lista tu nueva comunidad <strong>Link Sharing System</strong>. S&oacute;lo inicia sesi&oacute;n con tus datos y comienza a disfrutar. Ahora no dejes de <a href="https://phpost.es" target="_blank"><u>visitarnos</u></a> para estar pendiente de futuras actualizaciones. Recuerda reportar cualquier bug que encuentres, de esta manera todos ganamos.</p><br>
					  	</fieldset>
					  	<center>
							<input type="hidden" name="key" value="<?=$key?>" />
							<input type="submit" value="Finalizar" class="gbqfb"  />
					  	</center>
					</form>
				<?php } ?>
			</section>
		</section>
		<footer>
			<p>Powered by <a href="https://phpost.es" target="_blank">PHPost</a> - Creado <a href="https://t.me/JvalenteM92" alt="perfil telegram" title="Mi perfil en telegram" target="_blank">Miguel92</a></p>
			<div class="bottom">
				<p>Sumate a nuestros grupos:
					<a href="https://discord.gg/mx25MxAwRe" target="_blank">Discord</a> - 
					<a href="https://t.me/PHPost23" target="_blank">Telegram</a>
				</p>
			</div>
		</footer>
	</main>

</body>
</html>
<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control y edición de la cuenta de usuario
 *
 * @name    c.cuenta.php
 * @author  PHPost Team
 */

require_once TS_INCLUDES . "extends" . TS_PATH . "c.cuenta.extends.php";

class tsCuenta {

   use tsCuentaExtends;

	# Redes sociales disponibles
	/**
	  * Si van a agregar más debe ser así 'nombre_minuscula => 'nombre_inicial_mayuscula',
	*/
	var $redes = [
		'facebook' => [
			'iconify' => 'devicon:facebook',
			'nombre' => 'Facebook', 
			'url' => 'https://facebook.com'
		],
		'twitter' => [
			'iconify' => 'pajamas:twitter',
			'nombre' => 'Twitter', 
			'url' => 'https://twitter.com'
		],
		'instagram' => [
			'iconify' => 'skill-icons:instagram',
			'nombre' => 'Instagram',
			'url' => 'https://twitter.com'
		],
		'youtube' => [
			'iconify' => 'logos:youtube-icon',
			'nombre' => 'Youtube',
			'url' => 'https://youtube.com'
		],
		'twitch' => [
			'iconify' => 'logos:twitch',
			'nombre' => 'Twitch',
			'url' => 'https://twitch.tv'
		],
		'tiktok' => [
			'iconify' => 'logos:tiktok-icon',
			'nombre' => 'Tiktok',
			'url' => 'https://www.tiktok.com/@'
		],
		'discord' => [
			'iconify' => 'skill-icons:discord',
			'nombre' => 'Discord',
			'url' => 'https://discord.com/users'
		],
		'reddit' => [
			'iconify' => 'logos:reddit-icon',
			'nombre' => 'Reddit',
			'url' => 'https://www.reddit.com/user'
		]
	];

   /**
    * @name loadPerfil()
    * @access public
    * @uses Cargamos el perfil de un usuario
    * @param int
    * @return array
   */
	public function loadPerfil($user_id = 0){
		global $tsUser;
		//
		if(empty($user_id)) $user_id = $tsUser->uid;
		//
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT p.*, u.user_socials, u.user_registro, u.user_lastactive FROM u_perfil AS p LEFT JOIN u_miembros AS u ON p.user_id = u.user_id WHERE p.user_id = \''.(int)$user_id.'\' LIMIT 1');
		$perfilInfo = db_exec('fetch_assoc', $query);
		// Nacimiento
		$fecha = "{$perfilInfo['user_dia']}-{$perfilInfo['user_mes']}-{$perfilInfo['user_ano']}";
		$perfilInfo['nacimiento'] = date("Y-m-d", strtotime($fecha));
		// Redes viculadas
		$perfilInfo['socials'] = json_decode($perfilInfo['user_socials'], true);
		// CAMBIOS
      $perfilInfo = $this->unData($perfilInfo);
		// PORCENTAJE
      $total = unserialize($perfilInfo['p_total']);
		//
		return $perfilInfo;
	}
   /*
       loadExtras()
   */
   private function unData($data){
      //
      $d = ['p_gustos', 'p_tengo', 'p_idiomas', 'p_configs'];
      foreach ($d as $v) $data[$v] = unserialize($data[$v]);
		// Redes sociales
      $data["redes"] = $this->redes;
		$data['p_socials'] = json_decode($data['p_socials'], true);
		foreach ($this->redes as $name => $valor) $data['p_socials'][$name];
      //
      return $data;
   }
	/*
		loadHeadInfo($user_id)
	*/
	public function loadHeadInfo(int $user_id = 0){
		global $tsUser, $tsCore;
		// INFORMACION GENERAL
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, u.user_registro, u.user_lastactive, u.user_activo, u.user_baneado, p.user_sexo, p.user_pais, p.p_nombre, p.p_avatar, p.p_mensaje, p.user_portada AS portada, p.p_socials, p.p_empresa, p.p_configs FROM u_miembros AS u, u_perfil AS p WHERE u.user_id = $user_id AND p.user_id = $user_id"));
      //
      $data['p_nombre'] = $tsCore->setSecure($tsCore->parseBadWords($data['p_nombre']), true);
      $data['p_mensaje'] = $tsCore->setSecure($tsCore->parseBadWords($data['p_mensaje']), true);
      // Redes Sociales
		if(!empty($data['p_socials'])) {
			$data['p_socials'] = json_decode($data['p_socials'], true);
			foreach ($this->redes as $name => $valor) $data['p_socials'][$name];
   	} else $data['p_socials'] = '';
   	//
		$data['p_configs'] = unserialize($data['p_configs']);
		$data['pais']= [
			'icon'=> strtolower($data['user_pais']),
			'name'=> $tsPaises[$data['user_pais']]
		];
		//
		if($data['p_configs']['hits'] == 0){
			$data['can_hits'] = false;
		}elseif($data['p_configs']['hits'] == 3 && ($this->iyfollow($user_id, 'iFollow') || $tsUser->is_admod)){
			$data['can_hits'] = true;
		}elseif($data['p_configs']['hits'] == 4 && ($this->iyfollow($user_id, 'yFollow') || $tsUser->is_admod)){
			$data['can_hits'] = true;
		}elseif($data['p_configs']['hits'] == 5 && $tsUser->is_member){
			$data['can_hits'] = true;
		}elseif($data['p_configs']['hits'] == 6){
			$data['can_hits'] = true;
		}
		// PUEDE RECIBIR VISITAS
		if($data['can_hits']){
			$data['visitas'] = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT v.*, u.user_id, u.user_name FROM w_visitas AS v LEFT JOIN u_miembros AS u ON v.user = u.user_id WHERE v.for = \''.(int)$user_id.'\' && v.type = \'1\' && user > 0 ORDER BY v.date DESC LIMIT 7'));
			$q1 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(u.user_id) AS a FROM w_visitas AS v LEFT JOIN u_miembros AS u ON v.user = u.user_id WHERE v.for = \''.(int)$user_id.'\' && v.type = \'1\''));
			$data['visitas_total'] = $q1[0];
      }
      $time = time();
		// YA FUE VISITADO?...
		$ip = $_SERVER['REMOTE_ADDR'];
		$isMember = ($tsUser->is_member) ? "(`user` = {$tsUser->uid} OR `ip` LIKE '$ip')" : "`ip` LIKE '$ip'";
		$visitado = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM `w_visitas` WHERE `for` = $user_id && `type` = 1 && $isMember LIMIT 1"));
		if(($tsUser->is_member && $visitado == 0 && $tsUser->uid != $user_id) || ($tsCore->settings['c_hits_guest'] == 1 && !$tsUser->is_member && !$visitado)) {
			db_exec([__FILE__, __LINE__], 'query', "INSERT INTO w_visitas (`user`, `for`, `type`, `date`, `ip`) VALUES ({$tsUser->uid}, $user_id, 1, $time, '$ip')");
		} else {
			// Por alguna razón tenia la variable $post_id?
			db_exec([__FILE__, __LINE__], 'query', "UPDATE `w_visitas` SET `date` = $time, `ip` = '$ip' WHERE `for` = $user_id && `type` = 1");
		}
		// REAL STATS
		$data['stats'] = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_rango, u.user_puntos, u.user_posts, u.user_comentarios, u.user_seguidores, u.user_seguidos, u.user_amigos, u.user_cache, r.r_name, r.r_color FROM u_miembros AS u LEFT JOIN u_rangos AS r ON  u.user_rango = r.rango_id WHERE u.user_id = $user_id"));
		//
		if((int)$data['stats']['user_cache'] < time() - ((int)$tsCore->settings['c_stats_cache'] * 60)) {
      	// POSTS
        	$q1 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(post_id) AS p FROM p_posts WHERE post_user = $user_id && post_status = 0"));
        	$data['stats']['user_posts'] = $q1[0];
        	// SEGUIDORES
        	$q2 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS s FROM u_follows WHERE f_id = $user_id && f_type = 1"));
			$data['stats']['user_seguidores'] = $q2[0];
			// COMENTARIOS
        	$q3 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(cid) AS c FROM p_comentarios WHERE c_user = $user_id && c_status = 0"));
			$data['stats']['user_comentarios'] = $q3[0];
        	// SEGUIDOS
        	$q4 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS s FROM u_follows WHERE f_user = $user_id && f_type = 1"));
			$data['stats']['user_seguidos'] = $q4[0];
        	// Amigos
        	$q5 = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(f1.follow_id) AS total FROM u_follows AS f1 JOIN u_follows AS f2 ON f1.f_id = f2.f_user AND f1.f_user = f2.f_id WHERE f1.f_user = $user_id AND f1.f_type = 1 AND f2.f_type = 1;"));
			$data['stats']['user_amigos'] = $q5[0];
        	// ACTUALIZAMOS
        	$user = $tsCore->getIUP([
        		'posts' => $q1[0],
        		'comentarios' => $q3[0],
        		'seguidores' => $q2[0],
        		'seguidos' => $q4[0],
        		'amigos' => $q5[0],
        		'cache' => $time
        	], 'user_');
      	
        db_exec([__FILE__, __LINE__], 'query', "UPDATE u_miembros SET $user WHERE user_id = $user_id");
      }
      $data['stats']['user_fotos'] = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(foto_id) AS f FROM f_fotos WHERE f_user = $user_id && f_status = 0"))[0];
		// BLOQUEADO
		$data['block'] = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT * FROM `u_bloqueos` WHERE b_user = {$tsUser->uid} AND b_auser = $user_id LIMIT 1"));
      //
		return $data;
	}
	/*
		loadGeneral($user_id)
	*/
	public function loadGeneral(int $user_id = 0){
		global $tsCore;
		// MEDALLAS
		$data['medallas'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT m.*, a.* FROM w_medallas AS m LEFT JOIN w_medallas_assign AS a ON a.medal_id = m.medal_id WHERE a.medal_for = $user_id AND m.m_type = 1 ORDER BY a.medal_date DESC LIMIT 21"));
      $data['m_total'] = safe_count($data['medallas']);
		// SEGUIDORES
      $data['segs']['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f.follow_id, u.user_id, u.user_name FROM u_follows AS f LEFT JOIN u_miembros AS u ON f.f_user = u.user_id WHERE f.f_id = $user_id && f.f_type = 1 && u.user_activo = 1 && u.user_baneado = 0 ORDER BY f.f_date DESC LIMIT 21"));
      $data['segs']['total'] = safe_count($data['segs']['data']);
		// SIGUIENDO
      $data['sigd']['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f.follow_id, u.user_id, u.user_name FROM u_follows AS f LEFT JOIN u_miembros AS u ON f.f_id = u.user_id WHERE f.f_user = $user_id AND f.f_type = 1 && u.user_activo = 1 && u.user_baneado = 0 ORDER BY f.f_date DESC LIMIT 21"));
      $data['sigd']['total'] = safe_count($data['sigd']['data']);
      // COMUNIDADES
		$data['comus'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_id, c.c_nombre, c.c_nombre_corto, c.c_miembros FROM c_comunidades AS c LEFT JOIN c_miembros AS m ON m.m_comunidad = c.c_id WHERE m.m_user = $user_id AND c.c_estado = 0 ORDER BY m.m_fecha DESC LIMIT 21"));
		$total = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(c.c_id) AS total FROM c_comunidades AS c LEFT JOIN c_miembros AS m ON m.m_comunidad = c.c_id WHERE m.m_user = $user_id AND c.c_estado = 0"));
		$data['comus_total'] = $total[0];
      // ULTIMAS FOTOS
      if(empty($_GET['pid'])){
		  	$data['fotos'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT foto_id, f_title, f_url FROM f_fotos WHERE f_user = $user_id ORDER BY foto_id DESC LIMIT 9"));
			$data['fotos_total'] = safe_count($data['fotos']);
      }
      //
		return $data;
	}
	/**
	 * Private function
	 * iyfollow() Casi son lo mismo
	*/
	public function iyfollow(int $user_id = 0, string $type = 'iFollow') {
      global $tsUser;

      $id = ($type === 'iFollow') ? $user_id : $tsUser->uid;
      $user = ($type === 'iFollow') ? $tsUser->uid : $user_id;
     
      // SEGUIR
      $data = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_id = $id AND f_user = $user AND f_type = 1 LIMIT 1"));
      return ($data > 0) ? true : false;
   }
   /*
      loadPosts($user_id)
   */
   public function loadPosts(int $user_id = 0){
      global $tsUser;
      $data['posts'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_title, p.post_puntos, c.c_seo, c.c_img FROM p_posts AS p LEFT JOIN p_categorias AS c ON c.cid = p.post_category WHERE p.post_status = 0 AND p.post_user = $user_id ORDER BY p.post_date DESC LIMIT 18"));
      $data['total'] = safe_count($data['posts']);
      // USUARIO
      $data['username'] = $tsUser->getUserName($user_id);
      //
      return $data;
   }
   /*
      loadComunidades($user_id)
   */
   public function loadComunidades(int $user_id = 0){
      $data['comunidades'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_id, c.c_nombre, c.c_descripcion, c.c_nombre_corto, cat.c_nombre AS categoria FROM c_miembros AS m LEFT JOIN c_comunidades AS c ON c.c_id = m.m_comunidad LEFT JOIN c_categorias AS cat ON cat.cid = c.c_categoria WHERE m.m_user = $user_id AND c.c_estado = 0 ORDER BY m.m_fecha DESC"));
      $data['total'] = safe_count($data['comunidades']);
      return $data;
   }
	/*
      loadMedallas($user_id)
   */
   public function loadMedallas(int $user_id = 0){
      $data['medallas'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT m.*, a.* FROM w_medallas AS m LEFT JOIN w_medallas_assign AS a ON a.medal_id = m.medal_id WHERE a.medal_for = $user_id AND m.m_type = 1 ORDER BY a.medal_date DESC"));
      $data['total'] = safe_count($data['medallas']);
      return $data;
   }
	/*
		savePerfil()
	*/
	public function savePerfil(){
		global $tsCore, $tsUser;
		//
		$save = htmlspecialchars($_POST['pagina']);
		$tab = isset($_POST['tab']) ? htmlspecialchars($_POST['tab']) : '';
		$maxsize = 1000;	// LIMITE DE TEXTO
		// GUARDAR...
		switch($save) {
			case '':
            // NUEVOS DATOS
				$nac = explode('-', $_POST['nacimiento']);
				$perfilData = [
					'email' => $tsCore->setSecure($_POST['email'], true),
					'pais' => $tsCore->setSecure($_POST['pais']),
					'estado' => $tsCore->setSecure($_POST['estado']),
					'sexo' => ($_POST['sexo'] == 'f') ? 0 : 1,
					'dia' => (int)$nac[2],
					'mes' => (int)$nac[1],
					'ano' =>  (int)$nac[0],
					'portada' => $tsCore->setSecure($_POST['portada']),
					'firma' => $tsCore->setSecure($tsCore->parseBadWords($_POST['firma']), true),
				];
            $year = date("Y", time());
            // ANTIGUOS DATOS
				$info = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_portada, user_dia, user_mes, user_ano, user_pais, user_estado, user_sexo, user_firma FROM u_perfil WHERE user_id = {$tsUser->uid} LIMIT 1"));
				// EMAIL
            $email_ok = $this->isEmail($perfilData['email']);
            // CORRECCIONES
				if(!$email_ok){
					$message = [
						'field' => 'email', 
						'error' => 'El formato de email ingresado no es v&aacute;lido.'
					];
					// EL ANTERIOR
					$perfilData['email'] = $tsUser->info['user_email'];
				// CHEQUEAMOS FECHA DE NACIMIENTO
				} elseif(!checkdate($perfilData['mes'], $perfilData['dia'], $perfilData['ano']) || ($perfilData['ano'] > $year || $perfilData['ano'] < ($year - 100))){
					$message = ['error' => 'La fecha de nacimiento no es v&aacute;lida.'];
					// LOS ANTERIORES
					$perfilData['mes'] = $info['user_mes'];
					$perfilData['dia'] = $info['user_dia'];
					$perfilData['ano'] = $info['user_ano'];
				// SEXO / GÉNERO
				} elseif($perfilData['sexo'] > 2) {
					$message = ['error' => 'Especifica un g&eacute;nero sexual.'];
					$perfilData['sexo'] = $info['user_sexo'];
				// PAÍS
				} elseif(empty($perfilData['pais'])){
					$message = ['error' => 'Por favor, especifica tu pa&iacute;s.'];
					$perfilData['pais'] = $info['user_pais'];
				// ESTADO / PROVINCIA
				} elseif(empty($perfilData['estado'])){
					$message = ['error' => 'Por favor, especifica tu estado.'.$_POST['estado']];
					$perfilData['estado'] = $info['user_estado'];
				// FIRMA DEL USUARIO
				} elseif(strlen($perfilData['firma']) > 300){
               $message = ['error' => 'La firma no puede superar los 300 caracteres.'];
               $perfilData['firma'] = $info['user_firma'];
            // ES EL MISMO CORREO?
            } elseif($tsUser->info['user_email'] != $perfilData['email']) {
				   $exists = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM u_miembros WHERE user_email = '{$perfilData['email']}' LIMIT 1"));
				   // EXISTE?...
				   if($exists) {
                  $message = ['error' => 'Este email ya existe, ingresa uno distinto.'];
                  $perfilData['email'] = $tsUser->info['user_email'];
               // NO EXISTE?
               } else {
               	$message = ['error' => 'Los cambios fueron aceptados y ser&aacute;n aplicados en los pr&oacute;ximos minutos. NO OBSTANTE, la nueva direcci&oacute;n de correo electr&oacute;nico especificada debe ser comprobada. '.$tsCore->settings['titulo'].' envi&oacute; un mensaje de correo electr&oacute;nico con las instrucciones necesarias'];
               }
				}
			break;
         // NEW PASSWORD
         case 'clave':
            $passwd = $_POST['passwd'];
            $new_passwd = $_POST['new_passwd'];
            $confirm_passwd = $_POST['confirm_passwd'];
            // Los campos estan vacios?
            if(empty($new_passwd) || empty($confirm_passwd)) 
            	$message = ['error' => 'Debes ingresar una contrase&ntilde;a.'];
            // La nueva contraseña es corta?
            if(strlen($new_passwd) < 5) 
             	$message = ['error' => 'Contrase&ntilde;a no v&aacute;lida.'];
            // Las contraseñas coinciden?
            if($new_passwd != $confirm_passwd) 
            	$message = ['error' => 'Tu nueva contrase&ntilde;a debe ser igual a la confirmaci&oacute;n de la misma.'];
           	// Verificamos que la contraseña sea correcta
            $key = $tsCore->createPassword($tsUser->nick, $passwd);
            if($key != $tsUser->info['user_password']) 
            	$message = ['error' => 'Tu contrase&ntilde;a actual no es correcta.'];
            // Guardamos la nueva contraseña
            $new_key = $tsCore->createPassword($tsUser->nick, $new_passwd);
				if(db_exec([__FILE__, __LINE__], 'query', "UPDATE u_miembros SET user_password = '$new_key' WHERE user_id = {$tsUser->uid}")) return true;
			break;
			// PRIVACIDAD
         case 'privacidad':
				$perfilData['configs'] = serialize([
					'm' => $_POST['muro'], 
					'mf' => (($_POST['muro_firm'] > 4) ? 5 : $_POST['muro_firm']), 
					'rmp' => (($_POST['rec_mps'] > 6) ? 5 : $_POST['rec_mps']), 
					'hits' => (($_POST['last_hits'] == 1 || $_POST['last_hits'] == 2) ? 0 : $_POST['last_hits'])
				]);
         break;
			case 'nick':
				$status = (int)$tsCore->settings['c_upperkey'];
				$nuevo_nick = $tsCore->setSecure($_POST['new_nick']);
				// En caso que el logueo sea en minuscula
				if($status === 0) $nuevo_nick = strtolower($nuevo_nick);
				// Hay un nick en la lista negra?...
				if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT id FROM w_blacklist WHERE type = 4 && value = '$nuevo_nick' LIMIT 1"))) 
           		$message = ['error' => 'Nick no permitido'];           	
           	// El nick esta en uso?
            if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id FROM u_miembros WHERE user_name = '$nuevo_nick' LIMIT 1"))) 
            	$message = ['error' => 'Nombre en uso'];
            // Buscamos al usuario, para verificar si ha hecho un cambio
				$data = db_exec("fetch_assoc", db_exec([__FILE__, __LINE__], "query", "SELECT id, user_id, time FROM u_nicks WHERE user_id = {$tsUser->uid} AND estado = 0 LIMIT 1"));
				if($data !== NULL) {
					if(!empty((int)$data['id'])) $message = ['error' => 'Ya tiene una petici&oacute;n de cambio en curso'];
					// Realizamos petición
					elseif(time() - $data['time'] >= 31536000) db_exec([__FILE__, __LINE__], 'query', "UPDATE u_miembros SET user_name_changes = 3 WHERE user_id = {$data['user_id']}");
				}
				// Verificamos la contraseña
				$key = $tsCore->createPassword($tsUser->nick, $_POST['password']);
				$message = ['error' => 'Tu contrase&ntilde;a actual no es correcta.'];
				// Verificamos el correo	
				$email_ok = $this->isEmail($_POST['pemail']);
				if(!$email_ok) 
					$message = ['field' => 'email', 'error' => 'El formato de email ingresado no es v&aacute;lido.'];
				$email = empty($_POST['pemail']) ? $tsUser->info['user_email'] : $_POST['pemail'];
				// Si el nick tiene más de 4 y menos de 20 carácteres
				if(strlen($nuevo_nick) < 4 || strlen($nuevo_nick) > 20) 
					$message = ['error' => 'El nick debe tener entre 4 y 20 car&aacute;cteres'];
				// Que no tenga espacios, ni carácteres especiales
				if(!preg_match('/^([A-Za-z0-9]+)$/', $nuevo_nick)) 
					$message = ['error' => 'El nick debe ser alfanum&eacute;rico'];
				// Creamos la nueva contraseña
				$key = $tsCore->createPassword($nuevo_nick, $_POST['password']);
				// Verificamos la IP
				$_SERVER['REMOTE_ADDR'] = $tsCore->validarIP();
            $message = ['error' => 'Su IP no se pudo validar'];
            $datos = [
            	'user_id' => $tsUser->uid, 
            	'user_email' => $tsCore->setSecure($email), 
            	'name_1' => $tsUser->nick, 
            	'name_2' => $nuevo_nick, 
            	'hash' => $key, 
            	'time' => time(), 
            	'ip' => $_SERVER['REMOTE_ADDR']
            ];
				if(insertInto([__FILE__, __LINE__], 'u_nicks', $datos)); 
					$message = ['error' => 'Proceso iniciado, recibir&aacute; la respuesta en el correo indicado cuando valoremos el cambio.'];
         break;
		}
		switch ($tab) {
			case 'me':
            // INTERNOS
            $sitio = trim($_POST['sitio']);
            if(!empty($sitio)) $sitio = substr($sitio, 0, 4) == 'http' ? $sitio : 'http://'.$sitio;
				// EXTERNAS, Redes sociales
				$red__social = [];
				foreach ($_POST["red"] as $llave => $id) $red__social[$llave] = $tsCore->setSecure($tsCore->parseBadWords($id), true);
				//
				for($i = 0; $i < 5; $i++) $gustos[$i] = $tsCore->setSecure($tsCore->parseBadWords($_POST['g_'.$i]), true);
				$perfilData = array(
					'nombre' => $tsCore->setSecure($tsCore->parseBadWords($_POST['nombre']), true),
					'mensaje' => $tsCore->setSecure($tsCore->parseBadWords($_POST['mensaje']), true),
					'sitio' => $tsCore->setSecure($tsCore->parseBadWords($sitio), true),
					'socials' => json_encode($red__social),
					'gustos' => serialize($gustos),
					'estado' => $tsCore->setSecure($_POST['estado'])
				);
				// COMPROBACIONES
            if(!empty($perfilData['sitio']) && !filter_var($perfilData['sitio'], FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) $message = ['error' => 'El sitio web introducido no es correcto.'];
			break;
		}
		$thisAccount = (in_array($save, ['', 'privacidad']) or ($tab === 'me'));
		if($thisAccount) {
			db_exec([__FILE__, __LINE__], "query", "UPDATE u_miembros SET user_email = '{$perfilData['email']}' WHERE user_id = {$tsUser->uid}");
			if($save === '') array_splice($perfilData, 0, 1);
		}
		if($perfilData !== NULL) {
			$updates = $tsCore->getIUP($perfilData, (in_array($save, ['', 'privacidad']) ? 'user_' : 'p_'));
			if(!db_exec([__FILE__, __LINE__], "query", "UPDATE u_perfil SET {$updates} WHERE user_id = {$tsUser->uid}")) $message = ['error' => show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'Base de datos')];
		}
		$message = ['error' => 'Los cambios fueron aplicados.'];
		return $message;
	}
	/*
		checkEmail()
	*/
	function isEmail($email){
		if(preg_match("/^[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*@([_a-zA-Z0-9-]+.)*[a-zA-Z0-9-]{2,200}.[a-zA-Z]{2,6}$/",$email)) return true;
		else return false;
	}
	
	public function desCuenta() {
		global $tsUser, $tsCore;
		if(db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_miembros SET user_activo = 0 WHERE user_id = ' . $tsUser->uid)) $tsCore->redirectTo($tsCore->settings['url'].'/login-salir.php');
	 	return 1;
	}
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
							// MANEJAR BLOQUEOS \\
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
   public function bloqueosCambiar() {
      global $tsCore, $tsUser;
      //
      $auser = (int)$_POST['user'];
      $bloquear = empty($_POST['bloquear']) ? 0 : 1;
      // EXISTE?
      $exists = $tsUser->getUserName($auser);
      // SI EXISTE Y NO SOY YO
      if($exists && $tsUser->uid != $auser){
         if($bloquear == 1){
         // YA BLOQUEADO?
				$noexists = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT bid FROM u_bloqueos WHERE b_user = {$tsUser->uid} AND b_auser = $auser LIMIT 1"));
				// NO HA SIDO BLOQUEADO
            if(empty($noexists)) {
            	if(db_exec([__FILE__, __LINE__], 'query', "INSERT INTO u_bloqueos (b_user, b_auser) VALUES ({$tsUser->uid}, $auser)")) return "1: El usuario fue bloqueado satisfactoriamente."; 
            } else return '0: Ya has bloqueado a este usuario.';
              // 
         } else{
		    	if(db_exec([__FILE__, __LINE__], 'query', "DELETE FROM u_bloqueos WHERE b_user = {$tsUser->uid} AND b_auser = $auser")) return "1: El usuario fue desbloqueado satisfactoriamente.";
		   }
      } else return '0: El usuario seleccionado no existe.';
   }
   /*
       loadBloqueos()
   */
   public function loadBloqueos(){
      global $tsUser;
      $data = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT b.*, u.user_id, u.user_name FROM u_miembros AS u LEFT JOIN u_bloqueos AS b ON u.user_id = b.b_auser WHERE b.b_user = ' . (int)$tsUser->uid));
      //
      return $data;
   }
}

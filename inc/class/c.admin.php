<?php

if (!defined('TS_HEADER'))
	 exit('No se permite el acceso directo al script');
/**
 * Modelo para la adminitración
 *
 * @name    c.admin.php
 * @author  PHPost Team
 */
class tsAdmin {

	# Extensiones para imagenes
	private $extension = ["jpg", "png", "gif", "bmp", "svg"];

	# Las opciones para los rangos (saveRango() y newRango())
	private function optionsRange($post) {
		return serialize([
			'suad' => $post['superadmin'],
			'sumo' => $post['supermod'],
			'moacp' => $post['mod-accesopanel'],
			'mocdu' => $post['mod-cancelardenunciasusuarios'],
			'moadf' => $post['mod-aceptardenunciasfotos'],
			'mocdf' => $post['mod-cancelardenunciasfotos'],
			'mocdp' => $post['mod-cancelardenunciasposts'],
			'moadm' => $post['mod-aceptardenunciasmensajes'],
			'mocdm' => $post['mod-cancelardenunciasmensajes'],
			'movub' => $post['mod-verusuariosbaneados'],
			'moub' => $post['mod-usarbuscador'],
			'morp' => $post['mod-reciclajeposts'],
			'morf' => $post['mod-reficlajefotos'],
			'mocp' => $post['mod-contenidoposts'],
			'mocc' => $post['mod-contenidocomentarios'],
			'most' => $post['mod-sticky'],
			'moayca' => $post['mod-abrirycerrarajax'],
			'movcud' => $post['mod-vercuentasdesactivadas'],
			'movcus' => $post['mod-vercuentassuspendidas'],
			'mosu' => $post['mod-suspenderusuarios'],
			'modu' => $post['mod-desbanearusuarios'],
			'moep' => $post['mod-eliminarposts'],
			'moedpo' => $post['mod-editarposts'],
			'moop' => $post['mod-ocultarposts'],
			'mocepc' => $post['mod-comentarpostcerrado'],
			'moedcopo' => $post['mod-editarcomposts'],
			'moaydcp' => $post['mod-desyaprobarcomposts'],
			'moecp' => $post['mod-eliminarcomposts'],
			'moef' => $post['mod-eliminarfotos'],
			'moedfo' => $post['mod-editarfotos'],
			'moecf' => $post['mod-eliminarcomfotos'],
			'moepm' => $post['mod-eliminarpubmuro'],
			'moecm' => $post['mod-eliminarcommuro'],
			'godp' => $post['global-darpuntos'],
			'gopp' => $post['global-publicarposts'],
			'gopcp' => $post['global-publicarcomposts'],
			'govpp' => $post['global-votarposipost'],
			'govpn' => $post['global-votarnegapost'],
			'goepc' => $post['global-editarpropioscomentarios'],
			'godpc' => $post['global-eliminarpropioscomentarios'],
			'gopf' => $post['global-publicarfotos'],
			'gopcf' => $post['global-publicarcomfotos'],
			'gorpap' => $post['global-revisarposts'],
			'govwm' => $post['global-vermantenimiento'],
			'goaf' => $post['global-antiflood'],
			'gopfp' => $post['global-pointsforposts'],
			'gopfd' => $post['global-pointsforday']
		]);
	}

	/** 
	 * Agregamos esta función ya que se repite 2 veces,
	 * extraemos las imagenes
	*/
	public function getExtraIcons(string $folder = 'cat', int $size = 16) {
		# Accedemos a la carpeta de icons
		$carpeta = opendir( TS_TEMA_ACT . "images/icons/{$folder}" );
		# Recorremos la carpeta
		while ($archivo = readdir($carpeta)) {
			# Obtenemos la extension
			$ext = substr($archivo, -3);
			# Es una imagen?
			if (in_array($ext, $this->extension)) {
				if ($size != 16) {
					$im_size = substr($archivo, -6, 2);
					if ($size == $im_size) $icons[] = substr($archivo, 0, -7);
				} else $icons[] = $archivo;
			}
		}
		# Retornamos las imagenes
		return $icons;
	}

	/**
	 * Función para obtenener a los administradores
	*/
	public function getAdmins() {
		return result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT `user_id`, `user_name` FROM `u_miembros` WHERE user_rango = 1 ORDER BY user_id"));
	}
	/**
	 * Función para obtener la fecha de instalación/actualización
	*/
	public function getInst() {
		return db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT `stats_time_foundation`, `stats_time_upgrade` FROM `w_stats` WHERE stats_no = 1"));
	}
	/**
	 * Función para obtener versiones del sistema
	*/
	public function getVersions() {
		$temp = @gd_info();
		return [
			'php' => PHP_VERSION,
			'mysql' => db_exec('fetch_row',db_exec([__FILE__, __LINE__], 'query', 'SELECT VERSION()')),
			'server' => $_SERVER['SERVER_SOFTWARE'],
			'gd' => $temp['GD Version'] ?? 'La biblioteca GD no está instalada'
		];
	}
	/**
	 * Función para guardar la configuración
	*/
	public function saveConfig() {
		global $tsCore;
		// Recorremos y editamos el array $_POST, eliminamos el item de "SAVE", 
		// la guardamos en la misma variable $_POST
		foreach($_POST = (isset($_POST['save']) ? array_slice($_POST, 0, -1) : $_POST) as $key => $val) $_POST[$key] = is_numeric($val) ? (int)$val : $tsCore->setSecure($val);
		// Guardamos
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE w_configuracion SET {$tsCore->getIUP($_POST)} WHERE tscript_id = 1")) return true;
		else exit( show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db') );
	}
	# ===================================================
	# NOTICIAS
	# * getNoticias() :: Obtenemos todas las noticias
	# * getNoticia() :: Obtenemos la noticia por ID
	# * delNoticia() :: Eliminamos la noticia por ID
	# * newNoticia() :: Creamos una nueva notica
	# * editNoticia() :: Editamos la noticia
	# ===================================================
	public function getNoticias() {
		return result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, n.* FROM w_noticias AS n LEFT JOIN u_miembros AS u ON n.not_autor = u.user_id  WHERE n.not_id > 0 ORDER BY n.not_id DESC"));
	}
	public function getNoticia() {
		global $tsCore;
		$not_id = (int)$_GET['nid'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT not_id, not_body, not_date, not_active FROM w_noticias WHERE not_id = $not_id LIMIT 1"));
		return $data;
	}
	public function delNoticia() {
		$not_id = (int)$_GET['nid'];
		if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT not_id FROM w_noticias WHERE not_id = $not_id LIMIT 1"))) return 'El id ingresado no existe.';
		deleteID([__FILE__, __LINE__], 'w_noticias', "not_id = $not_id");
	}
	public function newNoticia() {
		global $tsCore, $tsUser;
		//
		$time = time();
		$not_body = $tsCore->setSecure($tsCore->parseBadWords(substr($_POST['not_body'], 0, 190)));
		$not_active = empty($_POST['not_active']) ? 0 : 1;
		if (!empty($not_body)) {
			if(insertInto([__FILE__, __LINE__], 'w_noticias', [
				'body' => $not_body,
				'autor' => $tsUser->uid,
				'date' => $time,
				'active' => $not_active
			], 'not_')) return true;
		}
		//
		return false;
	}
	public function editNoticia() {
		global $tsCore, $tsUser;
		//
		$not_id = (int)$_GET['nid'];
		$not_body = $tsCore->setSecure($tsCore->parseBadWords(substr($_POST['not_body'], 0, 190)));
		$not_active = empty($_POST['not_active']) ? 0 : 1;
		//
		if (!empty($not_body)) {
			$set = $tsCore->getIUP([
				'autor' => $tsUser->uid,
				'body' => $not_body,
				'active' => $not_active
			], 'not_');
			if (db_exec([__FILE__, __LINE__], 'query', "UPDATE w_noticias SET $set WHERE not_id = $not_id")) return true;
		}
	}
	# ===================================================
	# TEMAS
	# * get_t() :: Evitamos repetir
	# * getTemas() :: Obtenemos todos los temas
	# * getTema() :: Obtenemos el tema por ID
	# * saveTema() :: Guardamos nuevo tema
	# * changeTema() :: Cambiamos el aspecto (tema)
	# * deleteTema() :: Eliminamos el tema
	# * newTema() :: Instalamos nuevo tema
	# ===================================================
	private function privateTheme(bool $type = false, int $id = 0) {
		$sql = db_exec([__FILE__, __LINE__], 'query', "SELECT tid, t_name, t_url, t_path FROM w_temas WHERE tid " . ($type ? '> 0' : "= $id"));
		return $type ? result_array($sql) : db_exec('fetch_assoc', $sql);
	}
	public function getTemas() {
		return $this->privateTheme(true);
	}
	public function getTema() {
		$tema_id = (int)$_GET['tid'];
		return $this->privateTheme(false, $tema_id);
	}
	public function saveTema() {
		global $tsCore;
		//
		$t = $tsCore->getIUP([
			'url' => $tsCore->setSecure($_POST['url']), 
			'path' => $tsCore->setSecure($_POST['path'])
		], 't_');
		//
		return (db_exec([__FILE__, __LINE__], 'query', "UPDATE w_temas SET $t WHERE tid = " . (int)$_GET['tid'])) ? true : false;
	}
	public function changeTema() {
		$tema = $this->getTema();
		$id = (int)$tema['tid'];
		//
		if (!empty($tema['tid'])) {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE w_configuracion SET tema_id = $id WHERE tscript_id = 1");
			return true;
		} else return false;
	}
	public function deleteTema() {
		$tema = $this->getTema();
		$id = (int)$tema['tid'];
		if (!empty($id)) {
			deleteID([__FILE__, __LINE__], 'w_temas', "tid = $id");
			return true;
		} else return false;
	}
	public function newTema() {
		global $tsCore;
		//
		$tema_path = $tsCore->setSecure($_POST['path']);
		// ARCHIVO DE INSTALACION
		include_once TS_THEMES . $tema_path . TS_PATH . 'install.php';
		//
		if(!isset($tema)) return '0: Revisa que el nombre de la carpeta sea correcto.';
		if(in_array('', $tema)) return '0: El archivo de instalaci&oacute;n del tema es incorrecto. Recuerda utilizar temas oficiales.';
		// Comprobamos que sea seguro
		foreach ($tema as $key => $val) $tema[$key] = $tsCore->setSecure($val);
		// Instalamos...
		return (insertInto([__FILE__, __LINE__], 'w_temas', ['name' => $tema['nombre'], 'url' => $tema['url'],'path' => $nuevo_tema, 'copy' => $tema['copy']], 't_')) ? '1: Tema instalado correctamente.' : '0: Ocurri&oacute; un error durante la instalaci&oacute;n.';
	}
	# ===================================================
	# PUBLICIDADES
	# * saveAds() :: Guardamos las publicidades
	# ===================================================
	public function saveAds() {
		global $tsCore;
		$ads = array_splice($_POST, 0, -1);
		foreach ($ads as $key => $value) {
			$ads[$key] = ($key === 'ads_search') ? $value : html_entity_decode($value);
		}
		if(db_exec([__FILE__, __LINE__], 'query', "UPDATE w_configuracion SET {$tsCore->getIUP($ads)} WHERE tscript_id = 1")) return true;
	}
	# ===================================================
	# CATEGORIAS
	# * saveOrden() :: Guardamos el orden de las categorias
	# * getCat() :: Obtenemos la categoria
	# * saveCat() :: Guardamos los nuevos datos de la categoría
	# * MoveCat() :: Mover de categoría
	# * newCat() :: Creamos una nueva categoría
	# * delCat() :: Eliminamos la categoría 
	# ===================================================
	public function saveOrden() {
		$ordenado = [];
		# Obtenemos lista con el nuevo orden
		$nuevo_orden = 1;
		foreach (explode(',', $_POST["cats"]) as $orden) {
			db_exec([__FILE__, __LINE__], 'query', "UPDATE p_categorias SET c_orden = ".$nuevo_orden." WHERE cid = ".$orden);
			array_push($ordenado, $nuevo_orden);
			$nuevo_orden++;
		}
	}
	public function getCat() {
		# Obtenemos la ID de la categoría
		$cid = (int)$_GET['cid'];
		# Obtenemos la información
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT cid, c_orden, c_nombre, c_seo, c_img FROM p_categorias WHERE cid = '.$cid.' LIMIT 1'));
		# Retornamos los daots
		return $data;
	}
	public function saveCat() {
		global $tsCore;
		# Obtenemos la ID de la categoría
		$cid = (int)$_GET['cid'];
		$nombre = $tsCore->setSecure($tsCore->parseBadWords($_POST['c_nombre']));
		$categoria = $tsCore->getIUP([
			"nombre" => $nombre,
			"seo" => $tsCore->setSEO($nombre),
			"img" => $tsCore->setSecure($_POST['c_img']),
		], 'c_');
		# Guardamos en la tabla
		if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE `p_categorias` SET '.$categoria.' WHERE cid = ' . $cid)) return true;
	}
	public function MoveCat() {
		$new = (int)$_POST['newcid'];
		$old = (int)$_POST['oldcid'];
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE p_posts SET post_category = $new WHERE post_category = $old")) return true;
	}
	public function newCat() {
		global $tsCore;
		# Valores
		$c_nombre = $tsCore->setSecure($tsCore->parseBadWords($_POST['c_nombre']));
		# Orden
		$orden = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(cid) AS total FROM p_categorias'))['total'] + 1;
		# Insertamos los datos
		if (insertInto([__FILE__, __LINE__], 'p_categorias', ['orden' => $orden, 'nombre' => $c_nombre, 'seo' => $tsCore->setSEO($c_nombre), 'img' => $tsCore->setSecure($_POST['c_img'])], 'c_')) return true;
	}
	public function delCat() {
		global $tsCore;
		//
		$cid = (int)$_GET['cid'];
		$ncid = (int)$_POST['ncid'];
		// MOVER
		if (empty($ncid) and $ncid === 0) return 'Antes de eliminar una categor&iacute;a debes elegir a donde mover sus subcategor&iacute;as.';
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE p_posts SET post_category = $ncid WHERE post_category = $cid")) {
			if(deleteID([__FILE__, __LINE__], 'p_categorias', "cid = $cid")) return true;
		// SI LLEGÓ HASTA AQUI HUBO UN ERROR.
		} else return 'Lo sentimos ocurri&oacute; un error';
	}
	# ===================================================
	# RANGOS
	# * getRangos() :: Obtenemos todos los rangos
	# * getRango() :: Obtenemos el rango
	# * getRangoUsers() :: Rangos de los usuarios
	# * sameArrayRango() :: Evitamos que se repitan
	# * saveRango() :: Guardamos rango
	# * newRango() :: Nuevo rango
	# * delRango() :: Eliminamos el rango 
	# * SetDefaultRango() :: Rango por defecto
	# ===================================================
	public function getRangos() {
		global $tsCore;
		// RANGOS SIN PUNTOS
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM u_rangos ORDER BY rango_id, r_cant');
		// ARMAR ARRAY
		while ($row = db_exec('fetch_assoc', $query))  {
			$extra = unserialize($row['r_allows']);
			$data[$row['r_type'] == 0 ? 'regular' : 'post'][$row['rango_id']] = [
				'id' => $row['rango_id'],
				'name' => $row['r_name'],
				'color' => $row['r_color'],
				'imagen' => $row['r_image'],
				'cant' => $row['r_cant'],
				'max_points' => $extra['gopfp'],
				'user_puntos' => $extra['gopfd'],
				'type' => $row['r_type'],
				'num_members' => 0
			];
		}
		db_exec('free_result', $query);
		// NUMERO DE USUARIOS EN CADA RANGO
		if (!empty($data['post'])) {
			$IN = implode(', ', array_keys($data['post']));
			$query = db_exec([__FILE__, __LINE__], 'query', "SELECT user_rango AS ID_GROUP, COUNT(user_id) AS num_members FROM u_miembros WHERE user_rango IN ($IN) GROUP BY user_rango");
			while ($row = db_exec('fetch_assoc', $query)) {
				$data['post'][$row['ID_GROUP']]['num_members'] += $row['num_members'];
			}
			db_exec('free_result', $query);
		}
		// NUMERO DE USUARIOS EN RANGOS REGULARES
		if (!empty($data['regular'])) {
			$IN = implode(', ', array_keys($data['regular']));
			$query = db_exec([__FILE__, __LINE__], 'query', "SELECT user_rango AS ID_GROUP, COUNT(*) AS num_members FROM u_miembros WHERE user_rango IN ($IN) GROUP BY user_rango");
			while ($row = db_exec('fetch_assoc', $query)) {
				 $data['regular'][$row['ID_GROUP']]['num_members'] += $row['num_members'];
			}
			db_exec('free_result', $query);
		}
		//
		return $data;
	}
	public function getRango() {
		$rid = (int)$_GET['rid'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT * FROM u_rangos WHERE rango_id = $rid LIMIT 1"));
		$data['permisos'] = unserialize($data['r_allows']);
		return $data;
	}
	public function getRangoUsers() {
		global $tsCore;
		//
		$rid = (int)$_GET['rid'];
		$max = 10; // MAXIMO A MOSTRAR
		// TIPO DE BUSQUEDA
		$type = $_GET['t'];
		// SELECCIONAMOS
		$limit = $tsCore->setPageLimit($max, true);
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT u.user_id, u.user_name, u.user_email, u.user_registro, u.user_lastlogin FROM u_miembros AS u WHERE u.user_rango = $rid LIMIT $limit"));
		// PAGINAS
		list($total) = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(*) FROM u_miembros WHERE user_rango = $rid LIMIT $limit"));
		$data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] . '/admin/rangos?act=list&rid=' . $rid . '&t=' . $type . '', $_GET['s'], $total, $max);
		//
		return $data;
	}
	private function sameArrayRango(array $post = []) {
		global $tsCore;
		$retornar = [
			'name' => $tsCore->setSecure($tsCore->parseBadWords($post['rName'])),
			'color' => $tsCore->setSecure($post['rColor']),
			'image' => $tsCore->setSecure($post['r_img']),
			'cant' => empty($post['global-cantidadrequerida']) ? 0 : (int)$post['global-cantidadrequerida'],
			'type' => $post['global-type'] > 4 ? 0 : $post['global-type'],
			'allows' => self::optionsRange($post)
		];
		if (empty($retornar['name'])) return 'Debes ingresar el nombre del nuevo rango.';
		if ($post['global-pointsforposts'] > $post['global-pointsforday']) return 'El rango no puede dar m&aacute;s puntos de los que tiene al d&iacute;a.';
		return $retornar;
	}
	public function saveRango() {
		global $tsCore;
		//
		$rid = (int)$_GET['rid'];
		$r = self::sameArrayRango($_POST);
		$set = $tsCore->getIUP($r, 'r_');
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE u_rangos SET $set WHERE rango_id = $rid")) return true;
		else exit( show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db') );
	}
	public function newRango() {
		global $tsCore;
		$r = self::sameArrayRango($_POST);
		// Insertamos los datos
		if (insertInto([__FILE__, __LINE__], 'u_rangos', $r, 'r_')) return true;
	}
	public function delRango() {
		global $tsCore;
		//
		$rid = (int)$_GET['rid'];
		//
		if ($rid > 3) {
			$new_rango = (int)$_POST['new_rango'];
			if (db_exec([__FILE__, __LINE__], 'query', "UPDATE u_miembros SET user_rango = $new_rango WHERE user_rango = $rid")) {
				if (deleteID([__FILE__, __LINE__], 'u_rangos', "rango_id = $rid")) return true;
			}
		} else return 'No es posible eliminar este rango';
	}
	public function SetDefaultRango() {
		global $tsCore;
		//
		$url = $tsCore->settings['url'].'/admin/rangos';
		if($_SERVER['HTTP_REFERER'] == "$url?save=true" || $_SERVER['HTTP_REFERER'] == $url) {
			$rid = (int)$_GET['rid'];
			//
			$dato = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT rango_id, r_type FROM u_rangos WHERE rango_id = $rid LIMIT 1"));
			if (!empty($dato['rango_id']) && $dato['r_type'] == 0) {
				if (db_exec([__FILE__, __LINE__], 'query', "UPDATE w_configuracion SET c_reg_rango = $rid WHERE tscript_id = 1")) return true;
			} else return 'El rango no existe o no es posible utilizarlo';
		} else return 'Petici&oacute;n inv&aacute;lida';
	}
	 
	 /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	 // ADMINISTRAR USUARIOS \\
	 /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	 /*
	 getUsuarios()
	 */
	 function getUsuarios()
	 {
		  global $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);
		  //
		  if ($_GET['o'] == 'e')
		  {
				$order = 'u.user_activo, u.user_baneado';
		  } elseif ($_GET['o'] == 'c')
		  {
				$order = 'u.user_email';
		  } elseif ($_GET['o'] == 'i')
		  {
				$order = 'u.user_last_ip';
		  } elseif ($_GET['o'] == 'u')
		  {
				$order = 'u.user_lastactive';
		  } else
		  {
				$order = 'u.user_id';
		  }
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.*, r.*, p.* FROM u_perfil AS p LEFT JOIN u_miembros AS u ON u.user_id = p.user_id LEFT JOIN u_rangos AS r ON r.rango_id = u.user_rango ORDER BY ' .
				$order . ' ' . ($_GET['m'] == 'a' ? 'ASC' : 'DESC') . ' LIMIT ' . $limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM u_miembros WHERE user_id > \'0\'');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] . "/admin/users?o=" .
				$_GET['o'] . "&m=" . $_GET['m'] . "", $_GET['s'], $total, $max);
		  //
		  return $data;
	 }

	 /*
	 getUserData()
	 */
	 function getUserPrivacidad()
	 {
		  global $tsCore;
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT p_configs FROM u_perfil WHERE user_id = \'' . (int)
				$_GET['uid'] . '\' LIMIT 1');
		  $data = db_exec('fetch_assoc', $query);
		  $data['p_configs'] = unserialize($data['p_configs']);
		  //
		  return $data;
	 }
	 /*
	 getUserData()
	 */
	 function setUserPrivacidad()
	 {
		  global $tsCore;
		  //
		  $muro_firm = ($_POST['muro_firm'] > 4) ? 5 : $_POST['muro_firm'];
		  $see_hits = ($_POST['last_hits'] == 1 || $_POST['last_hits'] == 2) ? 0 : $_POST['last_hits'];
		  $array = array(
				'm' => $_POST['muro'],
				'mf' => $muro_firm,
				'rmp' => $_POST['rec_mps'],
				'hits' => $see_hits);
		  $perfilData['configs'] = serialize($array);
		  //
		  //
		  $updates = $tsCore->getIUP($perfilData, 'p_');
		  if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_perfil SET ' . $updates . ' WHERE user_id = \'' . (int)
				$_GET['uid'] . '\''))
				return true;

	 }
	 /*
	 getUserData()
	 */
	 function getUserData()
	 {
		  global $tsCore;
		  //
		  $user_id = $tsCore->setSecure($_GET['uid']);
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.*, r.*, p.* FROM u_perfil AS p LEFT JOIN u_miembros AS u ON u.user_id = p.user_id LEFT JOIN u_rangos AS r ON r.rango_id = u.user_rango WHERE u.user_id = \'' .
				(int)$user_id . '\' LIMIT 1');
		  $data = db_exec('fetch_assoc', $query);
		  $data['p_configs'] = unserialize($data['p_configs']);
		  //
		  return $data;
	 }
	 /*
	 setUserData
	 */
	 function setUserData($user_id)
	 {
		  global $tsCore;
		  # DATA
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT `user_name`, `user_email`, `user_password` FROM u_miembros WHERE user_id = \'' .
				(int)$user_id . '\'');
		  $data = db_exec('fetch_assoc', $query);
		  # LOCALS
		  $email = empty($_POST['email']) ? $data['user_email'] : $_POST['email'];
		  $password = $_POST['pwd'];
		  $cpassword = $_POST['cpwd'];
		  $user_nick = empty($_POST['nick']) ? $data['user_name'] : $_POST['nick'];
		  $user_points = empty($_POST['points']) ? $data['user_puntos'] : $_POST['points'];
		  $pointsxdar = empty($_POST['pointsxdar']) ? $data['user_puntos'] : $_POST['pointsxdar'];
		  $changenames = empty($_POST['changenicks']) ? $data['user_name_changes'] : $_POST['changenicks'];
		  #

		  if (!filter_var($email, FILTER_VALIDATE_EMAIL))
				return 'Correo electr&oacute;nico incorrecto';
		  if ($user_points >= 0)
		  {
				$apoints = ', user_puntos = \'' . (int)$user_points . '\'';
		  } else
				return 'Los puntos del usuario no se reconocen';
		  if ($changenames >= 0)
		  {
				$changedis = ', user_name_changes = \'' . (int)$changenames . '\'';
		  } else
				return 'Las disponibilidades de cambios de nombre de usuario deben ser num&eacute;ricas.';
		  if ($pointsxdar >= 0)
		  {
				$pxd = ', user_puntosxdar = \'' . (int)$pointsxdar . '\'';
		  } else
				return 'Los puntos para dar no se reconocen';
		  if (!empty($password) && !empty($cpassword))
		  {

				if (strlen($user_nick) < 3)
					 return 'Nick demasiado corto.';
				if (!preg_match('/^([A-Za-z0-9]+)$/', $user_nick))
					 return 'Nick inv&aacute;lido';
				$new_nick = ', user_name = \'' . $tsCore->setSecure($user_nick) . '\'';

				if (strlen($password) < 6)
					 return 'Contrase&ntilde;a no v&aacute;lida.';
				if ($password != $cpassword)
					 return 'Las contrase&ntilde;as no coinciden';
				$new_key = $tsCore->createPassword($user_nick, $password);
				$db_key = ', user_password = \'' . $tsCore->setSecure($new_key) . '\'';
		  }

		  if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE `u_miembros` SET user_email = \'' . $tsCore->setSecure($email) .
				'\' ' . $changedis . ' ' . $new_nick . ' ' . $pxd . ' ' . $apoints . ' ' . $db_key .
				' WHERE user_id = \'' . (int)$user_id . '\''))
		  {

				if ($_POST['sendata'])
					 mail($email, 'Nuevos datos de acceso', 'Sus datos de acceso a ' . $tsCore->
						  settings['titulo'] .
						  ' han sido cambiados por un administrador. Los nuevos datos son: usuario: ' . $user_nick .
						  ', contraseña: ' . $password . '. Disculpe las molestias', 'From: ' . $tsCore->settings['titulo'] . ' <no-reply@' . $tsCore->settings['domain'] . '>'); // FIX: 30/06/2014

				return true;
		  }
	 }
	 function deleteContent($user_id){
		  global $tsUser;
		  
		  if(db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT user_id FROM u_miembros WHERE user_id = \''.$tsUser->uid.'\' && user_password = \''.md5(md5($_POST['password']).$tsUser->nick).'\''))){
		  $c = $_POST['bocuenta'];
		  
		  if($_POST['boposts'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM p_posts WHERE post_user = \''.$user_id.'\'');
		  if($_POST['bofotos'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM f_fotos WHERE f_user = \''.$user_id.'\'');
		  if($_POST['boestados'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_muro WHERE p_user_pub = \''.$user_id.'\'');
		  if($_POST['bocomposts'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM p_comentarios WHERE c_user = \''.$user_id.'\'');
		  if($_POST['bocomfotos'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM f_comentarios WHERE c_user = \''.$user_id.'\'');
		  if($_POST['bocomestados'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_muro_comentarios WHERE c_user = \''.$user_id.'\'');
		  if($_POST['bolikes'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_muro_likes WHERE user_id = \''.$user_id.'\'');
		  if($_POST['boseguidores'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_follows WHERE f_id = \''.$user_id.'\' && f_type = \'1\'');
		  if($_POST['bosiguiendo'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_follows WHERE f_user = \''.$user_id.'\' && f_type = \'1\'');
		  if($_POST['bofavoritos'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM p_favoritos WHERE fav_user = \''.$user_id.'\''); // FIX: 14/12/2014 - 1.1.000.9
		  if($_POST['bovotosposts'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM p_votos WHERE tuser = \''.$user_id.'\'');
		  if($_POST['bovotosfotos'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM f_votos WHERE v_user = \''.$user_id.'\'');
		  if($_POST['boactividad'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_actividad WHERE user_id = \''.$user_id.'\'');
		  if($_POST['boavisos'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_avisos WHERE user_id = \''.$user_id.'\'');
		  if($_POST['bobloqueos'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_bloqueos WHERE b_user = \''.$user_id.'\'');
		  if($_POST['bomensajes'] || $c) { db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_mensajes WHERE mp_from = \''.$user_id.'\'');  db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_respuestas WHERE mr_from = \''.$user_id.'\''); }
		  if($_POST['bosesiones'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_sessions WHERE session_user_id = \''.$user_id.'\'');
		  if($_POST['bovisitas'] || $c) db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM w_visitas WHERE user = \''.$user_id.'\'');
		  
		  $data = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT user_name FROM u_miembros WHERE user_id = \''.$user_id.'\''));
		  $admin = db_exec('fetch_row', db_exec([__FILE__, __LINE__], 'query', 'SELECT user_email FROM u_miembros WHERE user_id = \'1\''));
		  
		  if($c && $tsUser->uid != $user_id){
				db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_miembros WHERE user_id = \''.$user_id.'\'');
				db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_perfil WHERE user_id = \''.$user_id.'\'');
				db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_portal WHERE user_id = \''.$user_id.'\'');
				db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM w_denuncias WHERE d_user = \''.$user_id.'\'');
				db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_bloqueos WHERE b_auser = \''.$user_id.'\'');
				db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_mensajes WHERE mp_to = \''.$user_id.'\'');
				db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM w_visitas WHERE `for` = \''.$user_id.'\' && type = \'1\'');
		  }
		  
		  db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO `u_avisos` (`user_id`, `av_subject`, `av_body`, `av_date`, `av_read`, `av_type`) VALUES (\'1\', \'Contenido eliminado\', \'Hola, le informamos que el administrador '.$tsUser->nick.' ('.$tsUser->uid.') ha eliminado '.($c ? 'la cuenta' : 'varios contenidos').' de '.$data[0].'.\', \''.time().'\', \'0\', \'1\')');
		  mail($admin[0], 'Contenido eliminado', '<html><head><title>Contenido de cierta cuenta han sido eliminados.</title></head><body><p>Hola, le informamos que el administrador '.$tsUser->nick.' ('.$tsUser->uid.') ha eliminado '.($c ? 'la cuenta' : 'varios contenidos').' de '.$data[0].'</p></body></html>', 'Content-type: text/html; charset=iso-8859-15');
		  return 'OK';
		}else return 'Credenciales incorrectas';
	 }
	 
	 /*
	 getUserRango
	 */
	 function getUserRango($user_id)
	 {

		  # CONSULTA
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_rango, r.rango_id, r.r_name, r.r_color FROM u_miembros AS u LEFT JOIN u_rangos AS r ON u.user_rango = r.rango_id WHERE u.user_id = \'' .
				(int)$user_id . '\' LIMIT 1');
		  $data['user'] = db_exec('fetch_assoc', $query);

		  # RANGOS DISPONIBLES
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT `rango_id`, `r_name`, `r_color` FROM `u_rangos`');
		  $data['rangos'] = result_array($query);

		  #
		  return $data;
	 }

	 /*
	 getAllRangos
	 */
	 function getAllRangos()
	 {

		  # RANGOS DISPONIBLES
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT `rango_id`, `r_name`, `r_color` FROM `u_rangos`');
		  $data = result_array($query);

		  #
		  return $data;
	 }
	 /*
	 setUserRango($user_id)
	 */
	 function setUserRango($user_id)
	 {
		  global $tsUser;
		  # SOLO EL PRIMER ADMIN PUEDE PONER A OTROS ADMINS
		  $new_rango = (int)$_POST['new_rango'];
		  if ($user_id == $tsUser->uid)
				return 'No puedes cambiarte el rango a ti mismo';
		  elseif ($tsUser->uid != 1 && $new_rango == 1)
				return 'Solo el primer Administrador puede crear más administradores principales';
		  else
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_miembros SET user_rango = \'' . (int)$new_rango . '\' WHERE user_id = \'' .
					 (int)$user_id . '\''))
					 return true;
		  }
	 }

	 function setUserFirma($user_id)
	 {
		  global $tsCore;

		  if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE `u_perfil` SET user_firma = \'' . $tsCore->setSecure($_POST['firma']) .
				'\' WHERE user_id = \'' . (int)$user_id . '\''))
				return true;

	 }

	 function setUserInActivo()
	 {
		  global $tsUser;

		  $usuario = $_POST['uid'];

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT user_activo FROM u_miembros WHERE user_id = \'' . (int)
				$usuario . '\'');
		  $data = db_exec('fetch_assoc', $query);


		  // COMPROBAMOS
		  if ($data['user_activo'] == 1)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_miembros SET user_activo = \'0\' WHERE user_id = \'' .
					 (int)$usuario . '\''))
				{
					 return '2: Cuenta desactivada';
				} else
					 return '0: Ocurri&oacute, un error';
		  } else
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_miembros SET user_activo = \'1\' WHERE user_id = \'' .
					 (int)$usuario . '\''))
				{
					 return '1: Cuenta activada.';
				} else
					 return 'Ocurri&oacute; un error';
		  }
	 }

	 function getSessions()
	 {
		  global $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, s.* FROM u_sessions AS s LEFT JOIN u_miembros AS u ON s.session_user_id = u.user_id ORDER BY s.session_time DESC LIMIT ' .
				$limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM u_sessions');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] .
				"/admin/sesiones?", $_GET['s'], $total, $max);
		  //
		  return $data;
	 }

	 function delSession()
	 {
		  global $tsCore;
		  $session_id = $_POST['sesion_id'];
		  if (db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT session_id FROM u_sessions WHERE session_id = \'' .
				$tsCore->setSecure($session_id) . '\' LIMIT 1')))
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM u_sessions WHERE session_id = \'' . $tsCore->
					 setSecure($session_id) . '\''))
					 return '1: Eliminado';
		  } else
				return '0: No existe esa sesi&oacute;n';
	 }

	 function getChangeNicks()
	 {
		  global $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, n.* FROM u_nicks AS n LEFT JOIN u_miembros AS u ON n.user_id = u.user_id WHERE estado = \'0\' ORDER BY n.time DESC LIMIT ' .
				$limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM u_nicks WHERE estado = \'0\'');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] . "/admin/nicks?",
				$_GET['s'], $total, $max);
		  //
		  return $data;
	 }

	 function getChangeNicks_A()
	 {
		  global $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, n.* FROM u_nicks AS n LEFT JOIN u_miembros AS u ON n.user_id = u.user_id WHERE estado > \'0\' ORDER BY n.time DESC LIMIT ' .
				$limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM u_nicks WHERE estado > \'0\'');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] . "/admin/nicks?",
				$_GET['s'], $total, $max);
		  //
		  return $data;
	 }

	 function ChangeNick_o_no()
	 {
		  global $tsCore, $tsMonitor;
		  //
		  $nick_id = $_POST['nid'];
		  //
		  $datos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM u_nicks WHERE id = \'' . (int)
				$nick_id . '\' LIMIT 1'));
		  //
		  if ($_POST['accion'] == 'aprobar')
		  {
				db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_miembros SET user_name = \'' . $datos['name_2'] . '\', user_password = \'' .
					 $datos['hash'] . '\', user_name_changes = user_name_changes - 1 WHERE user_id = \'' .
					 $datos['user_id'] . '\'');
				db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_nicks SET estado = \'1\' WHERE id = \'' . (int)$nick_id .
					 '\'');
				// AVISO
				$aviso = 'Hola <b>' . $datos['name_1'] . "</b>,\n\n Le informo que desde este momento su nombre de acceso ser&aacute; <b>" .
					 $datos['name_2'] . "</b> . Hasta pronto.";
				$tsMonitor->setAviso($datos['user_id'], 'Cambio realizado', $aviso, 4);
				//ENVIAMOS CORREO
				$subject = $datos['name_1'] . ', su petici&oacute;n de cambio ha sido aceptada';
				$body = 'Hola ' . $datos['name_1'] . ':<br />
				Le enviamos este email para informarle que su petici&oacute;n de cambio de nick ha sido aceptada.
				Desde este momento, podr&aacute; acceder en ' . $tsCore->settings['titulo'] .
					 ' con el nombre de usuario ' . $datos['name_2'] . '. <br /><br />
				El staff de <strong>' . $tsCore->settings['titulo'] . '</strong>';
		  } elseif ($_POST['accion'] == 'denegar')
		  {
				db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_miembros SET user_name_changes = user_name_changes - 1 WHERE user_id = \'' .
					 $datos['user_id'] . '\'');
				db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_nicks SET estado = \'2\' WHERE id = \'' . (int)$nick_id .
					 '\'');
				// AVISO
				$aviso = 'Hola <b>' . $datos['name_1'] . "</b>,\n\n Lamento informarle que su petici&oacute;n de cambio de nick a <b>" .
					 $datos['name_2'] . "</b> , ha sido denegada.";
				$tsMonitor->setAviso($datos['user_id'], 'Cambio realizado', $aviso, 3);
				//ENVIAMOS CORREO
				$subject = $datos['name_1'] . ', su petici&oacute;n de cambio ha sido denegada';
				$body = 'Hola ' . $datos['name_1'] . ':<br />
				Le enviamos este email para informarle que su petici&oacute;n de cambio de nick ha sido denegada. <br /><br />
				El staff de <strong>' . $tsCore->settings['titulo'] . '</strong>';
		  } else
				return '0: Mijo, ve de paseo';

		  // <--
		  include (TS_ROOT . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'class' .
				DIRECTORY_SEPARATOR . 'c.emails.php');
		  $tsEmail = new tsEmail('confirmar', 'nombre');
		  $tsEmail->emailTo = $datos['user_email'];
		  $tsEmail->emailSubject = $subject;
		  $tsEmail->emailBody = $body;
		  $tsEmail->emailHeaders = $tsEmail->setEmailHeaders();
		  $tsEmail->sendEmail($from, $to, $subject, $body) or die('0: Hubo un error al enviar el correo.');
		  die('1: <div class="box_cuerpo" style="padding: 12px 20px; border-top:1px solid #CCC">Hemos enviado un correo a <b>' .
				$datos['user_email'] .
				'</b> con la decisi&oacute;n tomada. Tambi&eacute;n le hemos enviado un aviso al usuario.</div>');
		  // -->
	 }


	 /****************** ADMINISTRACIÓN DE POSTS ******************/

	 function GetAdminPosts()
	 {
		  global $tsCore;
		  //
		  $max = 18; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);

		  if ($_GET['o'] == 'e')
		  {
				$order = 'p.post_status';
		  } elseif ($_GET['o'] == 'ip')
		  {
				$order = 'p.post_ip';
		  } else
		  {
				$order = 'p.post_id';
		  }

		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, c.c_nombre, c.c_seo, c.c_img, p.* FROM p_posts AS p LEFT JOIN u_miembros AS u ON p.post_user = u.user_id LEFT JOIN p_categorias AS c ON c.cid = p.post_category WHERE p.post_id > \'0\' ORDER BY ' .
				$order . ' ' . ($_GET['m'] == 'a' ? 'ASC' : 'DESC') . ' LIMIT ' . $limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM p_posts WHERE post_id > \'0\'');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] . "/admin/posts?o=" .
				$_GET['o'] . "&m=" . $_GET['m'] . "", $_GET['s'], $total, $max);
		  //
		  return $data;
	 }


	 /****************** ADMINISTRACIÓN DE FOTOS ******************/
	 function GetAdminFotos()
	 {
		  global $tsCore;
		  //
		  $max = 15; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, f.* FROM f_fotos AS f LEFT JOIN u_miembros AS u ON f.f_user = u.user_id WHERE f.foto_id > \'0\' ORDER BY f.foto_id DESC LIMIT ' .
				$limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM f_fotos WHERE foto_id > \'0\'');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] . "/admin/fotos?",
				$_GET['s'], $total, $max);
		  //
		  return $data;
	 }

	 function DelFoto()
	 {
		  //
		  $foto = intval($_POST['foto_id']);
		  if (db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT foto_id FROM `f_fotos` WHERE foto_id = \'' .
				(int)$foto . '\'')))
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM f_fotos WHERE foto_id = \'' . (int)$foto . '\''))
				{
					 return '1: Foto eliminada';
				} else
					 return '0: La foto no se pudo eliminar';
		  } else
				return '0: La foto no existe';

	 }

	 function setOpenClosedFoto()
	 {
		  global $tsUser;

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT f_closed FROM f_fotos WHERE foto_id = \'' . (int)$_POST['fid'] .
				'\'');
		  $data = db_exec('fetch_assoc', $query);

		  // COMPROBAMOS
		  if ($data['f_closed'] == 1)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE f_fotos SET f_closed = \'0\' WHERE foto_id = \'' . (int)
					 $_POST['fid'] . '\''))
				{
					 return '2: Comentarios abiertos';
				} else
					 return '0: Ocurri&oacute, un error';
		  } elseif ($data['f_closed'] == 0)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE f_fotos SET f_closed = \'1\' WHERE foto_id = \'' . (int)
					 $_POST['fid'] . '\''))
				{
					 return '1: Comentarios cerrados.';
				} else
					 return 'Ocurri&oacute; un error';
		  }
	 }


	 function setShowHideFoto()
	 {
		  global $tsUser;

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT f_status FROM f_fotos WHERE foto_id = \'' . (int)$_POST['fid'] .
				'\'');
		  $data = db_exec('fetch_assoc', $query);


		  // COMPROBAMOS
		  if ($data['f_status'] == 1)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE f_fotos SET f_status = \'0\' WHERE foto_id = \'' . (int)
					 $_POST['fid'] . '\''))
				{
					 return '2: Foto rehabilitada';
				} else
					 return '0: Ocurri&oacute, un error';
		  } elseif ($data['f_status'] == 0)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE f_fotos SET f_status = \'1\' WHERE foto_id = \'' . (int)
					 $_POST['fid'] . '\''))
				{
					 return '1: Foto deshabilitada.';
				} else
					 return 'Ocurri&oacute; un error';
		  }
	 }


	 /****************** ADMINISTRACIÓN DE NOTICIAS ******************/

	 function setNoticiaInActive()
	 {
		  global $tsUser;

		  $noticia = $_POST['nid'];

		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT not_active FROM w_noticias WHERE not_id = \'' . (int)
				$noticia . '\'');
		  $data = db_exec('fetch_assoc', $query);


		  // COMPROBAMOS
		  if ($data['not_active'] == 1)
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE w_noticias SET not_active = \'0\' WHERE not_id = \'' . (int)
					 $noticia . '\''))
				{
					 return '2: Noticia desactivada';
				} else
					 return '0: Ocurri&oacute, un error';
		  } else
		  {
				if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE w_noticias SET not_active = \'1\' WHERE not_id = \'' . (int)
					 $noticia . '\''))
				{
					 return '1: Noticia activada.';
				} else
					 return 'Ocurri&oacute; un error';
		  }
	 }

	 /****************** ADMINISTRACIÓN DE LISTA NEGRA ******************/

	 function getBlackList()
	 {
		  global $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, b.* FROM w_blacklist AS b LEFT JOIN u_miembros AS u ON b.author = u.user_id ORDER BY b.date DESC LIMIT ' .
				$limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM w_blacklist');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] .
				"/admin/blacklist?", $_GET['s'], $total, $max);
		  //
		  return $data;
	 }

	 function getBlock()
	 {
		  return db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT type, value, reason FROM w_blacklist WHERE id = \'' .
				(int)$_GET['id'] . '\' LIMIT 1'));
	 }

	 function saveBlock()
	 {
		  global $tsCore, $tsUser;

		  if (empty($_POST['value']) || empty($_POST['type']))
		  {
				return 'Debe rellenar todos los campos';
		  } else
		  {
				if ($_POST['type'] == 1 && $_POST['value'] == $_SERVER['REMOTE_ADDR'])
					 return 'No puedes bloquear tu propia IP';
				if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT id FROM w_blacklist WHERE type = \'' . (int)
					 $_POST['type'] . '\' && value = \'' . $tsCore->setSecure($_POST['value']) . '\'')))
				{
					 if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE w_blacklist SET type = \'' . (int)$_POST['type'] . '\', value = \'' .
						  $tsCore->setSecure($_POST['value']) . '\', author = \'' . $tsUser->uid . '\' WHERE id = \'' .
						  (int)$_GET['id'] . '\''))
						  return true;
				} else
					 return 'Ya existe un bloqueo as&iacute;';
		  }
	 }

	 function newBlock()
	 {
		  global $tsCore, $tsUser;

		  if (empty($_POST['value']) || empty($_POST['type']) || empty($_POST['reason']))
		  {
				return 'Rellene todos los campos';
		  } else
		  {
				if ($_POST['type'] == 1 && $_POST['value'] == $_SERVER['REMOTE_ADDR'])
					 return 'No puedes bloquear tu propia IP';
				if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT id FROM w_blacklist WHERE type = \'' . (int)
					 $_POST['type'] . '\' && value = \'' . $tsCore->setSecure($_POST['value']) . '\'')))
				{
					 if (db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO w_blacklist (type, value, reason, author, date) VALUES (\'' .
						  (int)$_POST['type'] . '\', \'' . $tsCore->setSecure($_POST['value']) . '\', \'' .
						  $tsCore->setSecure($_POST['reason']) . '\', \'' . $tsUser->uid . '\', \'' . time
						  () . '\')'))
						  return true;
				} else
					 return 'Ya existe un bloqueo as&iacute;';
		  }
	 }

	 function deleteBlock()
	 {

		  if (db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM w_blacklist WHERE id = \'' . (int)$_POST['bid'] . '\''))
				return '1: Bloqueo retirado';
		  else
				return '0: Hubo un error al borrar';

	 }

	 /****************** ADMINISTRACIÓN DE LISTA NEGRA ******************/

	 function getBadWords()
	 {
		  global $tsCore;
		  //
		  $max = 20; // MAXIMO A MOSTRAR
		  $limit = $tsCore->setPageLimit($max, true);
		  //
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT u.user_id, u.user_name, bw.* FROM w_badwords AS bw LEFT JOIN u_miembros AS u ON bw.author = u.user_id ORDER BY bw.wid DESC LIMIT ' .
				$limit);
		  //
		  $data['data'] = result_array($query);

		  // PAGINAS
		  $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(*) FROM w_badwords');
		  list($total) = db_exec('fetch_row', $query);

		  $data['pages'] = $tsCore->pageIndex($tsCore->settings['url'] .
				"/admin/badwords?", $_GET['s'], $total, $max);
		  //
		  return $data;
	 }

	 function getBadWord()
	 {
		  return db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM w_badwords WHERE wid = \'' .
				(int)$_GET['id'] . '\' LIMIT 1'));
	 }

	 function saveBadWord()
	 {
		  global $tsCore, $tsUser;

		  $method = empty($_POST['method']) ? 0 : 1;
		  $type = empty($_POST['type']) ? 0 : 1;
		  if (empty($_POST['before']) || empty($_POST['after']))
		  {
				return 'Rellene todos los campos';
		  } else
		  {
				if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT wid FROM w_badwords WHERE LOWER(word) = \'' .
					 $tsCore->setSecure(strtolower($_POST['before'])) . '\' && LOWER(swop) = \'' . $tsCore->
					 setSecure(strtolower($_POST['after'])) . '\'')))
				{
					 if (db_exec([__FILE__, __LINE__], 'query', 'UPDATE `w_badwords` SET method = \'' . $method . '\', type = \'' .
						  (int)$type . '\', word = \'' . $tsCore->setSecure($_POST['before']) . '\', swop = \'' .
						  $tsCore->setSecure($_POST['after']) . '\', author = \'' . $tsUser->uid . '\' WHERE wid = \'' .
						  (int)$_GET['id'] . '\''))
						  return true;
					 else
						  return 'Error al guardar';
				} else
					 return 'Ya existe un filtro as&iacute;';
		  }
	 }

	 function newBadWord()
	 {
		  global $tsCore, $tsUser;

		  $method = empty($_POST['method']) ? 0 : 1;
		  $type = empty($_POST['type']) ? 0 : 1;
		  if (empty($_POST['before']) || empty($_POST['after']) || empty($_POST['reason']))
		  {
				return 'Rellene todos los campos';
		  } else
		  {
				if (!db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', 'SELECT wid FROM w_badwords WHERE LOWER(word) = \'' .
					 $tsCore->setSecure(strtolower($_POST['before'])) . '\' && LOWER(swop) = \'' . $tsCore->
					 setSecure(strtolower($_POST['after'])) . '\'')))
				{
					 if (db_exec([__FILE__, __LINE__], 'query', 'INSERT INTO w_badwords (word, swop, method, type, author, reason, date) VALUES (\'' .
						  $tsCore->setSecure($_POST['before']) . '\', \'' . $tsCore->setSecure($_POST['after']) .
						  '\', \'' . (int)$method . '\', \'' . (int)$type . '\', \'' . $tsUser->uid . '\', \'' .
						  $tsCore->setSecure($_POST['reason']) . '\', \'' . time() . '\')'))
						  return true;
					 else
						  return 'Error al agregar';
				} else
					 return 'Ya existe un filtro as&iacute;';
		  }
	 }

	 function deleteBadWord()
	 {

		  if (db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM w_badwords WHERE wid = \'' . (int)$_POST['wid'] . '\''))
				return '1: Filtro retirado';
		  else
				return '0: Hubo un error al borrar';

	 }

	 /****************** ADMINISTRACIÓN DE ESTADÍSTICAS ******************/

	 function GetAdminStats()
	 {
		  $num = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT 
		  (SELECT count(foto_id) FROM f_fotos WHERE f_status = \'2\') as fotos_eliminadas, 
		  (SELECT count(foto_id) FROM f_fotos WHERE f_status = \'1\') as fotos_ocultas, 
		  (SELECT count(foto_id) FROM f_fotos WHERE f_status = \'0\') as fotos_visibles, 
		  (SELECT count(post_id) FROM p_posts WHERE post_status = \'0\') as posts_visibles, 
		  (SELECT count(post_id) FROM p_posts WHERE post_status = \'1\') as posts_ocultos, 
		  (SELECT count(post_id) FROM p_posts  WHERE post_status = \'2\') as posts_eliminados, 
		  (SELECT count(post_id) FROM p_posts  WHERE post_status = \'3\') as posts_revision, 
		  (SELECT count(cid) FROM p_comentarios WHERE c_status = \'0\') as comentarios_posts_visibles, 
		  (SELECT count(cid) FROM p_comentarios WHERE c_status = \'1\') as comentarios_posts_ocultos, 
		  (SELECT count(user_id) FROM u_miembros WHERE user_activo = \'1\') as usuarios_activos, 
		  (SELECT count(user_id) FROM u_miembros WHERE user_activo = \'0\' ) as usuarios_inactivos, 
		  (SELECT count(user_id) FROM u_miembros WHERE user_baneado = \'1\' ) as usuarios_baneados, 
		  (SELECT count(cid) FROM f_comentarios) as comentarios_fotos_total, 
		  (SELECT count(follow_id) FROM u_follows WHERE f_type  = \'1\' ) AS usuarios_follows,
		  (SELECT count(follow_id) FROM u_follows WHERE f_type  = \'2\' ) AS posts_follows,
		  (SELECT count(follow_id) FROM u_follows WHERE f_type  = \'3\' ) AS posts_compartidos,
		  (SELECT count(fav_id) FROM p_favoritos) AS posts_favoritos,  
		  (SELECT count(mr_id) FROM u_respuestas) AS usuarios_respuestas,
		  (SELECT count(mp_id) FROM u_mensajes) AS mensajes_total, 
		  (SELECT count(mp_id) FROM u_mensajes WHERE mp_del_to = \'1\') AS mensajes_de_eliminados,
		  (SELECT count(mp_id) FROM u_mensajes WHERE mp_del_from = \'1\') AS mensajes_para_eliminados,
		  (SELECT count(bid) FROM p_borradores) AS posts_borradores,
		  (SELECT count(bid) FROM u_bloqueos) AS usuarios_bloqueados, 
		  (SELECT count(bid) FROM u_bloqueos) AS usuarios_bloqueados,
		  (SELECT count(medal_id) FROM w_medallas WHERE m_type = \'1\') AS medallas_usuarios,
		  (SELECT count(medal_id) FROM w_medallas WHERE m_type = \'2\') AS medallas_posts,
		  (SELECT count(medal_id) FROM w_medallas WHERE m_type = \'3\') AS medallas_fotos,
		  (SELECT count(id) FROM w_medallas_assign) AS medallas_asignadas, 
		  (SELECT count(aid) FROM w_afiliados WHERE a_active = \'1\') AS afiliados_activos, 
		  (SELECT count(aid) FROM w_afiliados WHERE a_active = \'0\') AS afiliados_inactivos,
		  (SELECT count(pub_id) FROM u_muro) AS muro_estados, 
		  (SELECT count(cid) FROM u_muro_comentarios) AS muro_comentarios
		  '));

		  $num['usuarios_total'] = $num['usuarios_activos'] + $num['usuarios_inactivos'] +
				$num['usuarios_baneados'];
		  $num['seguidos_total'] = $num['posts_follows'] + $num['usuarios_follows'];
		  $num['muro_total'] = $num['muro_estados'] + $num['muro_comentarios'];
		  $num['afiliados_total'] = $num['afiliados_activos'] + $num['afiliados_inactivos'];
		  $num['posts_total'] = $num['posts_visibles'] + $num['posts_ocultos'] + $num['posts_eliminados'];
		  $num['comentarios_posts_total'] = $num['comentarios_posts_visibles'] + $num['comentarios_posts_ocultos'];
		  $num['medallas_total'] = $num['medallas_usuarios'] + $num['medallas_posts'] + $num['medallas_fotos'];
		  $num['fotos_total'] = $num['fotos_visibles'] + $num['fotos_ocultas'] + $num['fotos_eliminadas'];

		  return $num;
	 }

	 /*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/

}
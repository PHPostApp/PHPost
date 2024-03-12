<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control del monitor de usuario
 *
 * @name    c.monitor.php
 * @author  PHPost Team
 */
class tsMonitor {

	/**
	 * @name notificaciones 
	 * @access public
	 * @info NUMERO DE NOTIFICACIONES NUEVAS
	**/
	public $notificaciones = 0;

	/**
	 * @name avisos
	 * @access public
	 * @info NUMERO DE AVISOS/ALERTAS
	**/
	public $avisos = 0;    

	/**
	 * @name monitor
	 * @access private
	 * @info ORACIONES PARA CADA NOTIFICACION
	**/
	private $monitor = array();

	/**
	 * @name show_type
	 * @access public
	 * @info COMO MOSTRAREMOS LAS NOTIFICACIONES -> AJAX/NORMAL
	**/
	public $show_type = 1;

	/*
		constructor()
	*/
	public function __construct(){
		global $tsUser;
		// VISITANTE?
		if(empty($tsUser->is_member)) return false;
		// NOTIFICACIONES
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(not_id) AS total FROM u_monitor WHERE user_id = {$tsUser->uid} AND not_menubar > 0"));
		$this->notificaciones = $data['total'];
		/**
		 * AVISOS
		*/ 
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(av_id) AS total FROM u_avisos WHERE user_id = {$tsUser->uid} AND av_read = 0"));
		$this->avisos = $data['total'];
	}

	/**
	 * @name makeMonitor
	 * @access private
	 * @params none
	 * @return none
	*/
	private function makeMonitor(){
		$this->monitor = [
			1 => ['text' => 'agreg&oacute; a favoritos tu', 'ln_text' => 'post', 'css' => 'star'],
			2 => ['text' => ['coment&oacute; tu','_REP_ nuevos comentarios en tu'], 'ln_text' => 'post', 'css' => 'comment_post'],
			3 => ['text' => 'dej&oacute; _REP_ puntos en tu', 'ln_text' => 'post', 'css' => 'points'],
			4 => ['text' => 'te est&aacute; siguiendo', 'ln_text' => 'Seguir a este usuario', 'css' => 'follow'],
			5 => ['text' => 'cre&oacute; un nuevo', 'ln_text' => 'post', 'css' => 'post'],
			6 => ['text' => ['te recomienda un', '_REP_ usuarios te recomiendan un'], 'ln_text' => 'post', 'css' => 'share'],
			7 => ['text' => ['coment&oacute; en un', '_REP_ nuevos comentarios en el'], 'ln_text' => 'post', 'extra' => 'que sigues', 'css' => 'blue_ball'],
			8 => ['text' => ['vot&oacute; _REP_ tu', '_REP_ nuevos votos a tu'], 'ln_text' => 'comentario', 'css' => 'voto_'],
			9 => ['text' => ['respondi&oacute; tu', '_REP_ nuevas respuestas a tu'], 'ln_text' => 'comentario', 'css' => 'comment_resp'],
			10 => ['text' => 'subi&oacute; una nueva', 'ln_text' => 'foto', 'css' => 'photo'],
			11 => ['text' => ['coment&oacute; tu','_REP_ nuevos comentarios en tu'], 'ln_text' => 'foto', 'css' => 'photo'],
			12 => ['text' => 'public&oacute; en tu', 'ln_text' => 'muro', 'css' => 'wall_post'],
			13 => ['text' => ['coment&oacute; ', '_REP_ nuevos comentarios en'], 'ln_text' => 'publicaci&oacute;n', 'extra' => 'coment&oacute;', 'css' => 'w_comment'],
			14 => ['text' => ['le gusta tu', 'A _REP_ personas les gusta tu'], 'ln_text' => ['publicaci&oacute;n','comentario'], 'css' => 'w_like'],
			15 => ['text' => 'Recibiste una medalla', 'css' => 'medal'],
			16 => ['text' => 'Tu post recibi&oacute; una medalla', 'css' => 'medal'],
			17 => ['text' => 'Tu foto recibi&oacute; una medalla', 'css' => 'medal'],
			// COMUNIDADES
			18 => ['text' => ['cre&oacute; un nuevo'], 'ln_text' => 'tema', 'css' => 'post'],
			19 => ['text' => ['coment&oacute; tu','_REP_ nuevos comentarios en tu'], 'ln_text' => 'tema', 'css' => 'comment_post'],
			20 => ['text' => ['coment&oacute; en un', '_REP_ nuevos comentarios en el'], 'ln_text' => 'tema', 'extra' => ' que sigues', 'css' => 'blue_ball'],
			21 => ['text' => ['respondi&oacute; tu', '_REP_ nuevas respuestas a tu'], 'ln_text' => 'comentario', 'css' => 'comment_resp'],
			22 => ['text' => ['agreg&oacute; a favoritos tu','_REP_ nuevos favoritos a tu'], 'ln_text' => 'tema', 'css' => 'star'],
			23 => ['text' => ['te recomienda un', '_REP_ usuarios te recomiendan un'], 'ln_text' => 'tema', 'css' => 'share'],
			24 => ['text' => ['vot&oacute; _REP_ tu', '_REP_ nuevos votos a tu'], 'ln_text' => 'tema', 'css' => 'voto_'],
			25 => ['text' => ['vot&oacute; _REP_ tu', '_REP_ nuevos votos a tu'], 'ln_text' => 'comentario', 'css' => 'voto_'],
			26 => ['text' => ['est&aacute; siguiendo tu','_REP_ nuevos seguidores a tu'], 'ln_text' => 'tema', 'css' => 'follow'],
		];
	}

	/**
	 * @name setAviso
	 * @access public
	 * @param int, string, string
	 * @return bool
	 * @info ENVIA UN AVISO/ALERTA
	*/
	public function setAviso($user_id = 0, $subject = '(sin asunto)', $body = '', $type = 0){
		global $tsCore;
		# VERIFICAMOS QUE SE PUEDA ENVIAR EL AVISO
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_baneado FROM u_miembros WHERE user_id = $user_id LIMIT 1"));
		# NO PODEMOS ENVIAR A UN USUARIO BANEADO
		if($data['user_baneado'] === 1) return true;
		# INSERTAMOS EL AVISO
		return (insertInto([__FILE__, __LINE__], 'u_avisos', ['user_id' => $user_id, 'av_subject' => $tsCore->setSecure($subject), 'av_body' => $tsCore->setSecure($body), 'av_date' => time(), 'av_type' => $type])) ? true : false;
	}

	/**
	 * @name getAvisos
	 * @access public
	 * @param none
	 * @return array
	 * @info OBTIENE LOS MENSAJES Y ALERTAS DEL USUARIO
	 */
	public function getAvisos(){
		global $tsUser;
		# OBTENERMOS Y RETORNAMOS
		return result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM u_avisos WHERE user_id = ' . $tsUser->uid));
	}

	/**
	 * @name readAviso
	 * @access public
	 * @param int
	 * @return array
	 * @info ONTIENE UN AVISO
	*/
	public function readAviso(int $av_id = 0){
		global $tsUser;
		# OBTENEMOS
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT av_id, user_id, av_subject, av_body, av_date, av_read, av_type FROM u_avisos WHERE av_id = ' . (int)$av_id));
		# RETURN
		if(empty($data['av_id']) || $data['user_id'] != $tsUser->uid && !$tsUser->is_admod == 1) 
			return 'El aviso no existe';
		else {
			db_exec([__FILE__, __LINE__], 'query', 'UPDATE u_avisos SET av_read = 1 WHERE av_id = ' . (int)$av_id);
			$this->avisos = $this->avisos - 1;
			return $data;
		}
	}

	/**
	 * @name delAviso
	 * @access public
	 * @param int
	 * @return bool
	 * @info ELIMINA UN AVISO
	*/
	public function delAviso(int $av_id = 0){
		global $tsUser;
		  # OBTENEMOS
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT user_id FROM u_avisos WHERE av_id = ' . (int)$av_id));
		# RETURN
		if(empty($data['user_id']) || $data['user_id'] != $tsUser->uid && !$tsUser->is_admod == 1) return false;
		else {
			deleteID([__FILE__, __LINE__], 'u_avisos', 'av_id=' . (int)$av_id);
			return true;
		 }
	}

	/**
	 * @name setNotificacion
	 * @access public
	 * @param int
	 * @return void
	*/
	public function setNotificacion(int $type = 0, int $user_id = 0, int $obj_user = 0, int $obj_uno = 0, int $obj_dos = 0, int $obj_tres = 0){
		global $tsUser, $tsCore;
		# NO SE MOSTRARA MI PROPIA ACTIVIDAD
		if($user_id != $tsUser->uid) {
			# VERIFICA SI ESTE USUARIO ADMITE NOTIFICACIONES DEL TIPO $type
			$allow = $this->allowNotifi($type, $user_id);
			if(empty($allow)) return true;
			// VERIFICAR CUANTAS NOTIFICACIONES DEL MISMO TIPO Y EN POCO TIEMPO TENEMOS
			$tiempo = time() - 3600; //  HACE UNA HORA
			$not_data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT not_id FROM u_monitor WHERE user_id = $user_id AND obj_uno = $obj_uno AND obj_dos = $obj_dos AND not_type = $type AND not_date > $tiempo AND not_menubar > 0 ORDER BY not_id DESC LIMIT 1"));
			// Actualizamos o insertamos
			$not_db_type = (!empty($not_data['not_id']) && $type != 4) ? 'update' : 'insert';
			// COMPROBAR LIMITE DE NOTIFICACIONES
			$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT not_id FROM u_monitor WHERE user_id = $user_id ORDER BY not_id DESC"));
			$ntotal = safe_count($data);
			$delid = $data[$ntotal-1]['not_id']; // ID DE ULTIMA NOTIFICACION
			// ELIMINAR NOTIFICACIONES?
			if($ntotal > $tsCore->settings['c_max_nots']) {
				deleteID([__FILE__, __LINE__], 'u_monitor', 'not_id = ' . $delid);
			}
			// ACTUALIZAMOS / INSERTAMOS
			if($not_db_type === 'update') {
				$sql = $tsCore->getIUP([
					'obj_user' => $obj_user,
					'not_date' => time(),
					'not_total' => 'not_total + 1',
				]);
				$sql = db_exec([__FILE__, __LINE__], 'query', "UPDATE u_monitor SET $sql  WHERE not_id = {$not_data['not_id']}");
			} else {
				$sql = insertInto([__FILE__, __LINE__], 'u_monitor', [
					'user_id' => $user_id,
					'obj_user' => $obj_user,
					'obj_uno' => $obj_uno,
					'obj_dos' => $obj_dos,
					'obj_tres' => $obj_tres,
					'not_type' => $type,
					'not_date' => time()
				]);
			}
			if($sql) return true;
		}
	}
	
	/**
	 * @name setFollowNotificacion
	 * @access public
	 * @params int
	 * @return void
	 * @info Envia notificaciones a los usuarios que siguen a un post o usuario.
	*/
	public function setFollowNotificacion($notType = null, int $f_type = 0, int $user_id = 0, int $obj_uno = 0, int $obj_dos = 0, array $excluir = []){
		global $tsCore;
		# TIPO DE FOLLOW USER o POST
		if($f_type == 1) $f_id = (int)$user_id;
		elseif($f_type == 2) $f_id = (int)$obj_uno;
		# BUSCAMOS LOS Q SIGAN A ESTE POST/ USER
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT f_user FROM u_follows WHERE f_id = $f_id AND f_type = $f_type"));
		//
		foreach($data as $key => $val){
			// A CADA USUARIO LE NOTIFICAMOS SI NO ESTA EN LAS EXCLUSIONES
			if(!in_array($val['f_user'], $excluir)) {
				$this->setNotificacion($notType, $val['f_user'], $user_id, $obj_uno, $obj_dos);
			}
		}
		//
		return true;
	}

	/**
	 * @name setMuroRepost
	 * @access public
	 * @params int
	 * @return void
	 * @info NOTIFICA CUANDO ALGUIEN RESPONDE UNA PUBLICACION EN UN MURO
	*/
	public function setMuroRepost(int $pub_id = 0, int $p_user = 0, int $p_user_pub = 0){
		global $tsUser;
		//
		$data = result_array( db_exec([__FILE__, __LINE__], 'query', "SELECT c_user FROM u_muro_comentarios WHERE pub_id = $pub_id AND c_user NOT IN ({$tsUser->uid}, $p_user)"));
		// ENVIAMOS NOTIFICACION A LOS QUE HAYAN COMENTADO
		$enviados = [];
		foreach($data as $key => $val) {
			if(!in_array($val['c_user'], $enviados)){
				$this->setNotificacion(13, $val['c_user'], $tsUser->uid, $pub_id, 3);
				$enviados[] = $val['c_user'];
			}
		}
		// ENVIAMOS AL DUE�O DEL MURO
		$this->setNotificacion(13, $p_user, $tsUser->uid, $pub_id, 1);
		// ENVIAMOS AL QUE PUBLICO SI NO FUE EL DUE�O DEL MURO
		if(($p_user != $p_user_pub) && !in_array($p_user_pub, $enviados)) {
			$this->setNotificacion(13, $p_user_pub, $tsUser->uid, $pub_id, 2);    
		}
	}

	/**
	 * @name getNotificaciones
	 * @access public
	 * @param int
	 * @return array
	 * @info CREAR UN ARRAY CON LAS NOTIFICAIONES DEL USUARIO
	*/
	public function getNotificaciones(bool $unread = false){
		global $tsUser, $tsCore;
		# SI HAY MAS DE 5 NOTIS MOSTRAMOS TODAS LAS NO LEIDAS
		if($this->show_type == 1) {
			// VIEW TYPE
			$not_view = ($unread == true) ? '= 2' : '> 0';
			$not_del = ($unread == true) ? 1 : 0;
			$notun = ($this->notificaciones > 5 || $unread == true);
			$limit = $notun ? "" : "LIMIT 5";
			//
			$sql = $notun ? " AND m.not_menubar $not_view" : '';
		// SI VA AL MONITOR ENTONCES ACTUALIZAMOS PARA QUE YA NO SE VEAN EN EL MENUBAR
		} elseif($this->show_type == 2) {
			// DATOS
			$sql = "";
			$limit = "";
			//ESTAD�STICAS
			$dataDos['stats']['posts'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_user = {$tsUser->uid} && f_type = 3"));
			$dataDos['stats']['seguidores'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_id = {$tsUser->uid} && f_type = 1"));
			$dataDos['stats']['siguiendo'] = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_user = {$tsUser->uid} && f_type = 1"));
			# CARGO LOS FILTROS
			$filtros = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c_monitor FROM u_portal WHERE user_id = {$tsUser->uid} LIMIT 1"));
			//
			$filtros = explode(',', $filtros['c_monitor']);
			foreach($filtros as $key => $val) $dataDos['filtro'][$val] = true;	
		} 
		// PROCESOS
		$data = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT m.*, u.user_name AS usuario FROM u_monitor AS m LEFT JOIN u_miembros AS u ON m.obj_user = u.user_id WHERE m.user_id = {$tsUser->uid}$sql ORDER BY m.not_id DESC $limit"));
		// ACTUALIZAMOS
		 if($this->show_type == 1) db_exec([__FILE__, __LINE__], 'query', "UPDATE u_monitor SET not_menubar = $not_del WHERE user_id = {$tsUser->uid} AND not_menubar > 0");
		 else db_exec([__FILE__, __LINE__], 'query', "UPDATE u_monitor SET not_menubar = 0, not_monitor = 0 WHERE user_id = {$tsUser->uid} AND not_monitor = 0");
		// ARMAR TEXTOS Y LINKS :)
		$dataDos['data'] = $this->armNotificaciones($data);
		// TOTAL DE NOTIDICACIONES
		$dataDos['total'] = safe_count($dataDos['data']);
		//
		return $dataDos;
	}

	/**
	 * @name armarNotificacion
	 * @access private
	 * @param array, int
	 * @return array
	 * @info CREA LAS NOTIFICACIONES
	*/
	private function armNotificaciones($array){
		# ARMAMOS LAS ORACIONES
		 $this->makeMonitor();
		# PARA CADA VALOR CREAR UNA CONSULTA
		foreach($array as $key => $val){
			// CREAR CONSULTA
			$sql = $this->makeConsulta($val);
			// CONSULTAMOS
			if(is_array($sql)){
				$dato = $sql;
			} else {
				$query = db_exec([__FILE__, __LINE__], 'query', $sql);
				$dato = db_exec('fetch_assoc', $query);
			}
			if($dato !== NULL) $dato = array_merge($dato, $val);
			// SI AUN EXISTE LO QUE VAMOS A NOTIFICAR..
			if($dato) $data[] = $this->makeOracion($dato);
		}
		//
		return $data;
	}

	/**
	 * @name makeConsulta
	 * @access private
	 * @param array
	 * @return string
	 * @info RETORNA UNA CONSULTA DEPENDIENDO EL TIPO DE NOTIFICACION
	*/
	public function makeConsulta(array $data = []){
		$data['obj_uno'] = (int)$data['obj_uno'];
		$data['obj_dos'] = (int)$data['obj_dos'];
		# CON UN SWITCH ESCOGEMOS LA CONSULTA APROPIADA
		switch($data['not_type']) {
			// EN ESTOS CASOS SE NECESITA LO MISMO
			case 1: 
			case 2: 
			case 3: 
			case 5: 
			case 6:
			case 7:
			case 8:
			case 9:
				return "SELECT p.post_id, p.post_user, p.post_title, c.c_seo FROM p_posts AS p LEFT JOIN p_categorias AS c ON p.post_category = c.cid WHERE p.post_id = {$data['obj_uno']} LIMIT 1";
			break;
			// FOLLOW
			case 4:
				global $tsUser;
				// CHECAR SI YA LO SEGUIMOS
				$i_follow = $tsUser->iFollow($data['obj_user']);
				return ['follow' => $i_follow];
			break;
			// PUBLICO EN TU MURO
			case 12:
				return "SELECT p.pub_id, u.user_name FROM u_muro AS p LEFT JOIN u_miembros AS u ON p.p_user_pub = u.user_id WHERE p.pub_id = {$data['obj_uno']} LIMIT 1";
			break;
			case 13:
				global $tsUser;
				// HAY MAS DE UNA NOTIFICACION DEL MISMO TIPO
				$dato = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT p.pub_id, p.p_user, p.p_user_pub, u.user_name FROM u_muro AS p LEFT JOIN u_miembros AS u ON p.p_user = u.user_id WHERE p.pub_id = {$data['obj_uno']} LIMIT 1"));
				//
				$dato['p_user_resp'] = $data['obj_user'];
				$dato['p_user_name'] = $dato['user_name']; // // DUE�O DEL MURO
				$dato['user_name'] = $tsUser->getUserName($data['obj_user']); // QUIEN PUBLICO
				return $dato;
			break;
			case 14:
				if($data['obj_dos'] == 2)
					return "SELECT pub_id AS obj_uno, c_body FROM u_muro_comentarios WHERE cid = {$data['obj_uno']}";
				else return ['value' => 'hack'];
			break;
			case 15:
				return "SELECT medal_id, m_title, m_image FROM w_medallas WHERE medal_id = {$data['obj_uno']} LIMIT 1";
			break;
			case 16:
				return "SELECT p.post_id, p.post_title, c.c_seo, m.medal_id, m.m_title, m.m_image, a.medal_for FROM w_medallas_assign AS a LEFT JOIN p_posts AS p ON p.post_id = a.medal_for LEFT JOIN p_categorias AS c ON c.cid = p.post_category LEFT JOIN w_medallas AS m ON m.medal_id = a.medal_id WHERE m.medal_id = {$data['obj_uno']} AND p.post_id = {$data['obj_dos']} LIMIT 1";
			break;
			case 17:
				return "SELECT f.foto_id, f.f_title, f.f_user, m.medal_id, m.m_title, m.m_image, a.medal_for, u.user_id, u.user_name FROM w_medallas_assign AS a LEFT JOIN f_fotos AS f ON f.foto_id = a.medal_for LEFT JOIN u_miembros AS u ON u.user_id = f.f_user LEFT JOIN w_medallas AS m ON m.medal_id = a.medal_id WHERE m.medal_id = {$data['obj_uno']} AND f.foto_id = {$data['obj_dos']} LIMIT 1";
			break;
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 23:
			case 24:
			case 25:
			case 26:
            return 'SELECT c.c_nombre, c.c_nombre_corto, t.t_id, t.t_titulo, t.t_autor, u.user_name FROM c_temas AS t LEFT JOIN c_comunidades AS c ON c.c_id = t.t_comunidad LEFT JOIN u_miembros AS u ON user_id = t_autor WHERE t.t_id = \''.(int)$data['obj_uno'].'\' LIMIT 1';
			break;
		}
	}

	/**
	*/
	private function setReplace($data, $oracion) {
		return str_replace('_REP_', "<strong>$data</strong>", $oracion);
	}

	/**
	 * @name makeOracion
	 * @access private
	 * @param array, int, int
	 * @return array
	 * @info RETORNA LAS ORACIONES A MOSTRAR EN EL MONITOR
	*/
	private function makeOracion(array $data = []){
		global $tsCore, $tsUser;
		# LOCALES
		$site_url = $tsCore->settings['url'];
		$no_type = $data['not_type'];
		$txt_extra = ($this->show_type == 1) ? '' : ' '.$this->monitor[$no_type]['ln_text'];
		$ln_text = $this->monitor[$no_type]['ln_text'];
		$ln_text = is_array($ln_text) ? $ln_text[$data['obj_dos']-1] : $ln_text;
		//
		$oracion['unread'] = ($this->show_type == 1) ? $data['not_menubar'] : $data['not_monitor'];
		$oracion['style'] = $this->monitor[$no_type]['css'];
		$oracion['date'] = $data['not_date'];
		$oracion['user'] = $data['usuario'];
		$oracion['avatar'] = $data['obj_user'].'_120.jpg';
		$oracion['total'] = $data['not_total'];
		# CON UN SWITCH ESCOGEMOS QUE ORACION CONSTRUIR
		switch($no_type){
			case 1:
			case 3:
			case 5:
				// 
				$oracion['text'] = $this->monitor[$no_type]['text'].$txt_extra;
				if($no_type == 3) 
					$oracion['text'] = self::setReplace($data['obj_dos'], $oracion['text']);
				$oracion['link'] = $site_url.'/posts/'.$data['c_seo'].'/'.$data['post_id'].'/'.$tsCore->setSEO($data['post_title']).'.html';
				$oracion['ltext'] = ($this->show_type == 1) ? $ln_text : $data['post_title'];
				$oracion['ltit'] = ($this->show_type == 1) ? $data['post_title'] : '';
			break;
			// FOLLOW
			case 4:
				$oracion['text'] = $this->monitor[$no_type]['text'];
				if($data['follow'] != true && $this->show_type == 2) {
					$oracion['link'] = '#" onclick="notifica.follow(\'user\', '.$data['obj_user'].', notifica.userInMonitorHandle, this)';
					$oracion['ltext'] = $this->monitor[$no_type]['ln_text'];    
				}
			break;
			// PUEDEN SER MAS DE UNO
			case 2:
			case 6:
			case 7:
			case 8:
			case 9:
				// CUANTOS
				$no_total = $data['not_total'];
				// MAS DE UNA ACCION
				if($no_total > 1) {
					  $text = $this->monitor[$no_type]['text'][1].$txt_extra;
					  $oracion['text'] = self::setReplace($no_total, $text);
				} else $oracion['text'] = $this->monitor[$no_type]['text'][0].$txt_extra;
				// �ES MI POST?
				if($data['post_user'] == $tsUser->uid) {
					$oracion['text'] = str_replace('te recomienda un', 'ha recomendado tu', $oracion['text']);
			 	}
				// ID COMMENT
				if($no_type == 8 || $no_type == 9){
					$id_comment = '#comentario-'.$data['obj_dos'];
					// EXTRAS
					if($no_type == 8) {
						$voto_type = ($data['obj_tres'] == 0) ? 'negativo' : 'positivo';
						$oracion['text'] = self::setReplace($voto_type, $oracion['text']);
						$oracion['style'] = 'voto_'.$voto_type;
					}
				}
				$oracion['link'] = $site_url.'/posts/'.$data['c_seo'].'/'.$data['post_id'].'/'.$tsCore->setSEO($data['post_title']).'.html'.$id_comment;
				$oracion['ltext'] = ($this->show_type == 1) ? $ln_text : $data['post_title'];
				$oracion['ltit'] = ($this->show_type == 1) ? $data['post_title'] : '';
			break;
			// PUBLICACION EN MURO
			case 12:
				$oracion['text'] = $this->monitor[$no_type]['text'].$txt_extra;
				$oracion['link'] = $site_url.'/perfil/'.$tsUser->nick.'/'.$data['obj_uno'];
				$oracion['ltext'] = ($this->show_type == 1) ? $ln_text : $tsUser->nick;
				$oracion['ltit'] = ($this->show_type == 1) ? $tsUser->nick : '';
			break;
			case 13:
				$de = ($tsUser->uid == $data['p_user']) ? ' tu' : ($data['p_user'] == $data['p_user_resp'] ? ' su' : ' la publicaci&oacute;n de');
				// CUANTOS
				$no_total = $data['not_total'];
				if($no_total > 1) {
					$text = $this->monitor[$no_type]['text'][1].$de.$txt_extra;
					$oracion['text'] = self::setReplace($no_total, $text);
				} else $oracion['text'] = $this->monitor[$no_type]['text'][0].$de.$txt_extra;
				//
				$oracion['link'] = $site_url.'/perfil/'.$data['p_user_name'].'/'.$data['pub_id'];
				$oracion['ltext'] = ($this->show_type == 1) ? $ln_text : $tsUser->nick;
				$oracion['ltit'] = ($this->show_type == 1) ? $tsUser->nick : '';
			break;
			case 14:
				// CUANTOS
				$no_total = $data['not_total'];
				// MAS DE UNA ACCION
				if($no_total > 1) {
					$text = $this->monitor[$no_type]['text'][1].' '.$ln_text;
					$oracion['text'] = self::setReplace($no_total, $text);
				} else $oracion['text'] = $this->monitor[$no_type]['text'][0];
				//
				$oracion['text'] = ($this->show_type == 1) ? $oracion['text'] : $oracion['text'].' '.$ln_text;
				$oracion['link'] = $site_url.'/perfil/'.$tsUser->nick.'/'.$data['obj_uno'];
				$oracion['ltext'] = ($this->show_type == 1) ? $ln_text : substr($data['c_body'],0,20).'...';
				$oracion['ltit'] = ($this->show_type == 1) ? substr($data['c_body'],0,20).'...' : '';
			break;
			case 15:
				$oracion['text'] = "Recibiste una nueva <span title=\"{$data['m_title']}\"><strong>medalla</strong> <img src=\"$site_url/public/images/icons/med/{$data['m_image']}_16.png\"/></span>";
			break;
			case 16:
				$post_title = $tsCore->setSEO($data['post_title']);
				$oracion['text'] = "Tu <a href=\"$site_url/posts/{$data['c_seo']}/{$data['post_id']}/{$post_title}.html\" title=\"$post_title\"><strong>post</strong></a> tiene una nueva <span title=\"{$data['m_title']}\"><strong>medalla</strong> <img src=\"$site_url/public/images/icons/med/{$data['m_image']}_16.png\"/></span>";
			break;
			case 17:
				$f_title = $tsCore->setSEO($data['f_title']);
				$oracion['text'] = "Tu <a href=\"$site_url/fotos/{$data['user_name']}/{$data['foto_id']}/$f_title.html\" title=\"$f_title\"><strong>foto</strong></a> tiene una nueva <span title=\"{$data['m_title']}\"><strong>medalla</strong> <img src=\"$site_url/public/images/icons/med/{$data['m_image']}_16.png\"/></span>";
			break;
			case 18:
			case 19:
			case 20:
			case 21:
			case 22:
			case 23:
			case 24:
			case 25:
			case 26:
				// CUANTOS
            $no_total = $data['not_total'];
				$id_comment = '';
				if($no_type == 20) $extra = $this->monitor[$no_type]['extra'];
            // MAS DE UNA ACCION
            if($no_total > 1) {
               $text = $this->monitor[$no_type]['text'][1].$txt_extra;
               $oracion['text'] = str_replace('_REP_', "<b>{$no_total}</b>", $text);
            } else $oracion['text'] = ($this->show_type == 1 && $no_type == 20) ? $this->monitor[$no_type]['text'][0].$txt_extra : $this->monitor[$no_type]['text'][0].$txt_extra.$extra;
            // ID COMMENT
            if($no_type == 21 || $no_type == 25){
               $id_comment = '#coment_id_'.$data['obj_tres'];
            }
				if($no_type == 24 || $no_type == 25){
					$voto_type = ($data['obj_dos'] == 0) ? 'negativo' : 'positivo';
					$oracion['text'] = str_replace('_REP_', '<b>'.$voto_type.'</b>', $oracion['text']);
					$oracion['style'] = 'voto_'.$voto_type;
				}
            //
            $oracion['link'] = $site_url.'/comunidades/'.$data['c_nombre_corto'].'/'.$data['t_id'].'/'.$tsCore->setSEO($data['t_titulo']).'.html'.$id_comment;
            $oracion['ltext'] = ($this->show_type == 1) ? $ln_text : $data['t_titulo'];
            $oracion['ltit'] = ($this->show_type == 1) ? $data['t_titulo'] : '';
         break;
		}
		# RETORNAMOS
		return $oracion;
	}

	/**
	 * @name setFollow
	 * @access public
	 * @param none
	 * @return string
	 * @info MANEJA EL SEGUIR USUARIO/POST
	*/
	public function setFollow(){
		global $tsUser, $tsCore, $tsActividad;
		// VARS
		$notType = 4; // NOTIFICACION
		$fw = $this->getFollowVars();
		foreach($fw as $k => $value) $fw[$k] = is_numeric($value) ? (int)$value : $value;
		// ANTI FLOOD
		$flood = $tsCore->antiFlood(false, 'follow');
		if(strlen($flood) > 1) {
			$flood = str_replace('0: ','',$flood);
			return "1-{$fw['obj']}-0-$flood";
		}
		// YA EXISTE?
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_user = {$tsUser->uid} AND f_id = {$fw['obj']} AND f_type = {$fw['type']} LIMIT 1"));
		// SEGUIR
		if(empty($data['follow_id'])){
			if($tsUser->uid == $fw['obj'] && $fw['type'] == 1) return "1-{$fw['obj']}-0-No puedes seguirte a ti mismo.";
			if(insertInto([__FILE__, __LINE__], 'u_follows', ['user' => $tsUser->uid, 'id' => $fw['obj'], 'type' => $fw['type'], 'date' => time()], 'f_')){
				// MONITOR?
				if($fw['notUser'] > 0) 
					$this->setNotificacion($notType, $fw['notUser'], $tsUser->uid);
				// CUANTOS?
				$total = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT COUNT(follow_id) AS total FROM u_follows WHERE f_id = {$fw['obj']} AND f_type = {$fw['type']}"));
				// ACTIVIDAD
				$ac_type = ($fw['type'] == 1) ? 8 : 7;
				$tsActividad->setActividad($ac_type, $fw['obj']);
				// RESPUESTA
				return "0-{$fw['obj']}-{$total['total']}";
			} else return "1-{$fw['obj']}-0-No se pudo completar la acci&oacute;n.";
		} else return "2-{$fw['obj']}-0";
	}

	/**
	 * @name setUnFollow
	 * @access public
	 * @param none
	 * @return string
	 * @info MANEJA EL DEJAR DE SEGUIR UN USUARIO/POST
	*/
	public function setUnFollow(){
		global $tsUser, $tsCore;
		// VARS
		$notType = 4; // NOTIFICACION
		$fw = $this->getFollowVars();
		foreach($fw as $k => $value) $fw[$k] = is_numeric($value) ? (int)$value : $value;
		// ANTI FLOOD
		$flood = $tsCore->antiFlood(false, 'follow');
		if(strlen($flood) > 1) {
			$flood = str_replace('0: ','',$flood);
			return '1-'.$fw['obj'].'-0-'.$flood;
		}
		// DEJAR DE SEGUIR
		if(deleteID([__FILE__, __LINE__], 'u_follows', "f_user = {$tsUser->uid} AND f_id = {$fw['obj']} AND f_type = {$fw['type']}")) {
			// CUANTOS?
			$total= db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_id = {$fw['obj']} AND f_type = {$fw['type']}"));
			// RESPUESTA
			return '0-'.$fw['obj'].'-'.$total;
		} else return '1-'.$fw['obj'].'-0-No se pudo completar la acci&oacute;n.';
	}

	/**
	 * @name getFollowVars
	 * @access private
	 * @param none
	 * @return array
	 * @info GENERA Y CREA UN ARRAY CON LA INFORMACION QUE RECIBE POR AJAX
	*/
	public function getFollowVars(){
		global $tsCore;
		//
		$return['sType'] = (int)$_POST['type'];
		$return['obj'] = (int)$_POST['obj'];
		// TIPO EN NUMERO
		switch($return['sType']){
			case 'user': 
				$return['type'] = 1; 
				$return['notUser'] = $return['obj'];
			break;
			case 'post': 
				$return['type'] = 2; 
				$return['notUser'] = 0;
			break;
		}
		//
		return $return;
	}

	/**
	 * @name getFollows
	 * @access public
	 * @param int
	 * @return array
	 * @info CARGA EN UN ARRAY LA INFORMACION DE LOS "FOLLOWs" DE UN USUARIO
	*/
	public function getFollows(int $type = 0, int $user_id = 0){
		global $tsCore, $tsUser;
		// VARS
		$user_id = empty($user_id) ? $tsUser->uid : $user_id;
		//
		switch($type){
			case 'seguidores':
				$query = "SELECT u.user_id, u.user_name, p.user_pais, p.p_mensaje, f.follow_id FROM u_miembros AS u LEFT JOIN u_perfil AS p ON u.user_id = p.user_id LEFT JOIN u_follows AS f ON p.user_id = f.f_user WHERE f.f_id = $user_id. AND f.f_type = 1 ORDER BY f.f_date DESC";				
			break;
			case 'siguiendo':
				$query = "SELECT u.user_id, u.user_name, p.user_pais, p.p_mensaje, f.follow_id FROM u_miembros AS u LEFT JOIN u_perfil AS p ON u.user_id = p.user_id LEFT JOIN u_follows AS f ON p.user_id = f.f_id WHERE f.f_user = $user_id AND f.f_type = 1 ORDER BY f.f_date DESC";
			break;
			case 'posts':
				$query = "SELECT f.f_id, p.post_user, p.post_title, u.user_name, c.c_seo, c.c_nombre, c.c_img FROM u_follows AS f LEFT JOIN p_posts AS p ON f.f_id = p.post_id LEFT JOIN u_miembros AS u ON u.user_id = p.post_user LEFT JOIN p_categorias AS c ON c.cid = p.post_category WHERE f.f_user = $user_id AND f.f_type = 2 ORDER BY f.f_date DESC";
			break;
		}
		// Evitamos la repeci�n de $total, $page, $data['pages'] y $data['data']
		$total = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', $query));
		$pages = $tsCore->getPagination($total, 12);
		$data['pages'] = $pages;
		$data['data'] = result_array(db_exec([__FILE__, __LINE__], 'query', "$query LIMIT {$pages['limit']}"));
		//
		if($type === 'seguidores') {
			foreach($data['data'] as $key => $val) {
				$siguiendo = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_user = $user_id AND f_id = {$val['user_id']} AND f_type = 1"));
				$val['follow'] = (!empty($siguiendo['follow_id'])) ? 1 : 0;
				$data['data'][] = $val;
			}
		}
		//
		return $data;
	}

	/**
	 * @name setSpam
	 * @access public
	 * @param none
	 * @return string
	 * @info ESTA FUNCION ES PARA REALIZAR RECOMENDACIONES
	*/
	public function setSpam(){
		global $tsCore, $tsUser, $tsActividad;
		//
		$postid = (int)$_POST['postid'];
		// TIENE SEGUIDORES?
		$seguidores = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_id = {$tsUser->uid} AND f_type = 1 LIMIT 1"));
		// YA LO HA RECOMENDADO?
		$recomendado = db_exec('num_rows', db_exec([__FILE__, __LINE__], 'query', "SELECT follow_id FROM u_follows WHERE f_id = $postid AND f_user = {$tsUser->uid} AND f_type = 3 LIMIT 1"));
		  
		if($seguidores < 1) return '0-Debes tener al menos un seguidor';
		if($recomendado > 0) return '0-No puedes recomendar el mismo post m&aacute;s de una vez.'; 
		//
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT post_user FROM p_posts WHERE post_id = $postid LIMIT 1"));
		//
		if($tsUser->uid != $data['post_user']) {
			// GUARDAMOS EN FOLLOWS PUES ES LA RECOMENDACION PARA SU SEGUIDORES! xD
			insertInto([__FILE__, __LINE__], 'u_follows', ['id' => $postid, 'user' => $tsUser->uid, 'type' => 3, 'date' => time()], 'f_');
			// NOTIFICAR
			if($this->setFollowNotificacion(6, 1, $tsUser->uid, $postid)) {
				$tsActividad->setActividad(4, $postid);
				return '1-La recomendaci&oacute;n fue enviada.';
			}
		} else return '0-No puedes recomendar tus posts.';
	}

	/**
	 * @name setFiltro
	 * @access public
	 * @param none
	 * @return bool
	 * @info GUARDA LOS FILTROS DE LA ACTIVIDAD
	*/
	public function setFiltro() {
		global $tsUser;
		//
		foreach ($_POST['fid'] as $key => $value) $fid[] = 'f'.$value;
		$filtros = join(',', $fid);
		# GUARDAR
		db_exec([__FILE__, __LINE__], 'query', "UPDATE u_portal SET c_monitor = '$filtros' WHERE user_id = {$tsUser->uid}");
		return true;
	}

	/**
	 * @name allowNotifi
	 * @access private
	 * @param int
	 * @return bool
	 * @info REVISA EN LA CONFIGURACION SI DESEA RESIBIR LA NOTIFICACION
	 */
	private function allowNotifi(int $type = 0, int $user_id = 0){
		# CONSULTAMOS
		$filtros = explode(',',db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT c_monitor FROM u_portal WHERE user_id = {$user_id} LIMIT 1"))['c_monitor']);
		# VERIFICAMOS
		return in_array("f$type", $filtros);
	}
}
<?php 

require realpath('../../') . DIRECTORY_SEPARATOR . "header.php";

class Callback extends tsCore {

	/**
	 * @access public
	 * Puede ser github, discord, google, etc.
	*/
	public $social = '';
	
	public function __construct() {}

	/**
	 * @name getEndPoint
	 * @access public
	 * @param string
	 * @return string
	 * Manejo de OAuth | Token
	*/
	public function getEndPoint(string $type = '') {
		$url = [
			'github' => [
				'token' => "https://github.com/login/oauth/access_token",
				'user' => "https://api.github.com/user"
			],
			'discord' => [
				'token' => "https://discord.com/api/oauth2/token",
				'user' => "https://discord.com/api/v10/users/@me"
			]
		];
		return $url[$this->social][$type];
	}

	/**
	 * @name buildQuery
	 * @access public
	 * @return string
	 * Control sobre parámetros en cURL
	*/
	public function buildQuery() {
		$prefix = ($this->social === 'github') ? 'gh' : $this->social;
		$param = [
		 	'client_id' => parent::getSettings()[$prefix . '_client_id'],
		 	'client_secret' => parent::getSettings()[$prefix . '_client_secret'],
		 	'grant_type' => 'authorization_code',
		 	'code' => $_GET['code'],
		 	'redirect_uri' => parent::getSettings()['url'] . '/' . $this->social . '.php'
		];
		if($this->social === 'github') {
			unset($param['grant_type']);
		}
		return http_build_query($param);
	}

	public function httpHeader() {
		if($this->social === 'github') {
			$header = ['Accept: application/json'];
		} elseif($this->social === 'discord') {
			$header = ['Content-Type: application/x-www-form-urlencoded'];
		}
		return $header;
	}

	public function cURLToken() {
		$ch = curl_init(self::getEndPoint('token'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, self::buildQuery());
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::httpHeader());
		$response = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($response);
		return $data;
	}

	private function httpHeaderUser($data) {
		if($this->social === 'github') {
			$array = [
				"Authorization: token " . $data, 
				"User-Agent: ". parent::getSettings()['titulo']
			];
		} elseif($this->social === 'discord') {
			$array = ["Authorization: Bearer " . $data];
		}
		return $array;
	}

	public function cURLUser($data) {
		$ch = curl_init(self::getEndPoint('user'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, self::httpHeaderUser($data->access_token));
		$response = curl_exec($ch);
		curl_close($ch);
		$userData = json_decode($response);
		return $userData;
	}

	public function OAuthComplete(array $UserData = []) {
		global $tsUser;
		# Verificamos que sea un correo
		if(filter_var($UserData['email'], FILTER_VALIDATE_EMAIL)) {
			# Lo almacenamos en una variable
			$email = parent::setSecure($UserData['email']);
			# Generamos la consulta 
			$usuario = $tsUser->sameQuery("LOWER(user_email) = '$email'", $this->social);
			# Comprobamos que exista el usuarios
			if(empty($usuario)) {
				self::createNewAccount($UserData);
			} else if((int)$usuario['user_'.$this->social] === 1 AND !$tsUser->is_member) {
				self::accessAccount($usuario);
			} else if((int)$usuario['user_'.$this->social] === 0 AND $tsUser->is_member) {
				self::updateAccount($usuario['user_id']);
			}
		} else die('Lo lamento, este '.$UserData['email'].' no es un correo v&aacute;lido.');
	}

	private function createNewAccount(array $UserData = []) {
		$rango = empty(parent::getSettings()['c_reg_rango']) ? 3 : (int)parent::getSettings()['c_reg_rango'];
		$active = (int)parent::getSettings()['c_reg_active'];
		$info = [
			'user_name' => $UserData['nick'], 
			'user_password' => '', 
			'user_email' => $UserData['email'], 
			'user_rango' => $rango, 
			'user_registro' => time(), 
			'user_activo' => $active, 
			'user_' . $this->social => 1
		];
		if(insertInto([__FILE__, __LINE__], 'u_miembros', $info)) {
			$id = db_exec('insert_id');
	     	foreach ([50, 120] as $k => $size) copy($UserData['avatar'], TS_AVATAR . "{$id}_$size.jpg");
	     	// INSERTAMOS EL PERFIL
			db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_perfil` (`user_id`, `p_avatar`) VALUES ($id, 1)");
	      db_exec([__FILE__, __LINE__], 'query', "INSERT INTO `u_portal` (`user_id`) VALUES ($id)");
	      $data = [
	      	'user_id' => $id, 
	      	'user_baneado' => 0, 
	      	...$info
	      ];
	      self::accessAccount($data);
		}
	}
	private function accessAccount(array $user = []) {
		global $tsUser;
		# Usuario activo?
      if((int)$user['user_activo'] === 0) die('Tienes que activar tu cuenta.');
		# Usuario baneado?
      if((int)$user['user_baneado'] === 1) die('Tu has sido baneado.');
		# Esta vinculado?
		if((int)$user['user_' . $this->social] === 1) {
			// Actualizamos la session
         $tsUser->sessionUpdate((int)$user['user_id']);           
			/* REDERIGIR */
			parent::redirectTo('./');
		} else die('Tu cuenta no esta vinculada a ' . $this->social);
	}

	private function updateAccount(int $id = 0) {
		db_exec([__FILE__, __LINE__], 'query', "UPDATE u_miembros SET user_{$this->social} = 1 WHERE user_id = $id");
		/* REDERIGIR */
		parent::redirectTo('./');
	}

}

$callback = new Callback;
<?php 

class Installer {

	private $ssl = 'http';

	private $keygen = 'UmlzdXMyMw==';

	public $required = [
		TS_CONFIG, TS_CACHE, TS_AVATAR, TS_UPLOADS
	];

	/**
	 * Obtenemos la url
	*/
	public function getSSLProtocol() {
		$ssl = 'http';
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') $ssl .= 's';
		return $ssl;
	}

	public function getURL(string $DIR_PAHT = '', string $clear = '') {
		// Creando las url base e install
		$url = (empty($clear) ? $this->getSSLProtocol() . "://" : '');
		$url .= ($_SERVER['HTTP_HOST'] ?? 'localhost') . $_SERVER['REDIRECT_URL'];
		if($DIR_PAHT === './') {
			return dirname($url);
		} else {
			return empty($DIR_PAHT) ? $url : dirname($url) . "/$DIR_PAHT";
		}
	}

	public function getStyleInstall() {
		return dirname($this->getURL()) . '/assets/css/install.css' . uniqid('?' . time());
	}

	public function folderFilePerms() {
		foreach($this->required as $key => $rqd) {
			$type_perms = is_dir($rqd) ? 0777 : 0666;
			if($key === 0) {
				if(!file_exists($this->required[0])) {
					copy(TS_EXAMPLE, $this->required[0]);
				}
			} else if(!is_dir($rqd)) {
				mkdir($rqd, $type_perms, true);
			}
			chmod($rqd, $type_perms);
		}
	}

	public function save(array $replace = []) {
		$search = ['dbhost', 'dbuser', 'dbpass', 'dbname', 'dbverify'];
		$search_replace = str_replace($search, $replace, file_get_contents(TS_CONFIG));
		file_put_contents(TS_CONFIG, $search_replace);
	}

	public function conn(array $db = [], ?string &$tsMessage = null, bool &$next = false) {
		try {
			$mysqli = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
			if ($mysqli->connect_errno) {
			   $tsMessage = "Falló la conexión con MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
			}
			if (!$mysqli->set_charset("utf8mb4")) {
			  	$tsMessage = "Error cargando el conjunto de caracteres utf8mb4: " . $mysqli->error;
			}
			$next = true;
		} catch (Exception $e) {
			switch ($e->getCode()) {
				case 1045:
					$tsMessage = "Acceso denegado para <strong>{$db['username']}@{$db['hostname']}</strong>.";
				break;
				case 1049:
					$tsMessage = "La base de datos <strong>{$db['database']}</strong> desconocido o no existe.";
				break;
				case 2002:
					$tsMessage = "Host <strong>{$db['database']}</strong> desconocido.";
				break;
			}
		}
		return $mysqli;
	}

	public function createPassword(string $username = '', string $password = '', string $verify = '') {
		$passmd5 = md5($username.$password);
		if(!empty($verify)) $passmd5 = ($passmd5 === $verify);
		return $passmd5;
	}
}
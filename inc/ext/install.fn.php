<?php 

class Installer {

	private $ssl = 'http';

	private $keygen = 'UmlzdXMyMw==';

	public $required = [
		TS_CONFIG, TS_CACHE, TS_AVATAR, TS_UPLOADS
	];

	public function getURL(string $dir = '') {
		// Intento de sistema de dirección automática
		if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
		   $this->ssl = 'https';
		}
		// Creando las url base e install
		$url = "$this->ssl://" . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost') . $_SERVER['REDIRECT_URL'];
		if($dir === './') {
			return dirname($url);
		} else {
			return empty($dir) ? $url : dirname($url) . "/$dir";
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
		$search = ['dbhost', 'dbuser', 'dbpass', 'dbname'];
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
			$tsMessage = $e->getMessage() . ' - code #' . $e->getCode();
		}
		return $mysqli;
	}

	public function createPassword(string $username = '', string $password = '', string $verify = '') {
		$username = $this->keygen . md5($username);
		$password = $this->keygen . md5($password);
		$md5 = md5($password . $username);
		if(!empty($verify)) $md5 = ($md5 === $verify);
		return $md5;
	}
}
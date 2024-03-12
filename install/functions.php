<?php

require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'inc' . DIRECTORY_SEPARATOR . 'definitions.php';

define('INSTALL_ROOT', TS_ROOT . 'install' . TS_PATH);

define('CONFIGINC', TS_ROOT . 'config.inc.php');
define('CONFIGINC2', INSTALL_ROOT . 'config.copy.php');

define('LICENSE', TS_ROOT . 'LICENSE');
define('FILE_BLOCKED_INSTALL', TS_ROOT . '.lock');

/**
 * Realizamos verificación de config.inc.php
*/
if(!file_exists(CONFIGINC)) {
   copy(CONFIGINC2, CONFIGINC);
   chmod(CONFIGINC, 0666);
}

$version_id = "1.3.580";
$version_title = "Risus $version_id";
$wversion_code = str_replace([' ', '.'], '_', strtolower($version_title));

$step = empty($_GET['step']) ? 0 : $_GET['step'];
$step = htmlspecialchars(intval($step));
$next = true; // CONTINUAR

$tema_a_usar = 'beatrix';
$tema_default = 'default';
$themes = [
	[
		'tid' => 1, 
		't_name' => ucfirst($tema_default), 
		't_url' => '/themes/' . $tema_default, 
		't_path' => $tema_default, 
		't_copy' => 'Miguel92'
	],	[
		'tid' => 2, 
		't_name' => ucfirst($tema_a_usar), 
		't_url' => '/themes/' . $tema_a_usar, 
		't_path' => $tema_a_usar, 
		't_copy' => 'Miguel92'
	]
];

// Intento de sistema de dirección automática
$ssl = 'http';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
   $ssl = 'https';
}
$local = dirname(dirname($_SERVER["REQUEST_URI"]));
// Creando las url base e install
$url = "$ssl://" . ($_SERVER['HTTP_HOST'] === 'localhost' ? "localhost$local" : $_SERVER['HTTP_HOST']);
$base_install = "$url/install";
/** 
 * Fue creado para simplificar algunas cosas
*/

class Install {

	public $carpeta = 0777;

	public $archivo = 0666;

	private $keygen = 'UmlzdXMyMw==';

	private $status_extension;

	private $info_extension;

	private $version_extension;

	private $notes = [
		'gd' => 'Biblioteca GD necesaria para recortar y crear imágenes.',
		'curl' => 'Se utiliza para realizar solicitudes HTTP.',
		'zip' => 'La creación y manipulación de archivos ZIP en PHP.',
		'openssl' => 'Permite a PHP establecer conexiones seguras mediante el protocolo HTTPS.'
	];

	public function __construct() {
		$this->status_extension['gd'] = (extension_loaded('gd'));
		$this->status_extension['curl'] = (extension_loaded('curl'));
		$this->status_extension['zip'] = (extension_loaded('zip'));
		$this->status_extension['openssl'] = (extension_loaded('openssl'));

		$this->info_extension['gd'] = $this->status_extension['gd'] ? gd_info() : '';
		$this->info_extension['curl'] = $this->status_extension['curl'] ? curl_version() : '';
		$this->info_extension['zip'] = $this->status_extension['zip'] ? 'InfoModules' : '';
		$this->info_extension['openssl'] = $this->status_extension['openssl'] ? 'InfoModules' : '';

		$this->version_extension['gd'] = 'GD Version';
		$this->version_extension['curl'] = 'version';
		$this->version_extension['zip'] = "/zip version\s+(.*)/";
		$this->version_extension['openssl'] = "/OpenSSL Library Version\s+(.*)/";
	}

	private function forzarPermisos(string $carpeta_archivo = '') {
		$tipo = is_dir($carpeta_archivo) ? $this->carpeta : $this->archivo;
		chmod($carpeta_archivo, $tipo);
	}

	private function existeCarpeta(string $carpeta) {
		if( !is_dir( $carpeta ) AND !is_file( $carpeta )):
			# Creamos la carpeta
			mkdir($carpeta, $this->carpeta, true);
			# Forzamos los permisos
			self::forzarPermisos($carpeta);
		endif;
	}

	public function chequearPermisos(array $chequear = []) {
		foreach ($chequear as $key => $carpeta_archivo) {
			if( is_dir($carpeta_archivo) ):
				self::forzarPermisos($carpeta_archivo);
			else:
				self::existeCarpeta($carpeta_archivo);
			endif;
		}
	}

	private function InfoModules() {
		ob_start();
		phpinfo(INFO_MODULES);
		$phpinfo = ob_get_clean();
		return $phpinfo;
	}

	public function checkVersionPHP() {
		$phpversion = phpversion();
		$version_php = (version_compare($phpversion, '7.4', '<')) ? false : true;
		return [
			'icono' => $version_php ? 'solar:check-read-line-duotone' : 'solar:close-circle-line-duotone',
			'clase' => $version_php ? 'success' : 'danger',
			'mensaje' => $version_php ? "Tu versión es superior o igual a PHP 7+" : "Se requiere 7+ o superior",
			'version' => $phpversion
		];
	}

	public function checkExtension() {
		foreach($this->status_extension as $lib => $extension) {
			$data = $this->info_extension[$lib];
			if($lib == 'gd' or $lib == 'curl') {
				$infodata = $extension ? $data[$this->version_extension[$lib]] : '';
			} else {
				$phpinfo = self::InfoModules();
				if (preg_match($this->version_extension[$lib], $phpinfo, $matches)) $infodata = $matches[1];
			}
			$info[$lib] = [
				'icono' => $extension ? 'solar:check-read-line-duotone' : 'solar:close-circle-line-duotone',
				'clase' => $extension ? 'success' : 'danger',
				'mensaje' => $extension ? "Extensión habilitada" : "La extensión $lib no está habilitada!",
				'version' => $infodata,
				'nota' => $this->notes[$lib]
			];
		}
		return $info;
	}

	public function createPassword(string $username = '', string $password = '', string $verify = ''):mixed {
		$username = $this->keygen . md5($username);
		$password = $this->keygen . md5($password);
		$md5 = md5($password . $username);
		if(!empty($verify)) $md5 = ($md5 === $verify);
		return $md5;
	}

	# Seguridad
	public function modoSeguro($string, $xss = false) {
		global $db, $database;
      // CONECTAMOS
      $database->db = $db;
      $database->db_link = $database->conn();
      $database->setNames();
    	// Verificar si magic_quotes_gpc está activado
    	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) $string = stripslashes($string);
    	// Escapar el valor
    	$string = $database->escape($string);
    	// Aplicar filtrado XSS si es necesario
    	if ($xss) $string = htmlspecialchars($string, ENT_COMPAT | ENT_QUOTES, 'UTF-8');
    	// Retornamos la información
    	unset($database);
    	unset($db);
    	return $string;
	}
	
	public function is_localhost() {
	   return $_SERVER['REMOTE_ADDR'] === '127.0.0.0' || $_SERVER['REMOTE_ADDR'] === '::1';
	}

	public function getIUP(array $array = [], string $prefix = ''): string {
	   $sets = [];
	   foreach ($array as $field => $value) $sets[] = "$prefix$field = " . (is_numeric($value) ? (int)$value : "'{$this->modoSeguro($value)}'");
	   return implode(', ', $sets);
	}

}

class DataBase {

	public $db = [];
	public $db_link;

   public function __construct() { 
   }

   public function conn() {
   	try {
   		return new mysqli($this->db['hostname'], $this->db['username'], $this->db['password'], $this->db['database']);
   	} catch (Exception $e) {
			switch ($e->getCode()) {
				case 1045:
					$_SERVER['message'] = "Acceso denegado para el usuario <strong>'{$this->db['username']}'</strong>@'{$this->db['hostname']}'";
				break;
				case 1049:
					$_SERVER['message'] = "La base de datos <strong>{$this->db['database']}</strong> es desconocida o no existe.";
				break;
				case 2002:
					$_SERVER['message'] = "HOST: <strong>{$this->db['hostname']}</strong> desconocido.";
				break;
			}
   	}
   }


	public function error() { 
		return mysqli_error($this->db_link); 
	}

	public function escape($string) { 
		return mysqli_real_escape_string($this->db_link, $string); 
	}

	public function fetch_assoc($query) { 
		return mysqli_fetch_assoc($this->query($query)); 
	}

	public function fetch_row($query) { 
		return mysqli_fetch_row($this->query($query)); 
	}

	public function num_rows($query) { 
		return mysqli_num_rows($this->query($query));
	}

	public function insert_id() {
		return mysqli_insert_id($this->db_link);
	}

   public function query($sql) {
   	return mysqli_query($this->db_link, $sql);
   }

   public function setNames() {
   	return $this->query("SET NAMES 'UTF8'");
   }

}


$database = new DataBase();
$Install = new Install();

$checkPerms = [CONFIGINC, TS_CACHE, TS_FILES, TS_AVATAR, TS_UPLOADS, TS_PORTADAS];
var_dump($checkPerms);
//$Install->chequearPermisos($checkPerms);
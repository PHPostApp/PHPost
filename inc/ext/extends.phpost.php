<?php 

class tsExtends {

	// No quitar, ni reemplazar
	private $keygen = 'UmlzdXMyMw==';

	private $verification;
	
	public function __construct() {
		# code...
	}
	
	public function getEndPoints(string $social = '', string $type = '') {
		$getEndPoints = [
			'github' => [
				'authorize_url' => 'https://github.com/login/oauth/authorize',
				'token' => "https://github.com/login/oauth/access_token",
				'user' => "https://api.github.com/user",
				'revoke' => "",
				'scope' => "user"
			],
			'discord' => [
				'authorize_url' => 'https://discord.com/oauth2/authorize',
				'token' => "https://discord.com/api/oauth2/token",
				'user' => "https://discord.com/api/v10/users/@me",
				'revoke' => "https://discord.com/api/oauth2/token/revoke",
				'scope' => "email identify"
			]
		];
		return $getEndPoints[$social][$type];
	}

	public function OAuth() {
		$OAuths = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT social_id, social_name, social_client_id, social_client_secret, social_redirect_uri FROM w_social'));
		foreach($OAuths as $k => $auth) {
			$parametros = [
				'client_id' => $auth['social_client_id'],
				'scope' => $this->getEndPoints($auth['social_name'], 'scope'),
				//'state' => strtolower($this->settings['titulo']).date('y'),
				'response_type' => 'code',
				'redirect_uri' => $auth['social_redirect_uri']
			];
			if($auth['social_name'] === 'github') unset($parametros['response_type']);
			$parametros = http_build_query($parametros);
			$authorize = $this->getEndPoints($auth['social_name'], 'authorize_url');
			$ruta[$auth['social_name']] = "$authorize?$parametros";
		}
		return $ruta;
	}
	/**
	 * @access public
	 * @description Es solo para comprobar que fue instalado
	*/
	public function verification() {
		$encode = base64_encode("{$this->settings['url']} - {$this->settings['version']}");
		return $encode;
	}

	public function https_on() {
	   if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') $isSecure = false;
	   elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $isSecure = true;
	   elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	      $isSecure = true;
	   }
	   $isSecure = 'http' . ($isSecure ? 's' : '') . '://';
	   return $isSecure;
	}

	/**
	 * Función para generar la contraseña
	 * y/o verificar la contraseña del usuario
	 * @param string 
	 * @param string 
	 * @return string
	*/
	public function createPassword(string $username = '', string $password = '', string $verify = '') {
		if((int)$this->settings['c_upperkey'] === 0) $username = strtolower($username);
		$username = $this->keygen . md5($username);
		$password = $this->keygen . md5($password);
		$md5 = md5($password . $username);
		if(!empty($verify)) $md5 = ($md5 === $verify);
		return $md5;
	}

	/*
     * Sacar imagen del post
     * si hay mas de una imagen, tomamos la 2 (casi siempre la 1 es de "bienvenido")
   */
	public function extraer_img($texto) {
	   // del tipo [img=imagen] o [img=imagen]
	   preg_match_all('/(\[img(\=|\]))((http|https)?(\:\/\/)?([^\<\>[:space:]]+)\.(jpg|jpeg|png|gif|webp))(\]|\[	\/img\])/i', $texto, $imgs);
	   // Si no se encontraron imágenes, devolver la imagen por defecto
	   if(empty($imgs[3])) {
	      return $this->settings['images'] . '/imagen_no_disponible.webp';
	   }
	   // Devolver la primera imagen encontrada
	   return $imgs[3][0];
	}


   public function nobbcode($nobbcode = '') {
    	// Elimina los códigos BBcodes
    	$nobbcode = preg_replace('/\[([^\]]*)\]/', '', $nobbcode); 
    	// Elimina las URLs
    	$nobbcode = preg_replace('@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', ' ', $nobbcode);
    	return $nobbcode;
	}


	public function truncate($string = '', $can = NULL){
		$stc = ($can == '') ? 150 : $can;
		$str = wordwrap($string, $stc);
		$str = str_replace('&nbsp;', ' ', $str);
		$str = explode("\n", $str);
		$str = $str[0] . '...';
		return $str;
	}

	public function getAvatar(int $uid = 0, int $size = 50) {
		$user = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT user_id, p_avatar FROM u_perfil WHERE user_id = $uid"));
		$ensamble = (int)$user['p_avatar'] == 1 ? "{$user['user_id']}_$size" : "avatar";
		return "{$this->settings['avatar']}/{$ensamble}.jpg?" . uniqid();
	}

	public function generateName(int $total = 10) {
      $text = '';
      # GENERAMOS ID PARA LA LICENCIA
      for ($i = 65; $i <= 90; $i++) $text .= chr($i); // De A ... Z
      for ($i = 97; $i <= 122; $i++) $text .= chr($i); // De a ... z
      # Return
      return substr(str_shuffle($text), 0, $total);
   }

   public function covers_posts(string $image = null) {
      if(!empty($_FILES["portada"]["name"])) {
      	$portada = $_FILES["portada"];
      	# Obtenemos la extension del archivo
      	$ext = pathinfo($portada["name"], PATHINFO_EXTENSION);
         # Carpeta a guardar portadas
         $archivo = empty($image) ? $this->generateName(12) . '.webp' : $image;
         $urlimage = $archivo;
         $nuevoarchivo = TS_FILES . 'posts' . TS_PATH . $archivo;
         if(!is_dir(TS_FILES . 'posts' . TS_PATH)) {
         	mkdir(TS_FILES . 'posts' . TS_PATH, true);
         }
         // Revisamos si se envía realmente una imagen
         $check = getimagesize($portada["tmp_name"]);
         if($check === false) {
            return 'El archivo que vas a enviar no es una imagen válida, verifica la imagen del post ' . $check["mime"];
         }
         // Verificar tamaño de la imagen | 2 MB
         $mb_one = 1048576 * 2;
         if ($portada["size"] > $mb_one) {
            return 'La imagen debe pesar por mucho 1MB, el tama&ntilde;o de tu archivo es mayor que el permitido.';
         }
         $image = imagecreatefromstring(file_get_contents($portada["tmp_name"]));
         if ($image !== false) {
         	if (imagewebp($image, $nuevoarchivo, 80)) $urlimage = $archivo;
           	else move_uploaded_file($portada["tmp_name"], $nuevoarchivo);
            imagedestroy($image);
         } else move_uploaded_file($portada["tmp_name"], $nuevoarchivo);
      } else $urlimage = "";
      return $urlimage;
   }

   /**
    * Evitamos repetir este código
   */
   public function verifyUrl(string $portada = null, string $carpeta = 'posts') {
   	$filter = (filter_var($portada, FILTER_VALIDATE_URL) !== false);
   	$isUrl = $filter ? $portada : "{$this->settings['files']}/$carpeta/{$portada}";
   	
   	if(!file_exists($isUrl) AND $filter) {
   		$isUrl = "{$this->settings['public']}/images/images.svg";
   	}
		return empty($portada) ? "{$this->settings['public']}/images/images.svg" : $isUrl;
   }

}
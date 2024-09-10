<?php

/**
 * Autor: Miguel92
 * Ejemplo: {phpost css=["archivo.css"] js=["archivo.js"] favicon="archivo.ico" global=['key1' => 'val1', 'key2' => 'val2']} 
 * Enlace: #
 * Fecha: Mar 01, 2024 
 * Nombre: phpost
 * Proposito: Añadir las etiquetas necesarias dentro del <head>
 * Tipo: function 
 * Version: 1.3 
*/

class SmartyPHPost {

	# Variable para almacenar datos necesarios
	public $system;

	# Para almacenar las rutas de acceso a carpeta
	private $folders;

	# Para almacenar los enlaces
	private $routes;

	# Para añadir el códgo css y js directamente
	public $inLine;

	# Variables de permisos
	private $allow_extension = ['ico', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'];

	# Variables para determinar el tipo
	private $images_types = [
  	   'ico' => 'x-icon',
  	   'png' => 'png',
  	   'jpg' => 'jpeg',
  	   'jpeg' => 'jpeg',
  	   'webp' => 'webp',
  	   'svg' => 'svg+xml'
  	];

  	# Acceso a carpeta de los recursos a usar
  	private $resources = ['root', 'css', 'images', 'js'];

  	/**
  	 * @access public
  	 * 
  	*/
  	public function __construct($smarty) {
  		$this->system['tsCore'] = new tsCore;
  		$this->system['tsUser'] = new tsUser;
  		$accessClass = ['action', 'tsAction', 'tsFoto', 'tsMPs', 'tsMuro', 'tsNots', 'tsPage', 'tsPages', 'tsPerfil', 'tsPost'];

  		foreach($accessClass as $class) $this->system[$class] = $GLOBALS[$class];

		# Enlaces a los recursos y Directorios
		$settings = [
		   'routes' => [
		      'tema' => $this->system['tsCore']->settings['t_url'], 
		      'assets' => $this->system['tsCore']->settings['assets'],
		      'dashboard' => $this->system['tsCore']->settings['url'].'/dashboard',
		   ],
		   'folders' => [
		      'tema' => $smarty->template_dir['tema'], 
		      'assets' => $smarty->template_dir['assets'],
		      'dashboard' => $smarty->template_dir['dashboard']
		   ]
		];

		foreach($settings as $settingKey => $settingValue):
		   foreach($settingValue as $routeKey => $routeValue):
		      foreach($this->resources as $folder):
		      	$slash = ($settingKey == 'routes' ? '/' : '');
		      	$dir = ($folder == 'root' ? '' : "$slash$folder");
		         $this->$settingKey[$routeKey][$folder] = $routeValue . $dir;
		      endforeach;
		   endforeach;
		endforeach;
  	}

  	/**
  	 * @access private
  	 * Función para recorres las carpetas
  	 * @param $filename Archivo a comprobar su existencia.
  	 * @return string Url del archivo existente.
  	*/
  	private function setFileExistsInRoute(string $filename = ''):string {
  		$response = '';
  		foreach($this->folders as $f => $folders) {
  			foreach($folders as $name => $folder) {
  				$file_path = $folder . ($name == 'root' ? '' : TS_PATH) . $filename;
  				if(file_exists($file_path)) {
  					$response = $this->routes[$f][$name] . "/$filename";
  					break 2;
  				}
  			}
  		}
  		return $response;
  	}

  	private function Integrity(string $file = ''):string {
		$file_content = file_get_contents($file);
		$hash = base64_encode(hash('sha256', $file_content, true));
		return "integrity=\"sha256-$hash\" crossorigin=\"anonymous\"";
	}

	private function minifyCss($input) {
    	if (trim($input) === "") return $input;
    	return preg_replace(
    		['/\/\*[^*]*\*+([^\/][^*]*\*+)*\//', '/\s+/',],
    		['', ' ',],
        	$input
    	);
	}

	/**
	 * @access private
    * Genera una etiqueta HTML para un recurso (CSS o JS) desde JSDelivr.
    * 
    * @param string $file La ruta del recurso
    * @return string La etiqueta HTML generada.
   */
	private function generateHtmlTag(string $file = ''):string {
		$typeTag = pathinfo($file, PATHINFO_EXTENSION);
		$integrity = $this->Integrity($file);
		$filereturn = $file . '?' . uniqid();
		if($typeTag == 'css') {
			if(pathinfo($file, PATHINFO_BASENAME) == 'wysibb.css') {
				return "<link rel=\"stylesheet\" href=\"$file\"/>\n";
			} else {
				return "<link rel=\"stylesheet\" href=\"$file\" $integrity/>\n";
			} 
		} else {
			return "<script src=\"$filereturn\" $integrity></script>\n";
		}
	}

  	/**
  	 * @access private 
  	 * Verifica permisos
  	*/
  	private function setPermisson(string $choice = '', string $subchoice = '') {
  		$permisos = [
  			'live' => (int)$this->system['tsCore']->settings['c_allow_live'] === 1,
  			'notLive' => !in_array($this->system['tsPage'], ['login', 'registro']),
  			'admin' => ($this->system['tsPage'] == $choice AND $this->system['action'] == $subchoice),
  			'php_files' => ($this->system['tsPage'] == "php_files/p.$subchoice.home")
  		];
  		return $permisos[$choice];
  	}

	/**
  	 * @access private 
  	 * Verifica permisos
  	*/
  	private function setPermissonEspecial() {
		return ($this->system['tsUser']->is_admod OR $this->system['tsUser']->permisos['moacp'] OR $this->system['tsUser']->permisos['most'] OR $this->system['tsUser']->permisos['moayca'] OR $this->system['tsUser']->permisos['mosu'] OR $this->system['tsUser']->permisos['modu'] OR $this->system['tsUser']->permisos['moep'] OR $this->system['tsUser']->permisos['moop'] OR $this->system['tsUser']->permisos['moedcopo'] OR $this->system['tsUser']->permisos['moaydcp'] OR $this->system['tsUser']->permisos['moecp']);
	}

  	/**
  	 * @access public
  	 * Buscamos y agregamos el archivo individual
  	*/
  	public function setStylesheet(string $stylesheet = '') {
		$filename = $this->setFileExistsInRoute($stylesheet);
	  	if(!empty($filename)) {
	  		return $this->generateHtmlTag($filename);
	  	}
  	}

  	/**
  	 * @access public
  	 * Buscamos y agregamos las hojas de estilos
  	*/
  	public function setStylesheets($stylesheets, bool $string = false) {
  		if(!$string) {
  			$filename = $this->setFileExistsInRoute($stylesheets);
  			if(!empty($filename)) $tag = $this->generateHtmlTag($filename);
  			return $tag;
  		}
  		# Si no es array, lo convertimos
  		$stylesheets = (array)$stylesheets;
  		// Añadimos archivos escenciales
  		$stylesheets = [...$stylesheets, "{$this->system['tsPage']}.css", "wysibb.css"];
  		
	  	// Quitamos 'wysibb' del array
		if (in_array($this->system['tsPage'], ['admin', 'moderacion'])) {
		   $stylesheetsToRemove = ['wysibb.css', 'moderacion.css'];
		   foreach ($stylesheetsToRemove as $stylesheetToRemove) {
		      $posicion = array_search($stylesheetToRemove, $stylesheets);
		      if ($posicion !== false) unset($stylesheets[$posicion]);
		   }
		   $stylesheets = array_values($stylesheets);
		}

  		// Añadimos el archivo 'live.css'
  		if($this->setPermisson('live') && $this->setPermisson('notLive')) 
  			$stylesheets = [...$stylesheets, "live.css"];
  		// Añadimos si esta en Admin > rangos
  		if($this->setPermisson('admin', 'rangos')) $stylesheets = [...$stylesheets, "colorpicker.css"];
  		if(in_array($this->system['tsPage'], ['cuenta', 'login', 'registro'])) $stylesheets = [...$stylesheets, "buttons-social.css"];

  		foreach($stylesheets as $style) {
  			$filename = $this->setFileExistsInRoute($style);
  			if(!empty($filename)) {
  				$tag[] = $this->generateHtmlTag($filename);
  			}
  		}
  		return is_array($tag) ? join("", $tag) : $tag;
  	}

  	/**
  	 * @access public
  	 * Buscamos y agregamos el archivo individual
  	*/
  	public function setScript(string $script = '') {
		$filename = $this->setFileExistsInRoute($script);
	  	if(!empty($filename)) {
	  		return $this->generateHtmlTag($filename);
	  	}
  	}

  	/**
  	 * @access public
  	 * Buscamos y agregamos los scripts
  	*/
  	public function setScripts($scripts = NULL, bool $isArray = true) {
  		if(!$isArray) return $this->setScript($scripts);
	  	// Añadimos archivos escenciales
	  	$scripts = ['wysibb.js', 'jquery.plugins.js', ...$scripts, "{$this->system['tsPage']}.js"];
	  	
		if(in_array($this->system['tsPage'], ['cuenta', 'comunidades'])) {
	  		$scripts = [...$scripts, "avatar.js"];
		}
		if($this->system['tsPage'] == 'posts') {
	  		$scripts = ["highlight.min.js", ...$scripts];
		}
		if($this->system['tsPage'] == 'admin') {
			if($this->system['action'] == '')
  				$scripts = [...$scripts, "timeago.min.js", "timeago.es.js"];
  			// Quitamos 'wysibb' del array
			$posicion = array_search("wysibb.js", $scripts);
			if($posicion !== false) unset($scripts[$posicion]);
			$scripts = array_values($scripts);
		}
		// Añadimos el archivo 'moderacion.js'
	  	if($this->setPermissonEspecial() AND $this->system['tsPage'] != 'admin') {
	  		$scripts = [...$scripts, "moderacion.js"];
	  	}
		// Añadimos el archivo 'live.js'
	  	if($this->setPermisson('live') && $this->setPermisson('notLive')) $scripts = [...$scripts, "live.js"];
		if($this->setPermisson('php_files', 'borradores')) $scripts = [...$scripts, 'borradores.js'];
		
		if($this->setPermisson('php_files', 'favoritos')) $scripts = [...$scripts, 'favoritos.js'];

	  	// Movemos 'app.js' al final
		$appIndex = array_search('app.js', $scripts);
		if ($appIndex !== false) {
		   unset($scripts[$appIndex]);
		   $scripts[] = 'app.js';
		}
	  	foreach($scripts as $script) {
	  		$filename = $this->setFileExistsInRoute($script);
	  		if(!empty($filename)) {
	  			$tag[] = $this->generateHtmlTag($filename);
	  		}
	  	}
	  	return is_array($tag) ? join("", $tag) : $tag;
  	}

  	public function setCDN(array $cdn = []) {
  		$mycdn = "https://cdn.jsdelivr.net/combine/" . join(',', $cdn);
  		return "<script src=\"$mycdn\"></script>\n";
  	}

  	public function setScriptLineGlobal() {
  		$claves = [];
  		if($this->system['tsUser']->uid != 0) $claves['user_key'] = $this->system['tsUser']->uid;
  		if(isset($this->system['tsPost']['post_id'])) {
			$claves['postid'] = (int)$this->system['tsPost']['post_id'];
			$claves['autor'] = (int)$this->system['tsPages']['autor'];
		}
		if(isset($this->system['tsFoto']['foto']['foto_id'])) {
			$claves['fotoid'] = (int)$this->system['tsFoto']['foto']['foto_id'];
		}
	
		if(isset($this->system['tsComunidades']->comid)) {
			$claves['comid'] = (int)$this->system['tsComunidades']->comid;
		}
		if(isset($this->system['tsComunidades']->temaid)) {
			$claves['temaid'] = (int)$this->system['tsComunidades']->temaid;
		}

		// Siempre
		$claves['assets_images'] = $this->system['tsCore']->settings['assets'] . '/images';
		$claves['tema_images'] = $this->system['tsCore']->settings['images'];
		$others = ['url', 'assets', 'domain', 'titulo', 'slogan'];
		foreach ($others as $key => $other) $claves[$other] = $this->system['tsCore']->settings[$other];
		//
		foreach ($claves as $key => $value) 
			$global[$key] = "\t$key: " . (is_numeric($value) ? $value : "'$value'");
		ksort($claves);
		$append = "{\n" . join(",\n", $global) . "\n}";

		if($this->system['tsUser']->uid != 0 AND $this->system['tsPage'] == 'cuenta') {
			$avatar = "{$this->system['tsCore']->settings['avatar']}/" . ($this->system['tsPerfil']['p_avatar'] ? "{$this->system['tsUser']->uid}_120" : 'avatar') . '.jpg';
			$portada = "";
			if(isset($this->system['tsPerfil']['user_portada'])) {
				$portada = "\n\tavatar.cover = '{$this->system['tsPerfil']['user_portada']}';";
			}
			$append .= <<< LINEA
			\ndocument.addEventListener("DOMContentLoaded", function() {
				avatar.uid = {$this->system['tsUser']->uid};
				avatar.current = '$avatar';$portada
			});
			LINEA;
		}
		$hash = base64_encode(hash('sha256', $append, true));
  		return <<< LINEA
		<script type="text/javascript" integrity="sha256-$hash" crossorigin="anonymous">
		const global_data = $append
		</script>\n
		LINEA;
  	}

  	public function setFavicon(string $favicon = '') {
		$filename = $this->setFileExistsInRoute($favicon);
		$types = [
	  	   'ico' => 'x-icon',
	  	   'png' => 'png',
	  	   'jpg' => 'jpeg',
	  	   'jpeg' => 'jpeg',
	  	   'webp' => 'webp',
	  	   'svg' => 'svg+xml'
	  	];
	  	$ext = pathinfo($filename, PATHINFO_EXTENSION);
	  	$filename .= '?' . uniqid();
		return "<link href=\"$filename\" rel=\"shortcut icon\" type=\"image/{$types[$ext]}\" />\n";
  		
  	}

}

function smarty_function_phpost($params, &$smarty) {

	# Inicializamos la clase
	$SmartyPHPost = new SmartyPHPost($smarty);

	# Inicializamos la variable
	$template = '';

	$SmartyPHPost->inLine = $params['codeline'] ?? false;

	if(isset($params['favicon'])) {
		$template .= "<!-- Añadimos el icono del sitio -->\n";
		$template .= $SmartyPHPost->setFavicon($params['favicon']);
	}
	
	# Añadimos las hojas de estilos
	if(isset($params['css'])) {
		$template .= "<!-- Añadimos los estilos elegidos y necesarios -->\n";
		$template .= $SmartyPHPost->setStylesheets($params['css'], is_array($params['css']));
	}

	if(isset($params['scriptGlobal'])) {
		$template .= $SmartyPHPost->setScriptLineGlobal();
	}	

	if(isset($params['cdn'])) {
		$template .= "<!-- Añadimos CDN -->\n";
		$template .= $SmartyPHPost->setCDN($params['cdn']);
	}

	# Añadimos los scripts
	if(isset($params['js'])) {
		$template .= "<!-- Añadimos los scripts elegidos y necesarios -->\n";
		$template .= $SmartyPHPost->setScripts($params['js'], is_array($params['js']));
	}

	return trim($template);
}
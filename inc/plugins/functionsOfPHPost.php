<?php 

/**
 * Generamos las funciones necesarias para el
 * funcionamiento del plugin
*/
class fnPHPost {

	// EXTENSIONES PARA IMAGENES SOLAMENTE
	private $extension = ['ico', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'];

	// TIPOS DE ARCHIVOS 
	private $types = [
  	   'ico' => 'x-icon',
  	   'png' => 'png',
  	   'jpg' => 'jpeg',
  	   'jpeg' => 'jpeg',
  	   'webp' => 'webp',
  	   'svg' => 'svg+xml'
  	];
	
	public function __construct() {
		# Coloque su código aquí...
	}

	/**
	 * Funcion para generar cache (básico)
	 * @return string ej: abcdef...
	*/
	private function getCache() {
		return uniqid('p');
	}

	/**
	 * Funcion para obtener la extensión del archivo
	 * @param string $file
	 * @return string "extension"
	*/
	private function getExtension(string $file = '') {
		return pathinfo($file)['extension'];
	}

	private function searchFile(string $folder = '', string $file = '', bool $public = false) {
		global $smarty;
		if($public) {
			// Acá busca dentro de la carpeta "root/public/.."
			$public_theme = $smarty->template_dir['public'] . $folder . TS_PATH;
		} else {
			// Acá busca dentro de la carpeta "root/themes/{tema_activo}/.."
			$public_theme = $smarty->template_dir[$folder];
		}
		return file_exists($public_theme . $file);
	}

	private function setURL(string $folder = '', string $file = '', bool $public = false) {
		global $tsCore;
		if($folder === 'tema') {
			// Acá busca dentro de la carpeta "root/themes/{tema_activo}/"
			$public_theme = $tsCore->settings['tema']['t_url'];
		} elseif($public) {
			// Acá busca dentro de la carpeta "root/public/{folder}"
			$public_theme = "{$tsCore->settings['public']}/$folder";
		} else {
			// Acá busca dentro de la carpeta "root/themes/{tema_activo}/{folder}"
			$public_theme = $tsCore->settings[$folder];
		}
		return "$public_theme/$file";
	}

	/**
	 * Generamos un icono de forma automatica con:
	 * @link https://ui-avatars.com/
	*/
	private function uiAvatars() {
		global $tsCore;
		/* $params[nombre] => valor*/
		$parametros['background'] = '0D8ABC';
		$parametros['color'] = 'fff';
		$parametros['name'] = $tsCore->settings['titulo'];
		$parametros['size'] = 64;
		$parametros['font-size'] = '0.50';
		$parametros['bold'] = true;
		$parametros['format'] = 'png';
		//
		foreach ($parametros as $nombre => $valor) $unir[$nombre] = "$nombre=$valor";
		return $unir;
	}

	/**
	 * Funcion para generar la etiqueta html
	 * @param string $favicon
	 * @return html
	*/
	public function getFavicon(string $favicon = '') {
		global $tsCore, $smarty;
		$extension = self::getExtension($favicon);
		if(in_array($extension, $this->extension)) {
			// Comprobamos si existe el favicon!
			if(self::searchFile('images', $favicon)) {
				$href = "{$tsCore->settings['images']}/$favicon?" . self::getCache();
				$type = $this->types[$extension];
			} else {
				$link = "<!-- No existe $favicon -->\n";
				// URL COMPLETO
				$href = 'https://ui-avatars.com/api/?' . join('&', self::uiAvatars());
				$type = $this->types['png'];
			}
		}
		if($favicon === 'not') {
			$link = "<!-- No existe el parametro 'favicon' -->\n";
			// URL COMPLETO
			$href = 'https://ui-avatars.com/api/?' . join('&', self::uiAvatars());
			$type = $this->types['png'];
		}
		$link .= "<link href=\"$href\" rel=\"shortcut icon\" type=\"image/$type\" />\n";
		return $link;
	}

	/**
	 * Funcion para añadir los estilos
	*/
	public function getStyle(string $css = '') {
		global $tsCore, $tsPage;
		// themes/{tema_activo}
		if(self::searchFile('tema', $css)) {
			$source = self::setURL('tema', $css) . "?" . self::getCache();
			$link .= "<link href=\"$source\" rel=\"stylesheet\" type=\"text/css\" />\n";
		// themes/{tema_activo}/css
		} elseif(self::searchFile('css', $css)) {
			$source = self::setURL('css', $css) . "?" . self::getCache();
			$link .= "<link href=\"$source\" rel=\"stylesheet\" type=\"text/css\" />\n";
		// public/css
		} elseif(self::searchFile('css', $css, true)) {
			$source = self::setURL('css', $css, true) . "?" . self::getCache();
			$link .= "<link href=\"$source\" rel=\"stylesheet\" type=\"text/css\" />\n";
		// Solo si esta en la página "posts"
		} elseif($tsPage === 'posts') {
			$source = self::setURL('css', 'AtomOneDark.css', true) . "?" . self::getCache();
			$link .= "<link href=\"$source\" rel=\"stylesheet\" type=\"text/css\" />\n";
		}
		return $link;
	}

	/**
	 * Funcion para obtener los permisos de usuario (administrador, moderador o especial)
	*/
	public function getPerms() {
		global $tsUser;
		return ($tsUser->is_admod OR $tsUser->permisos['moacp'] OR $tsUser->permisos['most'] OR $tsUser->permisos['moayca'] OR $tsUser->permisos['mosu'] OR $tsUser->permisos['modu'] OR $tsUser->permisos['moep'] OR $tsUser->permisos['moop'] OR $tsUser->permisos['moedcopo'] OR $tsUser->permisos['moaydcp'] OR $tsUser->permisos['moecp']);
	}

	/**
	 * Funcion para obtener las notificaciones
	*/
	public function getLive() {
		global $tsCore;
		return ((int)$tsCore->settings['c_allow_live'] === 1);
	}

	/**
	 * Funcion para añadir los estilos
	*/
	public function getScript(string $js = '', array $denegar = []) {
		global $tsCore;
		if(self::searchFile('js', $js)) {
			// Evitamos que los archivos se dupliquen
			if(!in_array($js, $denegar)) {
				// themes/{tema_activo}
				$source = self::setURL('js', $js) . "?" . self::getCache();
				$link .= "<script src=\"$source\" type=\"text/javascript\"></script>\n";
			}
		} elseif(self::searchFile('js', $js, true)) {
			// Evitamos que los archivos se dupliquen
			if(!in_array($js, $denegar)) {
				// themes/{tema_activo}/js
				$source = self::setURL('js', $js, true) . "?" . self::getCache();
				$link .= "<script src=\"$source\" type=\"text/javascript\"></script>\n";
			}
		}
		return $link;
	}

	public function getGlobalData() {
		global $tsCore, $tsUser, $tsPost, $tsFoto, $tsNots, $tsMPs, $tsAction;
		//
		if(isset($tsUser->uid) OR $tsUser->uid != 0) $data['user_key'] = (int)$tsUser->uid;
		$data['img'] = $tsCore->settings['images'];
		$data['url'] = $tsCore->settings['url'];
		$data['domain'] = $tsCore->settings['domain'];
		$data['s_title'] = $tsCore->settings['titulo'];
		$data['s_slogan'] = $tsCore->settings['slogan'];
		if(isset($tsPost['post_id'])) $data['postid'] = (int)$tsPost['post_id'];
		if(isset($tsPost['foto_id'])) $data['fotoid'] = (int)$tsFoto['foto_id'];
		// Modificamos el array
		foreach ($data as $key => $value) 
			$global[$key] = "\t$key: " . (is_numeric($value) ? $value : "'$value'");
		//
		ksort($global);
		$global = join(",\n", $global);
		// En el caso que exista notificaciones y/o mensajes
		$globalNMA = '';
		if($tsNots > 0 OR $tsMPs > 0 AND $tsAction != 'leer') {
			$notifica = ($tsNots > 0) ? "notifica.popup($tsNots);" : "// notifica";
			$mensaje = ($tsNots > 0) ? "mensaje.popup($tsMPs);" : "// mensaje";
			$globalNMA = <<< NMA
			$(document).ready(() => {
				$notifica
				$mensaje
			});
			NMA;
		}
		return <<< LINEA
		<script type="text/javascript">
		var global_data = {
		$global
		}
		$globalNMA
		</script>
		LINEA;
	}
	
}
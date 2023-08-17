<?php

/**
 * Smarty plugin para incluir archivos CSS y JS de forma independiente.
 *
 * Uso: Solo require el nombre del archivo
 *  1 - {phpost file="archivo.css"} 
 *  2 - {phpost file="archivo.js"} 
 *  3 - {phpost file="archivo.ico"} 
 *  4 - {phpost files=["archivo1.css","archivo2.css"] extension="css"} 
 *  5 - {phpost files=["archivo1.js","archivo2.js"] extension="js"} 
 *  6 - {phpost global=['key1' => 'val1', 'key2' => 'val2']} 
 *
 * Genera una etiqueta <link> si se proporciona un archivo CSS,
 * o una etiqueta <script> si se proporciona un archivo JS.
 * Si se proporciona un archivo CSS, se agrega un parámetro de consulta
 * con la marca de tiempo actual para evitar el almacenamiento en caché.
 *
 * @param array $params Parámetros pasados a la función (en este caso, 'file').
 * @param Smarty_Internal_Template $smarty Instancia del objeto Smarty.
 * @return string Código HTML generado por la función.
 */

function buscarArchivo(string $file = '', array $root = [], array $url = []) {
	$random_string = substr(uniqid(), -10);
	$ext = pathinfo($file, PATHINFO_EXTENSION);
	$filename = "$file?app$random_string";
	foreach($root as $k => $folder) {
	   if(file_exists($folder . $file)) {
	      $rtn = $url[$k] . "/$filename";
	   } else if(file_exists($folder . $ext . DS . $file)) {
	      $rtn = $url[$k] . "/$ext/$filename";
	   } else if (filter_var($file, FILTER_VALIDATE_URL)) {
			$tsCore = new tsCore;
	      $rtn = $file . ($GLOBALS["tsPage"] === 'registro' ? '?render=' . $tsCore->settings['pkey']  : '');
	   }
	}
	return $rtn;
}

function recorrer(array $param = [], array $root = [], array $url = [], string $type = '') {
	foreach($param as $f => $name) {
  		$UrlArchivo = buscarArchivo($name, $root, $url);
  		if($type === 'css') {
			if(!empty($UrlArchivo)) {
				$response .= "<link href=\"{$UrlArchivo}\" rel=\"stylesheet\" type=\"text/css\">\n";
			}
		} else {
			if(!empty($UrlArchivo)) {
				$response .= "<script src=\"{$UrlArchivo}\"></script>\n";
			}
		}
	}
	return $response;
}
function normalito(string $file = '', array $root = [], array $url = [], string $type = '') {
	$UrlArchivo = buscarArchivo($file, $root, $url);
	if($type === 'css') {
		if(!empty($UrlArchivo)) return "<link href=\"{$UrlArchivo}\" rel=\"stylesheet\" type=\"text/css\">";
	} else {
		if(!empty($UrlArchivo)) return "<script src=\"{$UrlArchivo}\"></script>";
	}
}

function smarty_function_phpost($params, &$smarty) {
	// Inicializamos la variable
	$output = '';
	$tsCore = new tsCore;
	$tsUser = new tsUser;
	// Evitamos repetirlo
  	$tsConfig = $smarty->tpl_vars['tsConfig']->value;
	// Carpeta donde se aloja
	$carpeta = [
		'tema' => $smarty->template_dir['tema'],
		'images' => $smarty->template_dir['images'],
		'css' => $smarty->template_dir['css'],
		'js' => $smarty->template_dir['js']
	]; ;
	// URL 
	$enlace = [
		'tema' => $tsConfig['tema']['t_url'],
		'images' => $tsConfig['images'],
		'css' => $tsConfig['css'],
		'js' => $tsConfig['js']
	];
	// Elemento a usar, si es un arreglo o un archivo simple
	$elemento = is_array($params['files']) ? $params['files'] : $params['file'];
	// Obtenemos la extensión del archivo simple
	$ExtensionFile = (!is_array($param['files'])) ? pathinfo($params['file'], PATHINFO_EXTENSION) : '';
	// En caso que sea CSS o JS
	// Se usa de esta manera, por que si no, lo agrega 2 veces!
	foreach ($elemento as $k => $val) {
		if($val === 'registro.js') {
			$nuevoElemento = "https://www.google.com/recaptcha/api.js";
			array_splice($elemento, $k, 0, $nuevoElemento);
		} 
		if($val === 'cuenta.js') {
			$nuevoElemento = "https://cdn.jsdelivr.net/npm/croppr@2.3.1/dist/croppr.min.js";
			array_splice($elemento, $k, 0, $nuevoElemento);
			$nuevoElemento = "cuenta.subir-avatar.js";
			array_splice($elemento, $k + 1, 0, $nuevoElemento);
		}
		if($val === 'cuenta.css') {
			$nuevoElemento = "https://cdn.jsdelivr.net/npm/croppr@2.3.1/dist/croppr.min.css";
			array_splice($elemento, $k, 0, $nuevoElemento);
		}
		// NO => array_splice(.....);
	}

	if (in_array($params['extension'], ['css', 'js']) OR in_array($ExtensionFile, ['css', 'js'])) {
		$output = is_array($params['files']) ? 
	  	recorrer($elemento, $carpeta, $enlace, $params['extension']) : 
	  	normalito($elemento, $carpeta, $enlace, );

  		// Es administrador? o tiene los permisos?
  		if($tsUser->is_admod || $tsUser->permisos['moacp'] || $tsUser->permisos['most'] || $tsUser->permisos['moayca'] || $tsUser->permisos['mosu'] || $tsUser->permisos['modu'] || $tsUser->permisos['moep'] || $tsUser->permisos['moop'] || $tsUser->permisos['moedcopo'] || $tsUser->permisos['moaydcp'] || $tsUser->permisos['moecp']) {
  			if($ExtensionFile === 'js' OR $params['extension'] === 'js') {
	  			$output .= normalito('moderacion.js', $carpeta, $enlace, 'js');
  			}
  		}
	} elseif(in_array($ExtensionFile, ['ico', 'png', 'jpg', 'jpeg', 'svg', 'webp'])) {
		foreach($carpeta as $k => $folder) {
  	      if(file_exists($folder . "images" . DS . $file)) {
  	         $UrlArchivo = $enlace[$k] . "/images/$elemento?app" . substr(uniqid(), -10);
  	      }
  	   }
  	   $types = [
  	   	'ico' => 'x-icon',
  	   	'png' => 'png',
  	   	'jpg' => 'jpeg',
  	   	'jpeg' => 'jpeg',
  	   	'webp' => 'webp',
  	   	'webp' => 'svg+xml'
  	   ];
	   $output .= "<link href=\"$UrlArchivo\" rel=\"icon\" type=\"image/{$types[$ExtensionFile]}\">";
	}
	// Con esta comprobacion de 'js' evitamos que agregue demás
	if($ExtensionFile === 'js' OR $params['extension'] === 'js') {
	  	if((int)$tsCore->settings['c_allow_live'] AND $tsUser->is_member) {
		  	$output .= "\n<link href=\"".buscarArchivo("live.css", $carpeta, $enlace)."\" rel=\"stylesheet\" type=\"text/css\">\n";
		   $output .= "<script src=\"".buscarArchivo("live.js", $carpeta, $enlace)."\"></script>";
	  	}
	}
	
  	return trim($output);
}
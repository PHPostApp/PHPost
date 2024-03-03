<?php 

/**
 * Autor: Miguel92
 * Ejemplo: {jsdelivr type='styles|scripts' sources=['librerias'] combine=true} 
 * Enlace: https://www.jsdelivr.com/ 
 * Fecha: Feb 28, 2024 
 * Nombre: jsdelivr
 * Proposito: Añadir cdn desde la pagina https://www.jsdelivr.com/ 
 * Tipo: function 
 * Version: 2.0 
*/

class jsdelivr {

	private $jsdelivr = 'https://cdn.jsdelivr.net/';

	private $combine = 'combine/';

	/**
    * Constructor de la clase JSDelivr.
   */
	public function __construct() {
		# mi código...
	}

	/**
	 * @access private
    * Lee los datos desde un archivo y los devuelve como un array asociativo.
    * 
    * @param string $fileName El nombre del archivo a leer.
    * @return array Los datos del archivo como un array asociativo.
    * @throws Exception Si el archivo no existe.
   */
	private function readDataFromFile(mixed $fileName = NULL) {
		if ($fileName === '') {
        throw new Exception("El nombre del archivo no puede estar vacío.");
    	}
		$archivo = TS_INCLUDES . 'jsdelivr' . TS_PATH . "$fileName.txt";
		if (!file_exists($archivo)) {
			throw new Exception("El archivo $fileName.txt no existe en jsdelivr.");
		}
		# Leemos el archivo
		$archivo_leer = fopen($archivo, "r");
		// Iterar sobre cada línea del archivo
		while (($linea = fgets($archivo_leer)) !== false) {
		   // Utilizar expresión regular para dividir la línea en clave y valor
	   	if (preg_match('/([^:]+):(.+)/', $linea, $matches)) {
	     		// Añadir los datos al array de información
	     		$informacion[trim($matches[1])] = trim($matches[2]);
	   	}
		}
		# Cerramos el archivo
		fclose($archivo_leer);
		# Retornamos los datos
		return $informacion;
	}

	/**
	 * @access public
    * Obtiene información sobre plugins desde uno o más archivos.
    * 
    * @param mixed $files El nombre del archivo o un array de nombres de archivos.
    * @return array La información de los plugins como un array.
   */
	public function getPluginsInfo($files) {
		if(is_array($files)) {
			foreach($files as $file) {
				$data[] = self::readDataFromFile($file);
			}
		} else {
			$data = self::readDataFromFile($files);
		}
		return $data;
	}

	/**
	 * @access public
    * Genera una etiqueta HTML para un recurso (CSS o JS) desde JSDelivr.
    * 
    * @param string $type El tipo de recurso ('css' o 'js').
    * @param mixed $routes La ruta o un array de rutas de los recursos.
    * @return string La etiqueta HTML generada.
   */
	public function generateHtmlTag(string $type = '', mixed $routes = ''):string {
		$combine = is_array($routes) ? $this->combine . join(',', $routes) : $routes;
		$route = $this->jsdelivr . $combine;
		//
		return ($type == 'css') ? "<link rel=\"stylesheet\" href=\"$route\" />" : "<script src=\"$route\"></script>";
	}

	/**
	 * @access public
    * Crea la ruta de un paquete npm (CSS o JS) desde JSDelivr.
    * 
    * @param mixed $data Los datos del paquete.
    * @param string $type El tipo de recurso ('css' o 'js').
    * @return string La ruta del paquete.
   */
	public function createNpmRoute(mixed $data = '', string $type = 'css'):string {
		$pluginname = 'npm/' . $data['name'];
		# Si mostramos la versión ex: @1.2.12
		if($data['on'] == 'true') $pluginname .= "@{$data['version']}";
		# Si añadimos el archivo al final ex: /filename.min.css
		if(!empty($data[$type])) $pluginname .= "/{$data[$type]}";
		return $pluginname;
	}

	/**
	 * @access public
    * Verifica si la página dada no coincide con ninguna de las páginas predeterminadas.
    * 
    * @param string $page La página a verificar.
    * @return bool True si no coincide, False de lo contrario.
   */
	public function matchPages(mixed $page = ''):bool {
		// En caso que esten en 2 o más páginas
		$arrayexplode = explode(',', $page);
		$arrayexplode = (count($arrayexplode) > 1) ? $arrayexplode : [];
		// Normal
		return !in_array($page, ['perfil', 'cuenta', 'admin', ...$arrayexplode]);
	}

}

function smarty_function_jsdelivr($params, &$smarty) {
	$jsdelivr = new jsdelivr;

	# Creamos la variable en la cual almacenaremos toda la info
	$sl2html = '';

	# Página
	$pagina = $smarty->tpl_vars['tsPage']->value;

	if($params['type'] == 'css' OR $params['type'] == 'js') {
		# Obtenemos toda la informacion de los archivos
		$plugins = $jsdelivr->getPluginsInfo($params['files']);
	
	   if(is_array($params['files'])) {
		   # Recorremos el array
		   foreach($plugins as $sid => $plugin):
				$pluginname = $jsdelivr->createNpmRoute($plugin, $params['type']);
				$arrayjoined = $jsdelivr->matchPages($plugin['page']);
				//
				if($arrayjoined) $fileurl[] = $pluginname;
				elseif($plugin['page'] == $pagina) $fileurl[] = $pluginname;
				
			endforeach;
		} else {
		   # Recorremos el array
			$pluginname = $jsdelivr->createNpmRoute($plugins, $params['type']);
			$arrayjoined = $jsdelivr->matchPages($plugins['page']);
			//
			if($arrayjoined) $fileurl = $pluginname;
			elseif($plugins['page'] == $pagina) $fileurl = $pluginname;
		}

		$fortype = $params['type'] == 'css' ? 'stylesheet' : 'script';
		$sl2html .= "<!-- JSDelivr Plugins v2 creado por Miguel92 ($fortype) -->\n";
		$sl2html .= $jsdelivr->generateHtmlTag($params['type'], $fileurl);
	}

	return trim($sl2html);
}
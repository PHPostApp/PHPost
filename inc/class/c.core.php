<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Funciones globales
 *
 * @name    c.core.php
 * @author  Miguel92 & PHPost.es
 */
class tsCore {
    
	var $settings;		// CONFIGURACIONES DEL SITIO

	// No quitar, ni reemplazar
	private $keygen = 'UmlzdXMyMw==';
	
	public function https_on() {
	   if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') $isSecure = false;
	   elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') $isSecure = true;
	   elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
	      $isSecure = true;
	   }
	   $isSecure = 'http' . ($isSecure ? 's' : '') . '://';
	   return $isSecure;
	}

	public function __construct() {
		// CARGANDO CONFIGURACIONES
		$this->settings = $this->getSettings();
		$this->settings['domain'] = str_replace($this->https_on(),'',$this->settings['url']);
		$this->settings['categorias'] = $this->getCategorias();
      $this->settings['default'] = $this->settings['url'].'/themes/default';
		$this->settings['tema'] = $this->getTema();
		$this->settings['images'] = $this->settings['tema']['t_url'].'/images';
      $this->settings['css'] = $this->settings['tema']['t_url'].'/css';
		$this->settings['js'] = $this->settings['tema']['t_url'].'/js';
		//
		$this->settings['avatar'] = $this->settings['url'].'/files/avatar';
		$this->settings['uploads'] = $this->settings['url'].'/files/uploads';
		$this->settings['public'] = $this->settings['url'].'/public';
		// Autenticarme con las redes
		$this->settings['oauth'] = $this->OAuth();
      //
     	if($_GET['do'] == 'portal' || $_GET['do'] == 'posts') 
     		$this->settings['news'] = $this->getNews();
		# Mensaje del instalador y pendientes de moderación #
		// $this->settings['install'] = $this->existinstall();
		$this->settings['novemods'] = $this->getNovemods();
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

	/*
		getSettings() :: CARGA DESDE LA DB LAS CONFIGURACIONES DEL SITIO
	*/
	public function getSettings() {
		$query = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM w_configuracion')); 
		return $query;
	}
	
	public function getNovemods() {
      $datos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT 
        	(SELECT count(post_id) FROM p_posts WHERE post_status = \'3\') as revposts, 
        	(SELECT count(cid) FROM p_comentarios WHERE c_status = \'1\' ) as revcomentarios, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias WHERE d_type = \'1\') as repposts, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias WHERE d_type = \'2\') as repmps, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias WHERE d_type = \'3\') as repusers, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias  WHERE d_type = \'4\') as repfotos, 
        	(SELECT count(susp_id) FROM u_suspension) as suspusers, 
        	(SELECT count(post_id) FROM p_posts WHERE post_status = \'2\') as pospelera, 
        	(SELECT count(foto_id) FROM f_fotos WHERE f_status = \'2\') as fospelera')
   	);
		$datos['total'] = $datos['repposts'] + $datos['repfotos'] + $datos['repmps'] + $datos['repusers'] + $datos['revposts'] + $datos['revcomentarios'];
		return $datos;  
	}
	/*
		getCategorias()
	*/
	public function getCategorias() {
		// CONSULTA
		$categorias = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT cid, c_orden, c_nombre, c_seo, c_img FROM p_categorias ORDER BY c_orden'));
      //
      return $categorias;
	}
	/*
		getTema()
	*/
	public function getTema() {
		$id = $this->settings['tema_id'];
		$data = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', "SELECT tid, t_name, t_url, t_path, t_copy FROM w_temas WHERE tid = $id LIMIT 1"));
      $data['t_url'] = $this->settings['url'] . '/themes/' . $data['t_path'];
		//
		return $data;
	}
	/*
        getNews()
    */
    function getNews()
    {
        //
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT not_body FROM w_noticias WHERE not_active = \'1\' ORDER by RAND()');
		while($row = db_exec('fetch_assoc', $query)){
		  $row['not_body'] = $this->parseBBCode($row['not_body'],'news');
          $data[] = $row;
		}
        //
        return $data;
    }
	
	//COMPROBACIONES DE LA EXISTENCIA DEL INSTALADOR O ACTUALIZADOR
	
	function existinstall() 
    {
		$install_dir = TS_ROOT . '/install/';
		$upgrade_dir = TS_ROOT . '/upgrade/';
		if(is_dir($install_dir)) return '<div id="msg_install">Por favor, elimine la carpeta <b>install</b></div>';		
		if(is_dir($upgrade_dir)) return '<div id="msg_install">Por favor, elimine la carpeta <b>upgrade</b></div>';
	}
    
    // FUNCIÓN CONCRETA PARA CENSURAR
	
	function parseBadWords($c, $s = FALSE) 
    {
        $q = result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT word, swop, method, type FROM w_badwords '.($s == true ? '' : ' WHERE type = \'0\'')));
        
        foreach($q AS $badword) 
        {
        $c = str_ireplace((empty($badword['method']) ? $badword['word'] : $badword['word'].' '),($badword['type'] == 1 ? '<img class="qtip" title="'.$badword['word'].'" src="'.$badword['swop'].'" align="absmiddle"/>' : $badword['swop'].' '),$c);
        }
        return $c;
	}        
	
	/*
		setLevel($tsLevel) :: ESTABLECE EL NIVEL DE LA PAGINA | MIEMBROS o VISITANTES
	*/
	function setLevel($tsLevel, $msg = false){
		global $tsUser;
		
		// CUALQUIERA
		if($tsLevel == 0) return true;
		// SOLO VISITANTES
		elseif($tsLevel == 1) {
			if($tsUser->is_member == 0) return true;
			else {
				if($msg) $mensaje = 'Esta pagina solo es vista por los visitantes.';
				else $this->redirect('/');
			}
		}
		// SOLO MIEMBROS
		elseif($tsLevel == 2){
			if($tsUser->is_member == 1) return true;
			else {
				if($msg) $mensaje = 'Para poder ver esta pagina debes iniciar sesi&oacute;n.';
				else $this->redirect('/login/?r='.$this->currentUrl());
			}
		}
		// SOLO MODERADORES
		elseif($tsLevel == 3){
			if($tsUser->is_admod || $tsUser->permisos['moacp']) return true;
			else {
				if($msg) $mensaje = 'Estas en un area restringida solo para moderadores.';
				else $this->redirect('/login/?r='.$this->currentUrl());
			}
		}
		// SOLO ADMIN
		elseif($tsLevel == 4) {
			if($tsUser->is_admod == 1) return true;
			else {
				if($msg) $mensaje = 'Estas intentando algo no permitido.';
				else $this->redirect('/login/?r='.$this->currentUrl());
			}
		}
		//
		return array('titulo' => 'Error', 'mensaje' => $mensaje);
	}

	/*
		redirect($tsDir)
	*/
	function redirectTo($tsDir){
		header("Location: " . urldecode($tsDir));
		exit();
	}
	public function redireccionar(string $page = '', string $subpage = '', string $param = '') {
		$param = empty($param) ? '' : "?$param";
		$this->redirectTo("{$this->settings['url']}/$page/$subpage$param");		
	}
   /*
      getDomain()
   */
   public function getDomain() {
      $domain = explode('/', str_replace($this->https_on(), '', $this->settings['url']));
      $domain = (is_array($domain)) ? explode('.', $domain[0]) : explode('.', $domain);
      //
      $t = safe_count($domain);
      $domain = $domain[$t - 2] . '.' . $domain[$t - 1];
      //
      return $domain;
   }
	/*
		currentUrl()
	*/
	public function currentUrl(){
		$current_url = $this->https_on() . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		return urlencode($current_url);
	}
	/*
		setJSON($tsContent)
	*/
	public function setJSON($data, $type = 'encode'){
      return ($type == 'encode') ? json_encode($data) : json_decode($data, true);            
	}
	/*
		setPagesLimit($tsPages, $start = false)
	*/
	function setPageLimit($tsLimit, $start = false, $tsMax = 0){
		if($start == false) $tsStart = empty($_GET['page']) ? 0 : (int)(($_GET['page'] - 1) * $tsLimit);
		else {
    		$tsStart = (int) $_GET['s'];
         $continue = $this->setMaximos($tsLimit, $tsMax);
         if($continue == true) $tsStart = 0;
      }
		//
		return $tsStart.','.$tsLimit;
	}
    /*
        setMaximos()
        :: MAXIMOS EN LAS PAGINAS
    */
   public function setMaximos(int $tsLimit = 0, int $tsMax = 0){
        // MAXIMOS || PARA NO EXEDER EL NUMERO DE PAGINAS
        $ban1 = ($_GET['page'] * $tsLimit);
        if($tsMax < $ban1){
            $ban2 = $ban1 - $tsLimit;
            if($tsMax < $ban2) return true;
        } 
        //
        return false;
   }

	/*
		getPages($tsTotal, $tsLimit)
		: PAGINACION
	*/
	public function getPages(int $tsTotal = 0, int $tsLimit = 0){
		//
		$tsPages = ceil($tsTotal / $tsLimit);
		// PAGINA
		$tsPage = empty($_GET['page']) ? 1 : $_GET['page'];
		// ARRAY
		$pages['current'] = $tsPage;
		$pages['pages'] = $tsPages;
		$pages['section'] = $tsPages + 1;
		$pages['prev'] = $tsPage - 1;
		$pages['next'] = $tsPage + 1;
      $pages['max'] = $this->setMaximos($tsLimit, $tsTotal);
		// RETORNAMOS HTML
		return $pages;
	}

   /*
      getPagination($total, $per_page)
   */
   public function getPagination($total, $per_page = 10){
      // PAGINA ACTUAL
      $page = empty($_GET['page']) ? 1 : (int) $_GET['page'];
      // NUMERO DE PAGINAS
      $num_pages = ceil($total / $per_page);
      // ANTERIOR
      $prev = $page - 1;
      $pages['prev'] = ($page > 0) ? $prev : 0;
      // SIGUIENTE 
      $next = $page + 1;
      $pages['next'] = ($next <= $num_pages) ? $next : 0;
      // LIMITE DB
      $pages['limit'] = (($page - 1) * $per_page).','.$per_page; 
      // TOTAL
      $pages['total'] = $total;
      //
      return $pages;
   }

   /**/
	public function pageIndex($base_url, &$start, $max_value, $num_per_page, $flexible_start = false) {
	   // Remove the 's' parameter from the base URL
	   $base_url = preg_replace('/[?&]s=\d*/', '', $base_url);
	   // Ensure $start is a non-negative integer and a multiple of $num_per_page
	   $start = max(0, (int) $start);
	   $start = $start - ($start % $num_per_page);
	   // Generate the link format based on whether flexible_start is enabled or not
	   $base_link = '<a class="navPages" href="' . ($flexible_start ? $base_url : $base_url . '&s=%d') . '">%s</a> ';
	   // Calculate the number of contiguous page links to show
	   $PageContiguous = 2;
	   // Initialize the page index string
	   $pageindex = '';
	   // Helper function to generate page links
	   $generatePageLink = function ($pageNumber) use ($base_link, $num_per_page) {
	      return sprintf($base_link, $pageNumber * $num_per_page, $pageNumber + 1);
	   };
	   // Add the link to the first page if necessary
	   if ($start > $num_per_page * $PageContiguous) $pageindex .= $generatePageLink(0) . ' ';
	   // Add '...' before the first page link if necessary
	   if ($start > $num_per_page * ($PageContiguous + 1)) $pageindex .= '<b> ... </b>';
	   // Add page links before the current page
	   for ($i = $PageContiguous; $i >= 1; $i--) {
	      $pageNumber = $start / $num_per_page - $i;
	      if ($pageNumber >= 0) $pageindex .= $generatePageLink($pageNumber);
	   }
	   // Add the link to the current page
	   $pageindex .= '[<b>' . ($start / $num_per_page + 1) . '</b>] ';
	   // Add page links after the current page
	   for ($i = 1; $i <= $PageContiguous; $i++) {
	      $pageNumber = $start / $num_per_page + $i;
	      if ($pageNumber * $num_per_page < $max_value) $pageindex .= $generatePageLink($pageNumber);
	   }
	   // Add '...' near the end if necessary
	   if ($start + $num_per_page * ($PageContiguous + 1) < $max_value) $pageindex .= '<b> ... </b>';
	   // Add the link to the last page if necessary
	   if ($start + $num_per_page * $PageContiguous < $max_value) {
	      $pageNumber = (int) (($max_value - 1) / $num_per_page);
	      $pageindex .= $generatePageLink($pageNumber);
	   }
	   return $pageindex;
	}

	/**
	 * Sistema de paginación automática [2023]
    * @author Miguel92	 - https://www.phpost.net/foro/perfil/521013-miguel92/
	 * basados completamente en estos mods de ellos
    * @author mdulises 	 - https://www.phpost.net/foro/perfil/2460-mdulises/
    * @author KMario 	 - https://www.phpost.net/foro/perfil/6266-kmario19/
    * @author ReModWrite - https://www.phpost.net/foro/perfil/526172-remodwrite/
	*/
	public function system_pagination(int $totalItems = 0, int $itemsPerPage = 0) {
    	// Obtenemos la pagina actual
   	$currentPage = empty($_GET['page']) ? 1 : (int)$_GET['page'];
   	// Si no existe devolvemos algo vacío
    	if ($totalItems <= 0) return '';
    	$page = "?page=";
    	// Empezamos con la estructura de la paginación
    	$pagination = '<ul class="pagination pagination-sm justify-content-center">';
    	// Calculamos el total de páginas necesarias.
    	$totalPages = ceil($totalItems / $itemsPerPage);
    	// Limitamos el valor de $currentPage para asegurarnos de que no se exceda el rango.
    	$currentPage = max(1, min($currentPage, $totalPages));
    	// Enlace a página anterior.
    	if ($currentPage > 1) {
      	$pagination .= "<li class=\"page-item\"><a class=\"navPages\" href=\"{$this->settings['url']}/$page" . ($currentPage - 1) . "\" title=\"Página anterior\">&laquo;</a></li>";
    	}
    	// Enlaces de primera y última página.
    	if ($currentPage > 3) {
      	$pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->settings['url']}/$page1\">1</a></li>";
        	if ($currentPage > 6) {
            $pagination .= "<li class=\"page-item\"><span class=\"page-link\">...</span></li>";
        	}
    	}
	   // Mostramos los enlaces de la paginación.
	   $startPage = max(1, $currentPage - 2);
	   $endPage = min($totalPages, $currentPage + 2);
	   //
    	for ($i = $startPage; $i <= $endPage; $i++) {
        	$activeClass = ($currentPage === $i) ? ' active' : '';
        	$pagination .= "<li class=\"page-item{$activeClass}\"><a class=\"page-link\" href=\"{$this->settings['url']}/$page{$i}\">{$i}</a></li>";
    	}
    	// Enlaces después del número 6.
    	if ($currentPage < $totalPages - 4) {
        	$pagination .= "<li class=\"page-item\"><span class=\"page-link\">...</span></li>";
        	$pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->settings['url']}/$page{$totalPages}\">{$totalPages}</a></li>";
    	}
    	// Enlace a página siguiente.
    	if ($currentPage < $totalPages) {
      	$pagination .= "<li class=\"page-item\"><a class=\"page-link\" href=\"{$this->settings['url']}/$page" . ($currentPage + 1) . "\" title=\"Página siguiente\">&raquo;</a></li>";
    	}
    	// Finalizamos la paginación
    	$pagination .= '</ul>';
    	return $pagination;
	}

	/**
	 * Realizó una comprobación de versión de PHP ya que magic_quotes_gpc 
	 * es obsoleta desde 7.4.0 y removida de PHP 8
	 * @link https://www.php.net/manual/en/function.get-magic-quotes-gpc.php
	*/
   # Seguridad
	public function setSecure($string, $xss = false) {
	   if(empty($string)) return $string;
	   // Verificar si magic_quotes_gpc estaba activado en la configuración de PHP
	   if (version_compare(PHP_VERSION, '7.4.0') < 0 && get_magic_quotes_gpc()) {
	      $string = stripslashes($string);
	   }
	   // Escapar el valor
	   $string = db_exec('real_escape_string', $string);
   	// Aplicar filtrado XSS si es necesario
   	if ($xss) $string = htmlspecialchars($string, ENT_COMPAT | ENT_QUOTES, 'UTF-8');
   	// Retornamos la información
   	return $string;
	}
	
   /*
      antiFlood()
   */
   public function antiFlood($print = true, $type = 'post', $msg = '') {
      global $tsUser;
      //
      $now = time();
      $msg = empty($msg) ? 'No puedes realizar tantas acciones en tan poco tiempo.' : $msg;
      //
      $limit = $tsUser->permisos['goaf'];
      $resta = $now - $_SESSION['flood'][$type];
      if($resta < $limit) {
         $msg = '0: '.$msg.' Int&eacute;ntalo en '.($limit - $resta).' segundos.';
         // TERMINAR O RETORNAR VALOR
         if($print) die($msg);
         else return $msg;
      } else {
         // ANTIFLOOD
         $_SESSION['flood'][$type] = (empty($_SESSION['flood'][$type])) ? time() : $now;
         // TODO BIEN
         return true;
      }
   }
	
	/*
		setSEO($string, $max) $max : MAXIMA CONVERSION
		: URL AMIGABLES
	*/
	public function setSEO($string, $max = false) {
		// ESPAÑOL
		$string = htmlentities($string, ENT_QUOTES, 'UTF-8');
		$string = preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i', '$1', $string);
		$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
		$string = preg_replace('~[^0-9a-z]+~i', '-', $string);
		$string = trim($string, '-');
		if($max) $string = strtolower($string);
		//
		return $string;
	}

	/*
		parseBBCode($bbcode)
	*/
	public function parseBBCode($bbcode, $type = 'normal') {
      // Class BBCode
      include_once TS_EXTRA . 'bbcode.inc.php';
      $parser = new BBCode();
    
      // Seleccionar texto
      $parser->setText($bbcode);

      // Seleccionar tipo
      switch ($type) {
         // NORMAL
         case 'normal':
         case 'smiles':
            // BBCodes permitidos
            $parser->setRestriction(array('url', 'code', 'quote', 'font', 'size', 'color', 'img', 'b', 'i', 'u', 's', 'align', 'spoiler', 'swf', 'video', 'goear', 'hr', 'sub', 'sup', 'table', 'td', 'tr', 'ul', 'li', 'ol', 'notice', 'info', 'warning', 'error', 'success'));
            // SMILES
            $parser->parseSmiles();
            // MENCIONES
            $parser->parseMentions();
          break;
        	// FIRMA
        	case 'firma':
            // BBCodes permitidos
            $parser->setRestriction(array('url', 'font', 'size', 'color', 'img', 'b', 'i', 'u', 's', 'align', 'spoiler'));
         break;
        	// NOTICIAS
        	case 'news':
            // BBCodes permitidos
            $parser->setRestriction(array('url', 'b', 'i', 'u', 's'));
            // SMILES
            $parser->parseSmiles();
         break;
      }
      // Retornar resultado HTML
      return $parser->getAsHtml();
   }

   /**
    * @name setMenciones
    * @access public
    * @param string
    * @return string
    * @info PONE LOS LINKS A LOS MENCIONADOS
    * @note Esta función se ha reemplazado por $parser->parseMentions(). Se reomienda exclusivamente para compatibilidad en versiones anteriores.
    */
   public function setMenciones($html){
      # GLOBALES
      global $tsUser;
      # HACK
      $html = $html.' ';
      # BUSCAMOS A USUARIOS
      preg_match_all('/\B@([a-zA-Z0-9_-]{4,16}+)\b/',$html, $users);
      $menciones = $users[1];
      # VEMOS CUALES EXISTEN
      foreach($menciones as $key => $user){
         $uid = $tsUser->getUserID($user);
         if(!empty($uid)) {
            $html = str_replace("@$user ", "@<a href=\"{$this->settings['url']}/perfil/$user\">$user</a> ", $html);
         }
      }
      # RETORNAMOS
      return $html;
   }

   /*
      parseSmiles($st)
   */
   public function parseSmiles($bbcode){
      return $this->parseBBCode($bbcode, 'smiles');
   }

	/*
		parseBBCodeFirma($bbcode)
	*/
	public function parseBBCodeFirma($bbcode){
	   return $this->parseBBCode($bbcode, 'firma');
	}

	/*
		setHace()
	*/
	public function setHace(int $fecha = 0, $show = false){
      # Creamos
      $tiempo = time() - $fecha;
      if($fecha <= 0) return "Nunca";
      // Declaración de unidades de tiempo, aunque es un aproximado
      // Ya que existe años bisiestos 366 días
      $unidades = [
        31536000 => ["a&ntilde;o", "a&ntilde;os"],
        2678400 => ["mes", "meses"],
        604800 => ["semana", "semanas"],
        86400 => ["d&iacute;a", "d&iacute;as"],
        3600 => ["hora", "horas"],
        60 => ["minuto", "minutos"],
      ];
      foreach($unidades as $segundos => $nombre){
         $round = round($tiempo / $segundos);
         $s = ($segundos === 2678400) ? 'es' : 's';
         if($tiempo <= 60) $hace = "instantes";
         else {
            if($round > 0) {
               $hace = "{$round} {$nombre[($round > 1 ? 1 : 0)]}";
               break;
            }
         }
      }
      // Si se ha establecido la opción $show, se agrega 'Hace' al resultado
      return ($show ? "Hace " : "") . $hace;
   }

	/*
		getUrlContent($tsUrl) :: Mejorado
	*/
	public function getUrlContent(string $tsUrl): ?string {
    	// USAMOS CURL O FILE
    	if (function_exists('curl_init')) {
        	// Obtener el user agent del cliente
    		//'Mozilla/5.0 (Windows; U; Windows NT 5.1; es-ES; rv:1.9) Gecko/2008052906 Firefox/3.0'
        	$useragent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        	// Abrir conexión  
        	$ch = curl_init();
        	curl_setopt_array($ch, [
            CURLOPT_URL => $tsUrl,
            CURLOPT_USERAGENT => $useragent,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_RETURNTRANSFER => true,
        	]);
        	$result = curl_exec($ch);
        	curl_close($ch);
    	} else $result = @file_get_contents($tsUrl);
    	return $result ?: null;
	}
	/**
	 * Función privada para validar la IP del usuario
	*/
	private function isValidIP(string $ip): bool {
    	return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false;
	}

	/**
	 * Función para obtener la IP del usuario
	*/
	public function getIP(): string {
   	$ip = 'unknown';
   	// List of trusted proxy IP headers
   	$trustedHeaders = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
   	foreach ($trustedHeaders as $header) {
        	if (isset($_SERVER[$header]) && $this->isValidIP($_SERVER[$header])) {
            $ip = $_SERVER[$header];
            break;
        	}
    	}
    	return $this->setSecure($ip);
	}

	/**
	 * Función para validar y obtener la dirección IP del cliente que realiza la petición.
	 *
	 * @return string|null La dirección IP válida del cliente o NULL si no se puede validar.
	*/
	public function validarIP() {
		$_SERVER['REMOTE_ADDR'] = $_SERVER['X_FORWARDED_FOR'] ? $_SERVER['X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
	 * Función para ayudar armar la sentencia en UPDATE
	 * @param array ['name' => 'john', 'password' => '123abc']
	 * @param string 'user_'
	 * @return string|null EJ: user_name = 'john', user_password = '123abc'...
	*/
	public function getIUP(array $array = [], string $prefix = ''): string {
	   $sets = [];
	   foreach ($array as $field => $value) $sets[] = "$prefix$field = " . (is_numeric($value) ? (int)$value : "'{$this->setSecure($value)}'");
	   return implode(', ', $sets);
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

}
<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Funciones globales
 *
 * @name    c.core.php
 * @author  PHPost Team
 */

if(file_exists(TS_EXTRA . 'optimizer.php')) {
	require_once TS_EXTRA . 'optimizer.php';
}

class tsCore {
    
	public $settings;		// CONFIGURACIONES DEL SITIO

	public $extras;

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
		$this->extras = $this->getExtras();
		$this->settings = $this->getSettings();
		$this->settings['seo'] = $this->getSEO();
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
		$this->settings['portada'] = $this->settings['url'].'/files/portadas';
		$this->settings['public'] = $this->settings['url'].'/public';
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
				'scope' => "user"
			],
			'discord' => [
				'authorize_url' => 'https://discord.com/oauth2/authorize',
				'token' => "https://discord.com/api/oauth2/token",
				'user' => "https://discord.com/api/v10/users/@me",
				'scope' => "email identify"
			],
			'gmail' => [
				'authorize_url' => 'https://accounts.google.com/o/oauth2/auth',
				'token' => "https://accounts.google.com/o/oauth2/token",
				'user' => "https://www.googleapis.com/oauth2/v2/userinfo",
				'scope' => "https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile"
			],
			'facebook' => [
				'authorize_url' => 'https://www.facebook.com/v18.0/dialog/oauth',
				'token' => "https://graph.facebook.com/oauth/access_token",
				'user' => "https://graph.facebook.com/v18.0/me?fields=id,name,email,picture,short_name",
				'scope' => "email,public_profile"
			],
			'twitter' => [
				'authorize_url' => 'https://api.twitter.com/oauth/authenticate',
				'token' => "https://api.twitter.com/oauth/access_token",
				'user' => "https://graph.facebook.com/v18.0/me?fields=id,name,email,picture,short_name",
				'scope' => "email,public_profile"
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
				'state' => strtolower($this->settings['titulo']).date('y'),
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
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT * FROM w_configuracion');
		return db_exec('fetch_assoc', $query);
	}
	public function getSEO() {
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT seo_titulo, seo_descripcion FROM w_site_seo');
		return db_exec('fetch_assoc', $query);
	}
	public function getExtras() {
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT optimizar, extension, tamano, calidad, smarty_cache, smarty_security, smarty_compress, smarty_lifetime FROM w_extras');
		return db_exec('fetch_assoc', $query);
	}
	
	public function getNovemods() {
      $datos = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT 
        	(SELECT count(post_id) FROM p_posts WHERE post_status = 3) as revposts, 
        	(SELECT count(cid) FROM p_comentarios WHERE c_status = 1) as revcomentarios, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias WHERE d_type = 1) as repposts, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias WHERE d_type = 2) as repmps, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias WHERE d_type = 3) as repusers, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias  WHERE d_type = 4) as repfotos, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias WHERE d_type = 5) as repcomunidades, 
        	(SELECT count(DISTINCT obj_id) FROM w_denuncias WHERE d_type = 6) as reptemas,  
        	(SELECT count(t_id) FROM c_temas WHERE t_estado = 1) as tempelera, 
        	(SELECT count(susp_id) FROM u_suspension) as suspusers, 
        	(SELECT count(post_id) FROM p_posts WHERE post_status = 2) as pospelera, 
        	(SELECT count(foto_id) FROM f_fotos WHERE f_status = 2) as fospelera')
   	);
		$datos['total'] = $datos['repposts'] + $datos['repfotos'] + $datos['repmps'] + $datos['repusers'] + $datos['revposts'] + $datos['revcomentarios'] + $datos['repcomunidades'] + $datos['reptemas'];
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
				else $this->redirectTo('/');
			}
		}
		// SOLO MIEMBROS
		elseif($tsLevel == 2){
			if($tsUser->is_member == 1) return true;
			else {
				if($msg) $mensaje = 'Para poder ver esta pagina debes iniciar sesi&oacute;n.';
				else $this->redirectTo('/login/?r='.$this->currentUrl());
			}
		}
		// SOLO MODERADORES
		elseif($tsLevel == 3){
			if($tsUser->is_admod || $tsUser->permisos['moacp']) return true;
			else {
				if($msg) $mensaje = 'Estas en un area restringida solo para moderadores.';
				else $this->redirectTo('/login/?r='.$this->currentUrl());
			}
		}
		// SOLO ADMIN
		elseif($tsLevel == 4) {
			if($tsUser->is_admod == 1) return true;
			else {
				if($msg) $mensaje = 'Estas intentando algo no permitido.';
				else $this->redirectTo('/login/?r='.$this->currentUrl());
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
    	if ($totalItems <= 0) return 0;
    	$page = "?page=";
    	$pagination['current'] = $currentPage;
    	// Empezamos con la estructura de la paginación
    	$pagination['item'] = '<ul class="page-numbers">';
    	// Calculamos el total de páginas necesarias.
    	$totalPages = ceil($totalItems / $itemsPerPage);
    	// Limitamos el valor de $currentPage para asegurarnos de que no se exceda el rango.
    	$currentPage = max(1, min($currentPage, $totalPages));
    	// Enlace a página anterior.
    	if ($currentPage > 1) {
      	$pagination['item'] .= "<li><a class=\"prev page-numbers\" href=\"{$this->settings['url']}/$page" . ($currentPage - 1) . "\" title=\"Página anterior\"><iconify-icon icon=\"bx:left-arrow-alt\"></iconify-icon></a></li>";
    	}
    	// Enlaces de primera y última página.
    	if ($currentPage > 3) {
      	$pagination['item'] .= "<li><a class=\"page-numbers\" href=\"{$this->settings['url']}/$page1\">1</a></li>";
        	if ($currentPage > 6) {
            $pagination['item'] .= "<li><span class=\"page-numbers\">...</span></li>";
        	}
    	}
	   // Mostramos los enlaces de la paginación.
	   $startPage = max(1, $currentPage - 2);
	   $endPage = min($totalPages, $currentPage + 2);
	   //
    	for ($i = $startPage; $i <= $endPage; $i++) {
        	if($currentPage === $i) {
				$pagination['item'] .= "<li><span aria-current=\"page\" class=\"page-numbers current\">{$i}</span></li>";
        	} else {
        		$pagination['item'] .= "<li><a class=\"page-numbers\" href=\"{$this->settings['url']}/$page{$i}\">{$i}</a></li>";
        	}
      }
    	// Enlaces después del número 6.
    	if ($currentPage < $totalPages - 4) {
        	$pagination['item'] .= "<li><span class=\"page-numbers\">...</span></li>";
        	$pagination['item'] .= "<li><a class=\"page-numbers\" href=\"{$this->settings['url']}/$page{$totalPages}\">{$totalPages}</a></li>";
    	}
    	// Enlace a página siguiente.
    	if ($currentPage < $totalPages) {
      	$pagination['item'] .= "<li><a class=\"next page-numbers\" href=\"{$this->settings['url']}/$page" . ($currentPage + 1) . "\" title=\"Página siguiente\"><iconify-icon icon=\"bx:right-arrow-alt\"></iconify-icon></a></li>";
    	}
    	// Finalizamos la paginación
    	$pagination['item'] .= '</ul>';
    	return $pagination;
	}

	/**
	 * Realizó una comprobación de versión de PHP ya que magic_quotes_gpc 
	 * es obsoleta desde 7.4.0 y removida de PHP 8
	 * @link https://www.php.net/manual/en/function.get-magic-quotes-gpc.php
	*/
   # Seguridad
	public function setSecure($string, $xss = false) {
    	// Verificar si magic_quotes_gpc está activado
    	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) $string = stripslashes($string);
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
            $parser->setRestriction(array('url', 'code', 'quote', 'font', 'size', 'color', 'img', 'b', 'i', 'u', 's', 'align', 'spoiler', 'swf', 'video', 'goear', 'hr', 'sub', 'sup', 'table', 'td', 'tr', 'ul', 'li', 'ol', 'notice', 'info', 'warning', 'error', 'success', 'html', 'css', 'javascript', 'php', 'sql'));
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
	public function createPassword(string $username = '', string $password = '') {
		if((int)$this->settings['c_upperkey'] === 0) $username = strtolower($username);
		$password = $this->keygen . md5($password);
		return $this->setSecure(md5($password . $username));
	}

	/**
    * Función privada para generar ID
    * @param int $total
    * @return string 
   */
   private function filenamex(int $total = 10) {
      $text = '';
      # GENERAMOS ID PARA LA LICENCIA
      for ($i = 65; $i <= 90; $i++) $text .= chr($i); // De A ... Z
      for ($i = 97; $i <= 122; $i++) $text .= chr($i); // De a ... z
      # Return
      return substr(str_shuffle($text), 0, $total);
   }

   public function cover(string $type = 'post', string $image = '') {
   	# Portada por defecto
   	$urlimage = "sin_portada.png";
   	# Verificamos el campo
      if(!empty($_FILES["portada"]["name"])) {
      	# Obtenemos la extension del archivo
      	$ext = pathinfo($_FILES["portada"]["name"], PATHINFO_EXTENSION);
         # Carpeta a guardar portadas
         $archivo = empty($image) ? self::filenamex(12).'.'.$ext : $image;
			// Crea la cookie
			if (!isset($_COOKIE['PORTADA'])) {
				setcookie('PORTADA', $archivo, time() + 3600, "/");
			}
         $nuevoarchivo = ($type === 'post' ? TS_PORTADAS : TS_UPLOADS) . $archivo;
         // Revisamos si se envía realmente una imagen
         $check = getimagesize($_FILES["portada"]["tmp_name"]);
         if($check === false) {
            return 'El archivo que vas a enviar no es una imagen válida, verifica la imagen del post ' . $check["mime"];
         }
         // Verificar tamaño de la imagen | 1 MB => 1048576 | 2 MB => 2097152
         $mb_one = 2097152;
         if ($_FILES["portada"]["size"] > $mb_one) {
            return 'La imagen debe pesar por mucho 2MB, el tama&ntilde;o de tu archivo es mayor que el permitido.';
         }
        
         if (!isset($_COOKIE['PORTADA'])) {
         	move_uploaded_file($_FILES["portada"]["tmp_name"], $nuevoarchivo);
         } else {
         	$archivo = $_COOKIE['PORTADA'];
         	$nuevoarchivo = ($type === 'post' ? TS_PORTADAS : TS_UPLOADS) . $archivo;
         }
      }

      return ['url' => $nuevoarchivo, 'filename' => $archivo];
   }

	public function colores() {
		$colors = [
			"000000", "000000", "00008B", "0000CD", "0000FF", "001A57", "00209C", "003153", "003C92", "004225", "00438A", "0048BA", "004F79", "00554E", "0061A9", "006400", "006A4E", "0071BC", "007E8B", "007F5C", "007F66", "008000", "008F4C", "0093AF", "009872", "009975", "0099FF", "009B7D", "00B0F6", "00B564", "00CED1", "00DDF3", "00FF00", "00FF7F", "00FF80", "00FFBF", "00FFBF", "00FFEF", "00FFFF", "010101", "019902", "04A404", "091F92", "091F92", "0A3F7A", "0BDA51", "0CF90C", "0D98BA", "0F0F0F", "122562", "128385", "18A88D", "191919", "1B4125", "1B4125", "1B7677", "1C1C1C", "1DACD6", "2000FF", "202020", "213C6E", "222222", "228B22", "25206F", "293133", "2A3223", "2E8B57", "301934", "318CE7", "330066", "333C87", "34C2A7", "35682D", "36454F", "367793", "37312B", "382212", "382983", "3B2A21", "3B3121", "3B7861", "3D2E2C", "3EAEB1", "4000FF", "404040", "40E0D0", "40E0D0", "417DC1", "436EC0", "43B3AE", "44944A", "4682b4", "478800", "4795CE", "483C32", "48D1CC", "496063", "4A2364", "4B382F", "4B5F56", "4C2882", "4E0041", "50301E", "50404D", "51D1F6", "524B3B", "52B830", "536878", "536878", "536895", "543D3F", "555555", "5564EB", "563970", "572364", "573F25", "591F0B", "5C5342", "5C5343", "5D5342", "5F3F3E", "5F7F7A", "6000FF", "602F6B", "604E97", "612682", "63E457", "65315F", "654321", "6576B4", "663B2A", "669F5F", "673147", "674C47", "694C41", "696969", "6A0DAD", "6B8E23", "6B8E23", "6D071A", "6E433C", "6F2DA8", "707D3D", "71BC78", "722F37", "72A0C1", "738678", "7573A6", "75B313", "77DD77", "79443B", "7B3F00", "7C342B", "7C7C40", "7CB9E8", "7D3F32", "7DF9FF", "7E9F2E", "7F00FF", "7F69A5", "7FFFD4", "7FFFD4", "800000", "800080", "800080", "804000", "808080", "808080", "81878B", "81D8D0", "823A3F", "826C34", "848482", "870074", "874639", "87CEFA", "882D17", "882D17", "8892C6", "891E35", "898AC0", "8A5754", "8A9A5B", "8A9A5B", "8B008B", "8B4513", "8D0036", "8E372E", "8E6E37", "8F9779", "900020", "91A3B0", "91A3B0", "9370DB", "93C572", "93C592", "9400D3", "955F20", "964B00", "967117", "96C8A2", "977F73", "987654", "98FF98", "9955BB", "996515", "996B42", "9A6619", "9C9C00", "9CFE37", "9DFFD0", "9EFD38", "9F2B68", "9F68A6", "A0D6B4", "A10684", "A11480", "A11C55", "A2522B", "A7D3F3", "A9A9A9", "AA0000", "AA1C47", "AB2A3E", "AC5CB5", "ACDCDD", "ACDCDD", "ADD8E6", "ADFF2F", "AE2029", "AF6E37", "AFE4DE", "AFEEEE", "B09DB9", "B0B5BC", "B0BF1A", "B21A27", "B21B1C", "B284BE", "B2FFFF", "B5651D", "B5783A", "B57EDC", "B82928", "B9935A", "BA7C45", "BC8648", "BD002F", "BDB76B", "BEBEBE", "C03E3E", "C04000", "C19A6B", "C19A6B", "C20073", "C30B4E", "C3B091", "C41E3A", "C46210", "C48A3C", "C6CE00", "C81023", "C97E28", "C9AE5D", "C9FFE5", "CB1D11", "CB6D51", "CBA2C8", "CC0000", "CC00CC", "CC99FF", "CCCCFF", "CD7F32", "CD853F", "CDCDCD", "CE4676", "CF71AF", "D10047", "D19538", "D1974D", "D1EBF7", "D1EDF2", "D22C21", "D2B48C", "D3C1DD", "D3D3D3", "D4442F", "D4AF37", "D5303E", "D6D6D6", "D7D0B7", "D891EF", "D8BFD8", "D99058", "D99058", "D99343", "D9E542", "D9E542", "DAA520", "DC2339", "DCD0FF", "DCD0FF", "DEB7D9", "DED700", "DFFF00", "DFFF00", "E0B0FF", "E25F23", "E2893A", "E30032", "E30049", "E3A857", "E3E4E5", "E40078", "E49B0F", "E49B0F", "E4D00A", "E4D00A", "E51A4C", "E51D2E", "E52B50", "E5E4E2", "E60026", "E61D52", "E62E00", "E62E11", "E65F00", "E6B57E", "E82300", "E8C39E", "E95400", "E9D66B", "EAC102", "EB6362", "ECCD6A", "ECE2C6", "ED1C24", "ED872D", "EDAA7C", "EDEAE0", "EEDD62", "EEDFA0", "EEEEEE", "EFD52E", "EFDECD", "F0E68C", "F0F8FF", "F19CBB", "F2003C", "F2F0E6", "F38D3C", "F3E5AB", "F50087", "F50087", "F5DEB3", "F5F35B", "F5F5DC", "F5F5F5", "F5FFF0", "F6AE97", "F6F6F6", "F7BFBE", "F8DE7E", "F8F8FF", "F8F8FF", "F984E5", "F98F1D", "F9BDA1", "FA8072", "FA8072", "FAD6A5", "FAE6FA", "FAEBD7", "FAFBFD", "FBF4E2", "FCC300", "FCD0B4", "FCD1C6", "FCF75E", "FD6C9E", "FDF5E6", "FDF5E6", "FDFD96", "FDFDFD", "FED8B1", "FEFF3F", "FF0000", "FF005A", "FF0080", "FF00FF", "FF2000", "FF3202", "FF3399", "FF4000", "FF4500", "FF5A36", "FF6000", "FF6600", "FF77FF", "FF7802", "FF7E00", "FF8000", "FF80FF", "FF8C00", "FF9966", "FF9CFF", "FFBA00", "FFC94D", "FFCB03", "FFCC0F", "FFD700", "FFD700", "FFD90F", "FFDB58", "FFDDF4", "FFDEAD", "FFDF00", "FFE302", "FFE661", "FFE900", "FFF0F5", "FFF5EE", "FFF5EE", "FFF600", "FFF6AD", "FFF83B", "FFF8E7", "FFFAFA", "FFFDD0", "FFFF00", "FFFFE0", "FFFFF0", "FFFFFF"
		];
		return $colors;
	}

}

<?php 

if(!defined('TS_HEADER')) 
	exit('No se permite el acceso directo al script');

/**
 * Clase para el manejo de la extension de c.posts.php
 *
 * @name    c.posts.extends.php
 * @author  Miguel92
*/

trait tsPostsExtends {

	/** 
	 * isAdmod($fix, $add)
	 * @access public
	 * @param string
	 * @param string
	 * @return string
	*/
	private function isAdmod(string $fix = 'u.', string $add = '') {
      $tsCore = new tsCore;
      $tsUser = new tsUser;
      //
      $isAdmod = ($tsUser->is_admod AND (int)$tsCore->settings['c_see_mod'] === 1) ? '' : "AND {$fix}user_activo = 1 AND {$fix}user_baneado = 0 $add";
      //
      return $isAdmod;
	}
	
	/** 
	 * isAdmodPost($fix, $add)
	 * @access public
	 * @param string
	 * @param string
	 * @return string
	*/
	private function isAdmodPost(string $fixu = 'u.', string $fixp = 'p.', string $add = '') {
      $tsCore = new tsCore;
      $tsUser = new tsUser;
      //
      $isAdmodPost = ($tsUser->is_admod && $tsCore->settings['c_see_mod'] == 1) ? " {$fixp}post_id > 0 " : " {$fixu}user_activo = 1 && {$fixu}user_baneado = 0 && {$fixp}post_status = 0";
      //
      return $isAdmodPost;
	}

	/**
	 * newEditPost($data, $type)
	 * @access private
	 * @param array
	 * @param string
	 * @return array
	*/
	private function newEditPost(array $data = [], string $type = 'new'):array {
		$tsCore = new tsCore;
		$data = [
			'title' => $tsCore->parseBadWords($tsCore->setSecure($data['title'], true)),
			'body' => $tsCore->setSecure($data['body']),
			'tags' => $tsCore->parseBadWords($tsCore->setSecure($data['tags'], true)),
			'category' => (int)$data['category']
		];
		if($type === 'new') $data['date'] = time();
		return $data;
	}

	/**
	 * generateURL($dato)
	 * @access private
	 * @param array
	 * @return array
	*/
	private function generateURL(array $dato = []):string {
		$tsCore = new tsCore;
		$seoTitle = $tsCore->setSEO($dato['post_title']);
		return "{$tsCore->settings['url']}/posts/{$dato['c_seo']}/{$dato['post_id']}/$seoTitle.html";
	}

	/**
	 * getPortada($portada, $data)
	 * @access private
	 * @param string
	 * @param array
	 * @return string
	*/
	private function getPortada(string $portada = NULL, string $data = '') {
		$tsCore = new tsCore;
		if(empty($portada)) {
			$portada = "{$tsCore->settings['public']}/images/sin_portada.png";
		# Si activamos la optimización de imagenes
		} elseif((int)$tsCore->extras['optimizar'] === 1 AND file_exists(TS_TOOLS.'tool.optimizer.php') AND extension_loaded('gd')) {
			include_once TS_TOOLS . 'tool.optimizer.php';
			$Optimizer = new Optimizer;
			if(filter_var($portada, FILTER_VALIDATE_URL)) {
				$Optimizer->url_image = $portada;
				$Optimizer->params_image = [
					'w' => 360,
					'h' => 230,
					'q' => (int)$tsCore->extras['calidad'],
					'e' => (int)$tsCore->extras['extension'],
					'd' => $data
				];
				$portada = $Optimizer->start(); 
			}
		}
		return $portada;
	}

	private function removeBBCode(string $texto = '', int $limite = 60):string {
		$patron = '/\[([^\]]*)\]|[\r\n]+/';
		$limitar_texto = substr($texto, 0, $limite);
		return preg_replace($patron, '', $limitar_texto) . '...';
	}

	private function generateDate(int $date = 0) {
		$timestampActual = time();
		$add = (date('Y', $timestampActual) != date('Y', $date)) ? ', Y' : '';
		return $resultado = date('j M' . $add, $date);
	}

	public function getMostPopular() {
		$tsCore = new tsCore;
		$tsUser = new tsUser;
		$limit = 3;
		# SELECCIONAMOS SOLO 3 POSTS
		$isAdmod = self::isAdmod(); //p.post_hits > 1000 AND p.post_comments > 1000 
		$isAdmodPost = self::isAdmodPost();
		$popular = result_array(db_exec([__FILE__, __LINE__], 'query', "SELECT p.post_id, p.post_user, p.post_title, p.post_hits, p.post_date, p.post_comments, p.post_puntos, p.post_private, p.post_sponsored, p.post_status, p.post_sticky, u.user_id, u.user_name, u.user_activo, u.user_baneado FROM p_posts AS p LEFT JOIN u_miembros AS u ON p.post_user = u.user_id $isAdmod WHERE p.post_private = 0 AND p.post_comments >= 20 AND p.post_puntos >= 50 AND $isAdmodPost GROUP BY p.post_id ORDER BY p.post_hits DESC LIMIT $limit"));
		// Generamos una url
		foreach($popular as $i => $dato) {
			$popular[$i]['post_url'] = self::generateURL($dato);
			$popular[$i]['post_fecha'] = self::generateDate($dato['post_date']);
		}
		return $popular;
	}

}
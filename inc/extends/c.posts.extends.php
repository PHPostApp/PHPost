<?php 

if(!defined('TS_HEADER')) 
	exit('No se permite el acceso directo al script');

/**
 * Clase para el manejo de la extension de c.posts.php
 *
 * @name    c.posts.extends.php
 * @author  Miguel92
*/

/**
 * Optimizador de imagenes
*/
if((int)$tsCore->extras['optimizar']) {
	if(file_exists(TS_EXTRA.'optimizer.php') AND extension_loaded('gd')) {
		include_once TS_EXTRA.'optimizer.php';
	}
}

trait tsPostsExtends {

	/** 
	 * isAdmod($fix, $add)
	 * @access public
	 * @param string
	 * @param string
	 * @return string
	*/
	private function isAdmod(string $fix = 'u.', string $add = '') {
      global $tsCore, $tsUser;
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
      global $tsCore, $tsUser;
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
		global $tsCore;
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
		global $tsCore;
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
	private function getPortada(string $portada = NULL, array $data = []):string {
		global $tsCore;
		if(empty($portada)) {
			$portada = "{$tsCore->settings['public']}/images/sin_portada.png";
		} elseif(file_exists(TS_EXTRA.'optimizer.php') AND extension_loaded('gd') AND (int)$tsCore->extras['optimizar'] === 1) {
			$portada = optimizer($portada, [
				'w' => 356,
				'h' => 244,
				'q' => 75,
				't' => 'webp',
				'd' => [
					'id' => $data['post_id'],
					'date' => $data['post_date']
				]
			], $tsCore->settings['url']);
		}
		return $portada;
	}

	private function removeBBCode(string $texto = '', int $limite = 60):string {
		$patron = '/\[([^\]]*)\]|[\r\n]+/';
		$limitar_texto = substr($texto, 0, $limite);
		return preg_replace($patron, '', $limitar_texto) . '...';
	}

}
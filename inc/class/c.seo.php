<?php

if (!defined('TS_HEADER'))
	 exit('No se permite el acceso directo al script');
/**
 * Modelo para la adminitración
 *
 * @name    c.seo.php
 * @author  Miguel92 & PHPost.es
 */
class tsSeo {

	public $seo;

	public function __construct() {
		$this->seo = $this->getSeo();
	}

	# ===================================================
	# SEO
	# * getSEO() :: Obtenemos toda la informacion
	# * getNoticia() :: Obtenemos la noticia por ID
	# * delNoticia() :: Eliminamos la noticia por ID
	# * newNoticia() :: Creamos una nueva notica
	# * editNoticia() :: Editamos la noticia
	# ===================================================
	public function getSeo() {
		$tsCore = new tsCore;
		$sql = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT seo_id, seo_titulo, seo_descripcion, seo_portada, seo_favicon, seo_keywords, seo_images, seo_robots_data, seo_robots, seo_sitemap FROM w_site_seo WHERE seo_id = 1'));
		if($sql == null) return [];
		$robots = json_decode($sql['seo_robots_data'], true);
		$sql['robots_name'] = $robots['name'];
		$sql['robots_content'] = $robots['content'];
		$sql['seo_images'] = empty($sql['seo_images']) ? : json_decode($sql['seo_images'], true);

		return $sql;
	}


	public function saveSEO() {
		global $tsCore, $tsUser;
		//
		foreach($_POST as $key => $val) $_POST[$key] = is_numeric($val) ? (int)$val : (is_array($val) ? json_encode($val, JSON_FORCE_OBJECT) : $tsCore->setSecure($val));
		$set = $tsCore->getIUP($_POST, 'seo_');
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE w_site_seo SET $set WHERE seo_id = 1")) return true;
	}

}
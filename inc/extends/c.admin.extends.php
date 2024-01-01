<?php 

if(!defined('TS_HEADER')) 
	exit('No se permite el acceso directo al script');

/**
 * Clase para el manejo de la extension de c.admin.php
 *
 * @name    c.admin.extends.php
 * @author  Miguel92
*/

trait tsAdminExtends {

	# Las opciones para los rangos (saveRango() y newRango())
	private function optionsRange(array $post = []) {
		return serialize([
			'suad' => $post['superadmin'],
			'sumo' => $post['supermod'],
			'moacp' => $post['mod-accesopanel'],
			'mocdu' => $post['mod-cancelardenunciasusuarios'],
			'moadf' => $post['mod-aceptardenunciasfotos'],
			'mocdf' => $post['mod-cancelardenunciasfotos'],
			'mocdp' => $post['mod-cancelardenunciasposts'],
			'moadm' => $post['mod-aceptardenunciasmensajes'],
			'mocdm' => $post['mod-cancelardenunciasmensajes'],
			'movub' => $post['mod-verusuariosbaneados'],
			'moub' => $post['mod-usarbuscador'],
			'morp' => $post['mod-reciclajeposts'],
			'morf' => $post['mod-reficlajefotos'],
			'mocp' => $post['mod-contenidoposts'],
			'mocc' => $post['mod-contenidocomentarios'],
			'most' => $post['mod-sticky'],
			'moayca' => $post['mod-abrirycerrarajax'],
			'movcud' => $post['mod-vercuentasdesactivadas'],
			'movcus' => $post['mod-vercuentassuspendidas'],
			'mosu' => $post['mod-suspenderusuarios'],
			'modu' => $post['mod-desbanearusuarios'],
			'moep' => $post['mod-eliminarposts'],
			'moedpo' => $post['mod-editarposts'],
			'moop' => $post['mod-ocultarposts'],
			'mocepc' => $post['mod-comentarpostcerrado'],
			'moedcopo' => $post['mod-editarcomposts'],
			'moaydcp' => $post['mod-desyaprobarcomposts'],
			'moecp' => $post['mod-eliminarcomposts'],
			'moef' => $post['mod-eliminarfotos'],
			'moedfo' => $post['mod-editarfotos'],
			'moecf' => $post['mod-eliminarcomfotos'],
			'moepm' => $post['mod-eliminarpubmuro'],
			'moecm' => $post['mod-eliminarcommuro'],
			'godp' => $post['global-darpuntos'],
			'gopp' => $post['global-publicarposts'],
			'gopcp' => $post['global-publicarcomposts'],
			'govpp' => $post['global-votarposipost'],
			'govpn' => $post['global-votarnegapost'],
			'goepc' => $post['global-editarpropioscomentarios'],
			'godpc' => $post['global-eliminarpropioscomentarios'],
			'gopf' => $post['global-publicarfotos'],
			'gopcf' => $post['global-publicarcomfotos'],
			'gorpap' => $post['global-revisarposts'],
			'govwm' => $post['global-vermantenimiento'],
			'goaf' => $post['global-antiflood'],
			'gopfp' => $post['global-pointsforposts'],
			'gopfd' => $post['global-pointsforday']
		]);
	}

	# ===================================================
	# EXTRA
	# * getExtra() :: Obtenemos información extra
	# ===================================================
	public function getExtra() {
		global $tsCore;
		$extra = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT optimizar, extension, tamano, calidad, smarty_security, smarty_compress, smarty_lifetime FROM w_extras WHERE extraid = 1'));
		return $extra;
	}
	public function saveExtra() {
		global $tsCore, $tsUser;
		//
		$_POST = isset($_POST['save']) ? array_slice($_POST, 0, -1) : $_POST;
		foreach($_POST as $key => $val) $_POST[$key] = is_numeric($val) ? (int)$val : $tsCore->setSecure($val);
		$set = $tsCore->getIUP($_POST);
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE w_extras SET $set WHERE extraid = 1")) return true;
	}

	# ===================================================
	# SEO
	# * getSEO() :: Obtenemos toda la informacion
	# * getNoticia() :: Obtenemos la noticia por ID
	# * delNoticia() :: Eliminamos la noticia por ID
	# * newNoticia() :: Creamos una nueva notica
	# * editNoticia() :: Editamos la noticia
	# ===================================================
	public function getSEO() {
		global $tsCore;
		$sql = db_exec('fetch_assoc', db_exec([__FILE__, __LINE__], 'query', 'SELECT seo_id, seo_titulo, seo_descripcion, seo_portada, seo_favicon, seo_keywords, seo_images, seo_robots_data, seo_robots, seo_sitemap FROM w_site_seo WHERE seo_id = 1'));
		$sql['seo_favicon'] = $sql['seo_favicon'];
		$robots = json_decode($sql['seo_robots_data'], true);
		$sql['robots_name'] = $robots['name'];
		$sql['robots_content'] = $robots['content'];
		$sql['seo_images'] = json_decode($sql['seo_images'], true);
		$sql['seo_images_total'] = [16, 32, 64];
		return $sql;
	}
	public function saveSEO() {
		global $tsCore, $tsUser;
		//
		$_POST = isset($_POST['save']) ? array_slice($_POST, 0, -1) : $_POST;
		foreach($_POST as $key => $val) $_POST[$key] = is_numeric($val) ? (int)$val : (is_array($val) ? json_encode($val, JSON_FORCE_OBJECT) : $tsCore->setSecure($val));
		$set = $tsCore->getIUP($_POST, 'seo_');
		if (db_exec([__FILE__, __LINE__], 'query', "UPDATE w_site_seo SET $set WHERE seo_id = 1")) return true;
	}

	# ===================================================
	# TEMAS
	# * privateTheme() :: Evitamos repetir
	# ===================================================
	private function privateTheme(bool $type = false, int $id = 0) {
		$sql = db_exec([__FILE__, __LINE__], 'query', "SELECT tid, t_name, t_url, t_path FROM w_temas WHERE tid " . ($type ? '> 0' : "= $id"));
		return $type ? result_array($sql) : db_exec('fetch_assoc', $sql);
	}
	# ===================================================
	# RANGOS
	# * sameArrayRango() :: Evitamos que se repitan
	# ===================================================
	private function sameArrayRango(array $post = []) {
		global $tsCore;
		$retornar = [
			'name' => $tsCore->setSecure($tsCore->parseBadWords($post['rName'])),
			'color' => $tsCore->setSecure($post['rColor']),
			'image' => $tsCore->setSecure($post['r_img']),
			'cant' => empty($post['global-cantidadrequerida']) ? 0 : (int)$post['global-cantidadrequerida'],
			'type' => $post['global-type'] > 4 ? 0 : $post['global-type'],
			'allows' => self::optionsRange($post)
		];
		if (empty($retornar['name'])) return 'Debes ingresar el nombre del nuevo rango.';
		if ($post['global-pointsforposts'] > $post['global-pointsforday']) return 'El rango no puede dar m&aacute;s puntos de los que tiene al d&iacute;a.';
		return $retornar;
	}
	

}
<?php 

/**
 * Smarty plugin para incluir archivos CSS y JS de forma independiente.
 *
 * Uso: Solo require el nombre del archivo
 *  {meta facebook=true twitter=false} 
 *
 * @param array $params Parámetros pasados a la función (en este caso, 'facebook|twitter').
 * @param Smarty_Internal_Template $smarty Instancia del objeto Smarty.
 * @return string Código HTML generado por la función.
*/

include_once TS_CLASS . "c.admin.php";

function smarty_function_meta($params, &$smarty) {
	global $tsCore, $tsPost, $tsFoto;
	// 
	$protocolo = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
	$dominio = $_SERVER['HTTP_HOST'];
	$ruta = $_SERVER['REQUEST_URI'];

	$url = $protocolo . "://" . $dominio . $ruta;

	$tsAdmin = new tsAdmin;
	$data = $tsAdmin->getSEO();

	// Titulo
	$title = (is_numeric($tsPost['post_id'])) ? $tsPost['post_title'] : ($tsFoto['foto_id'] ? $tsFoto['f_title'] : $data['seo_titulo']);

	// Descripcion
	$description = (is_numeric($tsPost['post_id'])) ? $tsPost['post_body_descripcion'] : ($tsFoto['foto_id'] ? $tsFoto['foto_descripcion'] : $data['seo_descripcion']);

	// Etiquetas
	$keywords = (is_numeric($tsPost['post_id'])) ? join(',', $tsPost['post_tags']) : $data['seo_keywords'];
	$keywords = strtolower($keywords);

	// Portada
	if(isset($tsPost['post_portada']) AND empty($tsPost['post_portada'])) {
		$tsPost['post_portada'] = "{$tsCore->settings['public']}/images/sin_portada.png";
	} 
	$image = (is_numeric($tsPost['post_id'])) ? $tsPost['post_portada'] : ($tsFoto['foto_id'] ? $tsFoto['foto_url'] : $data['seo_favicon']);

	// Tipo
	$type = is_numeric($tsPost['post_id']) ? 'article' : 'website';

	$nameRobots = [0 => 'robots', 1 => 'googlebot', 2 => 'googlebot-news'];
	$contentRobots = [0 => 'index', 1 => 'follow', 2 => 'noindex', 3 => 'nofollow', 4 => 'nosnippet', 5 => 'index, follow', 6 => 'index, nofollow', 7 => 'noindex, follow', 8 => 'noindex, nofollow'];

	$meta = "<!-- Meta Tags Generado por {$tsCore->settings['url']} -->\n";
	$tags = ['title', 'description', 'keywords'];
	// Etiquetas por defecto
	foreach ($tags as $tag) $meta .= "<meta name=\"$tag\" content=\"".$$tag."\" />\n";

	$meta .= "<meta name=\"application-name\" content=\"{$data['seo_titulo']}\">\n";
	$meta .= "<meta name=\"msapplication-TileImage\" content=\"{$data['seo_favicon']}\">\n";
	$meta .= "<meta name=\"msapplication-TileColor\" content=\"#2A2A2A\">\n";
	$meta .= "<meta name=\"theme-color\" media=\"(prefers-color-scheme: light)\" content=\"#5599DE\">\n";
	$meta .= "<meta name=\"theme-color\" media=\"(prefers-color-scheme: dark)\"  content=\"#343232\">\n";

	if((int)$data['seo_robots']) {
		$robots_data = json_decode($data['seo_robots_data'], true);
		$meta .= "<meta name=\"{$nameRobots[$robots_data['name']]}\" content=\"{$contentRobots[$robots_data['content']]}\" />\n";
	}
	
	$meta .= "<meta rel=\"manifest\" href=\"/manifest.json\" />\n";

	if((int)$data['seo_sitemap']) {
		$meta .= "<link rel=\"sitemap\" type=\"application/xml\" title=\"Mapa del sitio\" href=\"{$tsCore->settings['url']}/sitemap.xml\">\n";
	}
	$card = 'summary_large_image';
	$redes = [
		'facebook' => [
			'attr' => 'name',
			'prop' => 'og',
			'data' => ['type', 'url', 'title', 'description', 'image'],
		],
		'twitter' => [
			'attr' => 'property',
			'prop' => 'twitter',
			'data' => ['card', 'url', 'title', 'description', 'image'],
		]
	];
	foreach ($redes as $i => $social) {
		$meta .= "<!-- ".ucfirst($i)." -->\n";
		foreach ($social['data'] as $d => $info) {
			$meta .= "<meta {$social['attr']}=\"{$social['prop']}:$info\" content=\"".$$info."\" />\n";
		}
	}
	
	$type = pathinfo($data['seo_favicon'])['extension'];
	$meta .= "<link href=\"{$data['seo_favicon']}\" rel=\"shortcut icon\" type=\"image/$type\" />\n";
	foreach($data['seo_images'] as $im => $img) {
		$meta .= "<link href=\"$img\" rel=\"shortcut icon\" type=\"image/$type\" sizes=\"{$im}x{$im}\" />\n";
	}
	// Retornamos
	return trim($meta);
}
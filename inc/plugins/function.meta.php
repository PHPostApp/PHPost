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

	$url_completa = $protocolo . "://" . $dominio . $ruta;

	$tsAdmin = new tsAdmin;
	$data = $tsAdmin->getSEO();

	$title = (is_numeric($tsPost['post_id'])) ? $tsPost['post_title'] : ($tsFoto['foto_id'] ? $tsFoto['f_title'] : $data['seo_titulo']);
	$type = is_numeric($tsPost['post_id']) ? 'article' : 'website';

	$meta = "<!-- Meta Tags Generado por {$tsCore->settings['url']} -->\n";
	// Etiquetas por defecto
	$meta .= "<meta name=\"title\" content=\"$title\" />\n";
	$meta .= "<meta name=\"description\" content=\"{$data['seo_descripcion']}\" />\n";
	$meta .= "<meta name=\"theme-color\" media=\"(prefers-color-scheme: light)\" content=\"#5599DE\">\n";
	$meta .= "<meta name=\"theme-color\" media=\"(prefers-color-scheme: dark)\"  content=\"#343232\">\n";
	$meta .= "<meta rel=\"manifest\" href=\"./manifest.json\" />\n";
	$meta .= "<link rel=\"sitemap\" type=\"application/xml\" title=\"Mapa del sitio\" href=\"{$tsCore->settings['url']}/sitemap.xml\">\n";
	// FACEBOOK
	if(isset($params['facebook']) AND $params['facebook'] === true) {
		$meta .= "<!-- Open Graph / Facebook -->\n";
		$meta .= "<meta name=\"og:type\" content=\"$type\" />\n";
		$meta .= "<meta name=\"og:url\" content=\"$url_completa\" />\n";
		$meta .= "<meta name=\"og:title\" content=\"$title\" />\n";
		$meta .= "<meta name=\"og:description\" content=\"{$data['seo_descripcion']}\" />\n";
		$meta .= "<meta name=\"og:image\" content=\"{$tsCore->settings['url']}/{$data['seo_favicon']}\" />\n";
	}
	// TWITTER
	if(isset($params['twitter']) AND $params['twitter'] === true) {
		$meta .= "<!-- Twitter -->\n";
		$meta .= "<meta property=\"twitter:card\" content=\"summary_large_image\" />\n";
		$meta .= "<meta property=\"twitter:url\" content=\"$url_completa\" />\n";
		$meta .= "<meta property=\"twitter:title\" content=\"$title\" />\n";
		$meta .= "<meta property=\"twitter:description\" content=\"{$data['seo_descripcion']}\" />\n";
		$meta .= "<meta property=\"twitter:image\" content=\"{$tsCore->settings['url']}/{$data['seo_favicon']}\" />\n";
	}
	// Retornamos
	return trim($meta);
}
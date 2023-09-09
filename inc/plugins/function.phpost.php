<?php

/**
 * Smarty plugin para incluir archivos CSS y JS de forma independiente.
 *
 * Uso: Solo require el nombre del archivo
 *  1 - {phpost css=["archivo.css"]} 
 *  2 - {phpost js=["archivo.js"]} 
 *  3 - {phpost favicon="archivo.ico"} 
 *  4 - {phpost global=['key1' => 'val1', 'key2' => 'val2']} 
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

require_once realpath(__DIR__) . DIRECTORY_SEPARATOR . "functionsOfPHPost.php";

function smarty_function_phpost($params, &$smarty) {
	global $tsCore, $tsPage;
	//
	$HTML = '';
	$funcs = new fnPHPost;

	// Obtenemos el icono o generamos uno automaticamente
	$HTML .= $funcs->getFavicon((isset($params['favicon']) ? $params['favicon'] : 'not'));

	// Añadimos todos los estilos
	$HTML .= "<!-- Estilos de {$tsCore->settings['tema']['t_name']} -->\n";
	foreach($params['css'] as $css) $HTML .= $funcs->getStyle($css);
	// Para las notificaciones de usuario
	if($funcs->getLive()) $HTML .= $funcs->getStyle('live.css');

	// Añadimos todos los estilos
	$HTML .= "<!-- Scripts de {$tsCore->settings['tema']['t_name']} -->\n";
	if($tsPage === 'posts') array_push($params['js'], 'highlight.min.js');
	
	foreach($params['js'] as $js) $HTML .= $funcs->getScript($js, $params['deny']);

	// Si es administrador, moderador o tiene permisos
	if($funcs->getPerms()) $HTML .= $funcs->getScript('moderacion.js');

	// Para las notificaciones de usuario
	if($funcs->getLive()) $HTML .= $funcs->getScript('live.js');

	$HTML .= $funcs->getGlobalData();

	return $HTML;
}
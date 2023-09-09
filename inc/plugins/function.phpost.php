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

	// Añadimos este isset ya que si no exite $params['favicon'] generará un error
	if(isset($params['favicon'])) {
		$HTML .= "<!-- Añadidos col el plugin: {phpost favicon=\"...\"} -->\n";
		// Obtenemos el icono o generamos uno automaticamente
		$HTML .= $funcs->getFavicon((isset($params['favicon']) ? $params['favicon'] : 'not'));
	}

	// Añadimos este isset ya que si no exite $params['css'] generará un error
	if(isset($params['css'])) {
		if(is_array($params['css'])) {
			// Añadimos todos los estilos
			$HTML .= "<!-- Añadidos col el plugin: {phpost css=[\"...\"]} -->\n";
			// Para las notificaciones de usuario
			if($funcs->getLive()) array_push($params['css'], 'live.css');
			//
			foreach($params['css'] as $css) $HTML .= $funcs->getStyle($css);
		} else {
			$HTML .= "<!-- Añadidos col el plugin: {phpost css=\"...\"] -->\n";
			$HTML .= $funcs->getStyle($params['css']);
		}
	}

	// Añadimos este isset ya que si no exite $params['js'] generará un error
	if(isset($params['js']) OR isset($params['deny'])) {
		// Ahora usamos 'deny' para evitar que agregue 2 veces el mismo archivo
		if(is_array($params['js'])) {
			// Añadimos todos los scripts
			$HTML .= "<!-- Añadidos col el plugin: {phpost js=[\"...\"]} -->\n";
			
			// Básicamente siempre serán necesarios
			array_unshift($params['js'], "jquery.min.js", "jquery.plugins.js");
			
			// Ahora se añaden en páginas especificas
			if($tsPage === 'posts') array_push($params['js'], 'highlight.min.js');
			if($tsPage === 'admin') array_push($params['js'], 'timeago.min.js', 'timeago.es.js');

			// Si es administrador, moderador o tiene permisos
			if($funcs->getPerms()) array_push($params['js'], 'moderacion.js');

			// Para las notificaciones de usuario
			if($funcs->getLive()) array_push($params['js'], 'live.js');
			//
			foreach($params['js'] as $js) $HTML .= $funcs->getScript($js, $params['deny']);
			// Variable global
			$HTML .= "<!-- Añadidos col el plugin: {phpost *sin parametros*} -->\n";
			$HTML .= $funcs->getGlobalData();
		} else {
			$HTML .= "<!-- Añadidos col el plugin: {phpost js=[\"...\"]} -->\n";
			$HTML .= $funcs->getScript($params['js']);
		}
	}
	return trim($HTML);
}
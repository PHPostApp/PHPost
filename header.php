<?php
/**
 * Archivo de Inicialización del Sistema
 *
 * Carga las clases base y ejecuta la solicitud.
 *
 * @name    header.php
 * @author  PHPost Team
 */

/*
 * -------------------------------------------------------------------
 *  Estableciendo variables importantes
 * -------------------------------------------------------------------
 */

	if( !defined('TS_HEADER') ) define('TS_HEADER', TRUE);

	// Sesión
	if(!isset($_SESSION)) session_start();

	// Reporte de errores
	error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE ^ E_DEPRECATED);
	ini_set('display_errors', TRUE);

	// Límite de ejecución
	set_time_limit(300);

/*
 * -------------------------------------------------------------------
 *  Definiendo constantes
 * -------------------------------------------------------------------
 */
	//DEFINICION DE CONSTANTES
	define('TS_PATH', DIRECTORY_SEPARATOR);

	define('TS_ROOT', realpath(__DIR__) . TS_PATH);

	define('TS_CACHE', TS_ROOT . 'cache' . TS_PATH);

	define('TS_INCLUDES', TS_ROOT . 'inc' . TS_PATH);

	define('TS_FILES', TS_ROOT . 'files' . TS_PATH);

	define('TS_THEMES', TS_ROOT . 'themes' . TS_PATH);

	define('TS_CLASS', TS_INCLUDES . 'class' . TS_PATH);

	define('TS_EXTRA', TS_INCLUDES . 'ext' . TS_PATH);

	define('TS_PLUGINS', TS_INCLUDES . 'plugins' . TS_PATH);

	define('TS_SMARTY', TS_INCLUDES . 'smarty' . TS_PATH);

	define('TS_AVATAR', TS_FILES . 'avatar' . TS_PATH);

	define('TS_UPLOADS', TS_FILES . 'uploads' . TS_PATH);

	define('TS_PUBLIC', TS_ROOT . 'public' . TS_PATH);
	
	set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));

	// PARA LA CONFIGURACION DE SMARTY
	define('CACHE_CHECKED', TRUE);
	define('SECURITY', TRUE);
	define('COMPRESS_HTML', FALSE);
	define('CACHE_LIFE_TIME', 3600 * 5);

/*
 * -------------------------------------------------------------------
 *  Agregamos los archivos globales
 * -------------------------------------------------------------------
 */
	
	// Funciones
	include TS_EXTRA.'functions.php';

	// Nucleo
	include TS_CLASS.'c.core.php';
	
	// Controlador de usuarios
	include TS_CLASS.'c.user.php';

	// Monitor de usuario
	include TS_CLASS.'c.monitor.php';
	
	// Actividad de usuario
	include TS_CLASS.'c.actividad.php';

	// Mensajes de usuario
	include TS_CLASS.'c.mensajes.php';
	
	// Crean requests
	include TS_EXTRA.'QueryString.php';

/*
 * -------------------------------------------------------------------
 *  Inicializamos los objetos principales
 * -------------------------------------------------------------------
 */
 
	// Limpiar variables...
	$cleanRequest->limpiar();

	// Cargamos el nucleo
	$tsCore = new tsCore();
	
	// Usuario
	$tsUser = new tsUser();

	// Monitor
	$tsMonitor = new tsMonitor();

	// Actividad
	$tsActividad = new tsActividad();

	// Mensajes
	$tsMP = new tsMensajes();

	// Smarty
	require_once TS_EXTRA . 'smarty.php';
	
/*
 * -------------------------------------------------------------------
 *  Asignación de variables
 * -------------------------------------------------------------------
 */
	 
	 // Configuraciones
	 $smarty->assign('tsConfig', $tsCore->settings);

	 // Obtejo usuario
	 $smarty->assign('tsUser',$tsUser);
	 
	 // Avisos
	 $smarty->assign('tsAvisos', $tsMonitor->avisos);
	 
	 // Nofiticaciones
	 $smarty->assign('tsNots', $tsMonitor->notificaciones);
	 
	 // Mensajes
	 $smarty->assign('tsMPs',$tsMP->mensajes);

if (!extension_loaded('gd') && !function_exists('gd_info')) {
	$smarty->assign('gd_info', 'La extensión GD no está habilitada en tu servidor.');
}	 

/*
 * -------------------------------------------------------------------
 *  Validaciones extra
 * -------------------------------------------------------------------
 */
	 // Baneo por IP
	 $ip = $_SERVER['X_FORWARDED_FOR'] ? $_SERVER['X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
	 if(!filter_var($ip, FILTER_VALIDATE_IP)) die('Su ip no se pudo validar.'); 
	 if(db_exec('num_rows', db_exec(array(__FILE__, __LINE__), 'query', 'SELECT id FROM w_blacklist WHERE type = \'1\' && value = \''.$ip.'\' LIMIT 1'))) die('Bloqueado');

	 // Online/Offline
	 if($tsCore->settings['offline'] == 1 && ($tsUser->is_admod != 1 && $tsUser->permisos['govwm'] == false) && $_GET['action'] != 'login-user'){
		$smarty->assign('tsTitle',$tsCore->settings['titulo'].' -  '.$tsCore->settings['slogan']);
		  if(empty($_GET['action'])) 
			$smarty->display('sections/mantenimiento.tpl');
		  else die('Espera un poco...');
		exit();
	 // Banned
	 } elseif($tsUser->is_banned) {
		  $banned_data = $tsUser->getUserBanned();
		  if(!empty($banned_data)){
				// SI NO ES POR AJAX
				if(empty($_GET['action'])){
					 $smarty->assign('tsBanned',$banned_data);
					 $smarty->display('sections/suspension.tpl');
				} 
				else die('<div class="emptyError">Usuario suspendido</div>');
				//
				exit;
		  }
	 }
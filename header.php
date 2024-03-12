<?php
/**
 * Archivo de Inicialización del Sistema
 *
 * Carga las clases base y ejecuta la solicitud.
 *
 * @name    header.php
 * @author  PHPost Team
 */

if( !defined('TS_HEADER') ) define('TS_HEADER', TRUE);

// Sesión
if(!isset($_SESSION)) session_start();

// Reporte de errores
error_reporting(E_ALL ^ E_WARNING ^ E_NOTICE);
ini_set('display_errors', TRUE);

// Límite de ejecución
set_time_limit(300);

require_once realpath(__DIR__) . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR . 'definitions.php';


/*
 * -------------------------------------------------------------------
 *  Agregamos los archivos globales
 * -------------------------------------------------------------------
*/
	
// Funciones
include TS_EXTRA.'functions.php';

// Nucleo
include TS_CLASS . 'c.core.php';

// Controlador de usuarios
include TS_CLASS . 'c.user.php';

// Monitor de usuario
include TS_CLASS . 'c.monitor.php';

// Actividad de usuario
include TS_CLASS . 'c.actividad.php';

// Mensajes de usuario
include TS_CLASS . 'c.mensajes.php';

// Smarty
include TS_CLASS . 'c.smarty.php';

// Crean requests
include TS_EXTRA . 'QueryString.php';


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

// Definimos el template a utilizar
$tsTema = $tsCore->settings['tema']['t_path'];
if(empty($tsTema)) $tsTema = 'default';
define('TS_TEMA', $tsTema);

// Smarty
$smarty = new tsSmarty();
// Nueva configuración
$smarty->output(false);

	
/*
 * -------------------------------------------------------------------
 *  Asignación de variables
 * -------------------------------------------------------------------
*/

// Configuraciones
$smarty->assign('tsConfig', $tsCore->settings);

$smarty->assign('tsSeoData', $tsCore->getSettings('seo'));

$smarty->assign('tsExtras', $tsCore->getSettings('extras'));

// Obtejo usuario
$smarty->assign('tsUser', $tsUser);

// Avisos
$smarty->assign('tsAvisos', $tsMonitor->avisos);

// Nofiticaciones
$smarty->assign('tsNots', $tsMonitor->notificaciones);

// Mensajes
$smarty->assign('tsMPs', $tsMP->mensajes);

if (!extension_loaded('gd') || !function_exists('gd_info')) {
	echo '<span style="position:fixed;margin:1rem;z-index:999;background:#F00;color:#FFF;padding:.875rem 1.875rem;border-radius: .3rem;">La extensi&oacute;n GD no est&aacute; habilitada en tu servidor.</span>';
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
if($tsCore->settings['offline'] == 1 && ($tsUser->is_admod != 1 && $tsUser->permisos['govwm'] == false) && $_GET['action'] != 'login-user') {
	$smarty->assign('tsTitle', "{$tsCore->settings['titulo']} - {$tsCore->settings['slogan']}");
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
		 	$smarty->assign('tsBanned', $banned_data);
			$smarty->display('sections/suspension.tpl');
		} else die('<div class="emptyError">Usuario suspendido</div>');
		//
		exit;
	}
}

if(file_exists(TS_ROOT . 'upgrade-version.php')) {
	require_once TS_ROOT . 'upgrade-version.php';
}
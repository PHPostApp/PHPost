<?php 

if(!defined('TS_HEADER')) define('TS_HEADER', true);

if ( ! defined('TS_HEADER')) exit('No direct script access allowed');

/*
 * -------------------------------------------------------------------
 *  Definiendo constantes
 * -------------------------------------------------------------------
*/

define('TS_PATH', DIRECTORY_SEPARATOR);

define('TS_ROOT', 		realpath(dirname(__DIR__)) . TS_PATH);

define('TS_FILES', 		TS_ROOT . 'files' . TS_PATH);
define('TS_AVATAR', 		TS_FILES . 'avatar' . TS_PATH);
define('TS_UPLOADS', 	TS_FILES . 'uploads' . TS_PATH);
define('TS_PORTADAS', 	TS_FILES . 'portadas' . TS_PATH);

# EN CARPETA 'PRIVATE'
define('TS_PRIVATE', 	TS_ROOT . 'private' . TS_PATH);
define('TS_CACHE', 		TS_PRIVATE . 'cache' . TS_PATH);
define('TS_CALLBACK', 	TS_PRIVATE . 'callback' . TS_PATH);
define('TS_INCLUDES', 	TS_PRIVATE . 'inc' . TS_PATH);
define('TS_JSDELIVR', 	TS_PRIVATE . 'jsdelivr' . TS_PATH);
define('TS_PLUGINS', 	TS_PRIVATE . 'plugins' . TS_PATH);
define('TS_TOOLS', 		TS_PRIVATE . 'tools' . TS_PATH);

define('TS_CLASS',		TS_INCLUDES . 'class' . TS_PATH);
define('TS_EXTRA', 		TS_INCLUDES . 'ext' . TS_PATH);
define('TS_PHP',			TS_INCLUDES . 'php' . TS_PATH);
define('TS_SMARTY', 		TS_INCLUDES . 'smarty' . TS_PATH);

# EN CARPETA 'PUBLIC'
define('TS_PUBLIC', 		TS_ROOT . 'public' . TS_PATH);
define('TS_ASSETS', 		TS_PUBLIC . 'assets' . TS_PATH);
define('TS_THEMES', 		TS_PUBLIC . 'themes' . TS_PATH);
define('TS_VIEWS', 		TS_PUBLIC . 'views' . TS_PATH);
define('TS_DASHBOARD', 	TS_PUBLIC . 'dashboard' . TS_PATH);
define('TS_ADMOD', 		TS_DASHBOARD . "admin_mods" . TS_PATH);

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));
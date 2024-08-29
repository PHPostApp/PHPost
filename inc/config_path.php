<?php 

//DEFINICION DE CONSTANTES
define('TS_PATH', DIRECTORY_SEPARATOR);

// ../PHPost/
define('TS_ROOT', realpath(dirname(__DIR__)) . TS_PATH);

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

define('TS_ASSETS', TS_ROOT . 'assets' . TS_PATH);

define('TS_DASHBOARD', TS_ROOT . 'dashboard' . TS_PATH);

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));

// ARCHIVOS...
define('DATABASE', TS_EXTRA . 'database.php');

define('TS_CONFIG', TS_ROOT . 'config.inc.php');

define('TS_EXAMPLE', TS_EXTRA . 'example.config.php');

define('TS_LOCK', TS_ROOT . '.lock');

define('LICENSE', file_get_contents(TS_ROOT . 'LICENSE'));

define('SCRIPT_KEY', 'WkNvZGVVcGdyYWRl');
define('SCRIPT_NAME', 'PHPost');
define('SCRIPT_VERSION', '1.3.0-24');
define('SCRIPT_NAME_VERSION', SCRIPT_NAME . ' ' . SCRIPT_VERSION);
$codeversion = strtolower(str_replace([' ', '.', '-'], '_', SCRIPT_NAME_VERSION));
define('SCRIPT_VERSION_CODE', $codeversion);

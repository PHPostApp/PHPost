<?php 

//DEFINICION DE CONSTANTES
define('TS_PATH', DIRECTORY_SEPARATOR);

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

define('TS_PUBLIC', TS_ROOT . 'public' . TS_PATH);

set_include_path(get_include_path() . PATH_SEPARATOR . realpath('./'));
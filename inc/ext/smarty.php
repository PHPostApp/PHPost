<?php 

// Definimos el template a utilizar
$tsTema = $tsCore->settings['tema']['t_path'];
if(empty($tsTema)) $tsTema = 'default';
define('TS_TEMA', $tsTema);

/**
 * Desde este punto vamos a configurar SMARTY
*/
require_once TS_SMARTY . "bootstrap.php";
$smarty = new Smarty();

// DIRECTORIOS PRINCIPALES E IMPORTANTES
define('TS_TEMA_ACT', TS_THEMES . TS_TEMA . TS_PATH);
define('TS_TEMPLATES', TS_TEMA_ACT . "templates" . TS_PATH);
define('TS_SECTIONS', TS_TEMPLATES . "sections" . TS_PATH);
define('TS_MODULES', TS_TEMPLATES . "modules" . TS_PATH);
define('TS_ADMOD', TS_TEMPLATES . "admin_mods" . TS_PATH);

//
$smarty->setCompileCheck(CACHE_CHECKED);

/**
 * Compilamos los archivos en la carpeta cache
 * @link => https://www.smarty.net/docs/en/api.set.compile.dir.tpl 
*/
$smarty->setCompileDir(TS_CACHE . TS_TEMA);

/**
 * Creamos key para asignarle el valor del directorio,
 * ya que estas se usarán en los plugins,
 * ex: "key_name" => valor_carpeta
 * ======================================
 * ATENCIÓN: no deben cambiar el "key_name"
*/
$addDir['themes'] 	= TS_THEMES;
$addDir['tema'] 		= TS_TEMA_ACT;
$addDir['css'] 		= TS_TEMA_ACT . "css";
$addDir['js'] 			= TS_TEMA_ACT . "js";
$addDir['images'] 	= TS_TEMA_ACT . "images";
$addDir['templates'] = TS_TEMPLATES;
$addDir['sections'] 	= TS_SECTIONS;
$addDir['modules'] 	= TS_MODULES;
$addDir['admod'] 		= TS_ADMOD;
$addDir['plugins'] 	= TS_PLUGINS;
//
$smarty->setTemplateDir($addDir);

/**
 * Indicamos la ruta de los plugins para adicionar al sitio,
 * debemos hacer esto para que cuente como parte de smarty
 * @link => https://www.smarty.net/docs/en/api.add.plugins.dir.tpl
*/
$smarty->addPluginsDir(TS_PLUGINS);

// SEGURIDAD
$SECURITY_POLICY = new Smarty_Security($smarty);
$SECURITY_POLICY->$php_handling = $smart->PHP_REMOVE;
$SECURITY_POLICY->$allow_php_tag = true;
$SECURITY_POLICY->$modifiers = [];
$SECURITY_POLICY->$php_functions = [];

/**
 * Con esta función habilitamos el acceso a los directorios agregados
 * en la función de $smarty->setTemplateDir(...) si no estan definidos
 * no podran obtener el contenido de las mismas
 * @link => https://www.smarty.net/docs/en/advanced.features.tpl#advanced.features.security 
*/
if( SECURITY ) $smarty->enableSecurity( $SECURITY_POLICY );

/**
 * Eliminará: Comentarios, Espacios.
 * Basicamente comprimirá todo el html
 * @link => https://www.smarty.net/docs/en/api.load.filter.tpl
 * @link => https://stackoverflow.com/questions/18673684/minify-html-outputs-in-smarty/28556561
*/
if( COMPRESS_HTML ) $smarty->loadFilter('output', 'trimwhitespace');

$smarty->muteUndefinedOrNullWarnings();
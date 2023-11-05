<?php 

if (!defined('TS_HEADER')) exit('No se permite el acceso directo al script');

/**
 * El footer permite mostrar la plantilla
 *
 * @name    footer.php
 * @author  PHPost Team
 */

/*
 * -------------------------------------------------------------------
 *  Realizamos tareas para mostrar la plantilla
 * -------------------------------------------------------------------
*/

// P�gina solicitada
$smarty->assign("tsPage", $tsPage);

// A�adimos modules/{_pagina_}/
$smarty->addTemplateDir(["TS_PAGES" => TS_MODULES . $tsPage . TS_PATH]);

$mytemplate = "t.$tsPage.tpl";
$myerr = "t.error.tpl";

// Comprobamos que exista la plantilla
$template = $smarty->templateExists( $mytemplate ) ? $mytemplate : $myerr;

// Cacheamos la plantilla [5hs]
$smarty->setCacheLifetime( (int)$tsCore->extras['smarty_lifetime'] * 3600 );

/**
 * Borra la versi�n compilada del recurso de plantilla especificado
 * @link https://www.smarty.net/docs/en/api.clear.compiled.tpl.tpl
*/
$smarty->clearCompiledTemplate( $template );

/**
 * Limpiamos todo el cache
 * @link https://www.smarty.net/docs/en/api.clear.all.cache.tpl
*/
$smarty->clearAllCache();

/**
 * Cargamos todo el contenido de las plantillas en HTML
 * @link https://www.smarty.net/docs/en/api.display.tpl
*/
$smarty->display( $template );
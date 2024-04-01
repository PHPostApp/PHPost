<?php 
/**
 * Controlador
 *
 * @name    buscador.php
 * @author  PHPost Team
*/
/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "buscador";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 0;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

	$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?
	
	$tsContinue = true;	// CONTINUAR EL SCRIPT
	
/*++++++++ = ++++++++*/

	include realpath('../../') . DIRECTORY_SEPARATOR . "header.php";  // INCLUIR EL HEADER

	$tsTitle = $tsCore->settings['titulo'].' - '.$tsCore->settings['slogan']; 	// TITULO DE LA PAGINA ACTUAL

/*++++++++ = ++++++++*/
	
	// VERIFICAMOS EL NIVEL DE ACCSESO ANTES CONFIGURADO
	$tsLevelMsg = $tsCore->setLevel($tsLevel, true);
	if($tsLevelMsg != 1){	
		$tsPage = 'aviso';
		$tsAjax = 0;
		$smarty->assign("tsAviso",$tsLevelMsg);
		//
		$tsContinue = false;
	}
	//
	if($tsContinue){
/**********************************\

* (VARIABLES LOCALES ESTE ARCHIVO)	*

\*********************************/

	$q = isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '';
   $e = isset($_GET['e']) ? htmlspecialchars($_GET['e']) : '';
   $c = (int)$_GET['cat'];
   $a = isset($_GET['autor']) ? htmlspecialchars($_GET['autor']) : '';
	//
	include("../class/c.posts.php");
	$tsPosts = new tsPosts();

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/

	if((!empty($q) || !empty($a)) && $e != 'google'){
	   $smarty->assign("tsResults",$tsPosts->getQuery());
	}
	//
    $smarty->assign("tsQuery",$q);
    $smarty->assign("tsEngine",$e);
    $smarty->assign("tsCategory",$c);
    $smarty->assign("tsAutor",$a);
/**********************************\

* (AGREGAR DATOS GENERADOS | SMARTY) *

\*********************************/
	}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL

	/*++++++++ = ++++++++*/
	include("../../footer.php");
	/*++++++++ = ++++++++*/
}
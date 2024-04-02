<?php 
/**
 * Controlador
 *
 * @name    cuenta.php
 * @author  PHPost Team
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "cuenta";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 2;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

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

	$action = $_GET['action'];
	//
	include("../class/c.cuenta.php");
	$tsCuenta = new tsCuenta();

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/

	if(empty($action)){
		include_once TS_EXTRA . 'datos.php';
		include_once TS_EXTRA . 'geodata.php';
		// SOLO MENORES DE 84 AÑOS xD Y MAYORES DE...
		$now_year = date("Y", time());
		// 100años - 16años = 84años
		$edad = (int)$tsCore->settings['c_allow_edad'];
		$max_year = 100 - $edad;
		$start_year = (int)$now_year - (int)$max_year;
		$end_year = (int)$now_year - (int)$tsCore->settings['c_allow_edad'];
		//
		$smarty->assign("tsMax", (int)$max_year);
		$smarty->assign("tsMaxY", (int)$start_year);
		$smarty->assign("tsEndY", (int)$end_year);
		// PERFIL INFO
      $tsPerfil = $tsCuenta->loadPerfil();
		$smarty->assign("tsPerfil", $tsPerfil);
		$smarty->assign("tsRedes", $redes);
		// PERFIL DATA
		$smarty->assign("tsPData",$tsPerfilData);
      $smarty->assign("tsPrivacidad",$tsPrivacidad);
		// DATOS
		$smarty->assign("tsPaises",$tsPaises);
		$smarty->assign("tsEstados",$estados[$tsPerfil['user_pais']]);
		$smarty->assign("tsMeses",$tsMeses);
      // BLOQUEOS
      $smarty->assign("tsBlocks",$tsCuenta->loadBloqueos());
        
	} elseif($action == 'desactivate'){
		if(!empty($_POST['validar'])) echo $tsCuenta->desCuenta();
	}
	$smarty->assign("tsAccion", $_GET["accion"]); 
	$smarty->assign("tsTab", $_GET["tab"]); 
	
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
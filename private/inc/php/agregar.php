<?php 
/**
 * Controlador
 *
 * @name    agregar.php
 * @author  PHPost Team
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "agregar";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 2;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

	$tsAjax = empty($_GET['ajax']) ? 0 : 1; // LA RESPUESTA SERA AJAX?
	
	$tsContinue = true;	// CONTINUAR EL SCRIPT
	
/*++++++++ = ++++++++*/

	require_once realpath('../../../') . DIRECTORY_SEPARATOR . "header.php";  // INCLUIR EL HEADER

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

/**********************************\

*	(INSTRUCCIONES DE CODIGO)		*

\*********************************/

	$smarty->assign('tsColor', json_encode($tsCore->colores()));

	if(is_numeric($action)){
		//}
		require_once TS_CLASS . 'c.borradores.php';
		
		$tsDrafts = new tsDrafts();
		$tsBorrador = $tsDrafts->getDraft();
		$smarty->assign("tsDraft", $tsBorrador);
		//
	} elseif($action == 'editar'){
		// CLASE
		require_once TS_CLASS . 'c.posts.php';
		$tsPosts = new tsPosts();
		// GUARDAR
		if(!empty($_POST['title'])){
		  	$post_save = $tsPosts->savePost();
			if($post_save == 1) {
				$tsPost = (int)$_GET['pid'];
				$tsCat = (int)$_POST['category'];
				$query = db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_seo FROM p_categorias AS c WHERE c.cid = $tsCat LIMIT 1");
				$tsCat = db_exec('fetch_assoc', $query);
				
				//
				$post_url = "{$tsCore->settings['url']}/posts/{$tsCat['c_seo']}/$tsPost/{$tsCore->setSEO($_POST['title'])}.html";
				// NOS VAMOS AL POST
				$tsCore->redirectTo($post_url);
			} else {
                $tsPage = 'aviso';
                $smarty->assign("tsAviso",array('titulo' => 'Oops!', 'mensaje' => $post_save, 'but' => 'Volver', 'link' => 'javascript:history.go(-1)'));
			}
		// EDITAR
		} else {
            $draft = $tsPosts->getEditPost();
            if(!is_array($draft)){
                $tsPage = 'aviso';
                $smarty->assign("tsAviso",array('titulo' => 'Opps...', 'mensaje' => $draft, 'but' => 'Ir a pagina principal', 'link' => "{$tsCore->settings['url']}"));
            }else $smarty->assign("tsDraft", $draft);
		}
		//
		$smarty->assign("tsAction", $_GET['action']);
		$smarty->assign("tsPid", $_GET['pid']);
		
	} elseif($_POST['title']) {
		// CLASE
		require_once TS_CLASS . 'c.posts.php';
		$tsPosts = new tsPosts();
		//
		$tsPost = $tsPosts->newPost();
		//
		$tsPage = 'aviso';
		$tsAjax = 0;
		if($tsPost > 0) {
			$tsCat = (int)$_POST['category'];
			$query = db_exec([__FILE__, __LINE__], 'query', "SELECT c.c_seo FROM p_categorias AS c WHERE c.cid = $tsCat LIMIT 1");
			$tsCat = db_exec('fetch_assoc', $query);
			
			//
			header("Location: {$tsCore->settings['url']}/posts/{$tsCat['c_seo']}/$tsPost/{$tsCore->setSEO($_POST['title'])}.html");
			//$smarty->assign("tsAviso",array('titulo' => 'Bien!', 'mensaje' => 'El post <b>'.$_POST['titulo'].'</b> fue agregado. '.(!$tsUser->is_admod && ($tsUser->permisos['gorpap'] == true || $tsCore->settings['c_desapprove_post'] == 1) ? 'Deber&aacute; esperar su aprobaci&oacute;n' : '').' ', 'but' => 'Acceder al post', 'link' => "{$tsCore->settings['url']}/posts/{$tsCat['c_seo']}/$tsPost/{$tsCore->setSEO($_POST['title'])}.html"));
		} elseif($tsPost == -1){
			$smarty->assign("tsAviso", array('titulo' => 'Anti Flood', 'mensaje' => "No puedes realizar tantas acciones en tan poco tiempo. Vuelve a intentarlo en unos instantes.", 'but' => 'Volver', 'link' => "javascript:history.go(-1)"));
		} else {
			$smarty->assign("tsAviso", array('titulo' => 'Oops!', 'mensaje' => "Ha ocurrido un error intentalo m&aacute;s tarde.<br><b>Error</b>: ".$tsPost, 'but' => 'Volver', 'link' => 'javascript:history.go(-1)'));
		}
	}
	
/**********************************\

* (AGREGAR DATOS GENERADOS | SMARTY) *

\*********************************/
	}

if(empty($tsAjax)) {

	$smarty->assign("tsTitle",$tsTitle);	
	/*++++++++ = ++++++++*/
	include TS_ROOT . "footer.php";
	/*++++++++ = ++++++++*/
}
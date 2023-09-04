<?php 
/**
 * Controlador
 *
 * @name    admin.php
 * @author  PHPost Team
*/

/**********************************\

*	(VARIABLES POR DEFAULT)		*

\*********************************/

	$tsPage = "admin";	// tsPage.tpl -> PLANTILLA PARA MOSTRAR CON ESTE ARCHIVO.

	$tsLevel = 4;		// NIVEL DE ACCESO A ESTA PAGINA. => VER FAQs

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
	
	include_once TS_CLASS . "c.admin.php";

	// ACTION
	$action = htmlspecialchars($_GET['action']);
	// ACTION 2
	$act = htmlspecialchars($_GET['act']);
	// CLASE POSTS
	$tsAdmin = new tsAdmin();

	// Bienvenida
	if($action === '') {
		$tsTitle = 'Centro de Administración';
		$smarty->assign("tsAdmins", $tsAdmin->getAdmins());
      $smarty->assign("tsInst", $tsAdmin->getInst());
	// Creditos
	} elseif($action === 'creditos') {
		$tsTitle = 'Soporte y Cr&eacute;ditos';
		$smarty->assign("tsVersion", $tsAdmin->getVersions());
	// Configuraciones y Registro
	} elseif(in_array($action, ['configs', 'registro'])){
		$tsTitle = ($action === 'configs') ? 'Configuraci&oacute;n' : 'Registro de ' . $tsTitle;
		// GUARDAR CONFIGURACION
		if(!empty($_POST['titulo']) OR (!empty($_POST['pkey']) AND !empty($_POST['skey']))) {
			if($tsAdmin->saveConfig()) $tsCore->redireccionar('admin', $action, 'save=true');
		}
	// Temas
	} elseif($action === 'temas') {
		$tsTitle = 'Diseños / Temas';
		// VER TEMAS
		if(empty($act)) $smarty->assign("tsTemas", $tsAdmin->getTemas());
		// Editar o Nuevo tema
		elseif(in_array($act, ['editar', 'nuevo'])) {
			$tsTitle = ucfirst($act) . ' tema';
			if(!empty($_POST['save']) OR !empty($_POST['path'])) {
				$ActTheme = ($act === 'editar') ? $tsAdmin->saveTema() : $tsAdmin->newTema();
				if($ActTheme) $tsCore->redireccionar('admin', $action, 'save=true');
			} else {
				if($act === 'editar') $smarty->assign("tsTema", $tsAdmin->getTema());
				if($act === 'nuevo') $smarty->assign("tsError", $tsAdmin->newTema());
			} 
		} elseif($act === 'activar') {
			if($tsAdmin->changeTema()) $tsCore->redireccionar('admin', $action, 'save=true');
		} elseif($act === 'borrar') {
			$tsTitle = 'Borrar tema';
			if(!empty($_POST['confirm'])) {
				if($tsAdmin->deleteTema()) $tsCore->redireccionar('admin', $action, 'save=true');
			}
			$smarty->assign("tt", $_GET['tt']);
		}
	// Noticias
   } elseif($action === 'news'){
		$tsTitle = 'Noticias';
      if(empty($act)) $smarty->assign("tsNews",$tsAdmin->getNoticias());
      elseif($act === 'nuevo' && !empty($_POST['not_body'])){
         if($tsAdmin->newNoticia()) $tsCore->redireccionar('admin', $action, 'save=true');
      } elseif($act === 'editar'){
         if(!empty($_POST['not_body'])){
            if($tsAdmin->editNoticia()) $tsCore->redireccionar('admin', $action, 'save=true');
         } else $smarty->assign("tsNew",$tsAdmin->getNoticia());
      }  elseif($act === 'borrar'){
         if($tsAdmin->delNoticia()) $tsCore->redireccionar('admin', $action, 'borrar=true');
		}
	// Publicidades
	} elseif($action === 'ads'){
		$tsTitle = 'Publicidades';
		if(!empty($_POST['save'])){
			if($tsAdmin->saveAds()) $tsCore->redireccionar('admin', $action, 'save=true');
		}
	// POSTS
	} elseif($action === 'posts'){
		$tsTitle = 'Todos los posts';
		if(!$act) $smarty->assign("tsAdminPosts", $tsAdmin->GetAdminPosts());
	//FOTOS
	} elseif($action === 'fotos') {
		$tsTitle = 'Todas las fotos';
		if(!$act) $smarty->assign("tsAdminFotos", $tsAdmin->GetAdminFotos());
	// ESTADÍSTICAS
	} elseif($action === 'stats'){
		$tsTitle = 'Estad&iacute;sticas';
		$smarty->assign("tsAdminStats", $tsAdmin->GetAdminStats());	
	// CAMBIOS DE NOMBRE DE USUARIO
	} elseif($action === 'nicks'){
		$tsTitle = 'Nicks';
		 if(!$act) {
		 $smarty->assign("tsAdminNicks",$tsAdmin->getChangeNicks());
		 }elseif($act === 'realizados'){
		 $smarty->assign("tsAdminNicks",$tsAdmin->getChangeNicks_A());
		 }
   // LISTA NEGRA
    } elseif($action === 'blacklist'){
		 if(!$act) {
		 $smarty->assign("tsBlackList",$tsAdmin->getBlackList());
		 }elseif($act === 'editar'){
         if($_POST['edit']){
                $editar = $tsAdmin->saveBlock();
				if($editar == 1) $tsCore->redirectTo($tsCore->settings['url'].'/admin/blacklist?save=true');
				else $smarty->assign("tsError",$editar); $smarty->assign("tsBL",array(value => $_POST['value'], type => $_POST['type']));
         }else $smarty->assign("tsBL",$tsAdmin->getBlock());
		 }elseif($act === 'nuevo'){
		  if($_POST['new']){
                $nuevo = $tsAdmin->newBlock();
				if($nuevo == 1) $tsCore->redirectTo($tsCore->settings['url'].'/admin/blacklist?save=true');
				else $smarty->assign("tsError",$nuevo); $smarty->assign("tsBL",array(value => $_POST['value'], type => $_POST['type'], reason => $_POST['reason']));
          }
          }
          // CENSURAS
          } elseif($action === 'badwords'){
		 if(!$act) {
		 $smarty->assign("tsBadWords",$tsAdmin->getBadWords());
		 }elseif($act === 'editar'){
         if($_POST['edit']){
                $editar = $tsAdmin->saveBadWord();
				if($editar == 1) $tsCore->redirectTo($tsCore->settings['url'].'/admin/badwords?save=true');
				else $smarty->assign("tsError",$editar); $smarty->assign("tsBW",array(word => $_POST['before'], swop => $_POST['after'], method => $_POST['method'], type => $_POST['type']));
         }else $smarty->assign("tsBW",$tsAdmin->getBadWord());
		 }elseif($act === 'nuevo'){
		  if($_POST['new']){
                $nuevo = $tsAdmin->newBadWord();
				if($nuevo == 1) $tsCore->redirectTo($tsCore->settings['url'].'/admin/badwords?save=true');
				else $smarty->assign("tsError",$nuevo); $smarty->assign("tsBW",array(word => $_POST['before'], swop => $_POST['after'], method => $_POST['method'], type => $_POST['type'], reason => $_POST['reason']));
          }
          }
	// CONECTADOS A LA COMUNIDAD
	} elseif($action === 'sesiones'){
		 if(!$act) {
		 $smarty->assign("tsAdminSessions",$tsAdmin->GetSessions());
		 }
    /** MEDALLAS **/
    } elseif($action === 'medals'){
    	// CLASE MEDAL
    	include("../class/c.medals.php");
    	$tsMedal = new tsMedal();
        if(empty($act)){
            $smarty->assign("tsMedals",$tsMedal->adGetMedals());
        } elseif($act === 'nueva'){
            if($_POST['save']){
				$agregar = $tsMedal->adNewMedal();
				if($agregar == 1) $tsCore->redirectTo($tsCore->settings['url'].'/admin/medals?save=true');
				else $smarty->assign("tsError",$agregar); $smarty->assign("tsMed",array(m_title => $_POST['med_title'], m_description => $_POST['med_desc'], m_image => $_POST['med_img'], m_cant => $_POST['med_cant'], m_type => $_POST['med_type'], m_cond_user => $_POST['med_cond_user'], m_cond_user_rango => $_POST['med_cond_user_rango'], m_cond_post => $_POST['med_cond_post'], m_cond_foto => $_POST['med_cond_foto']));
            }
				//ICONOS PARA LAS MEDALLAS
				$smarty->assign("tsIcons",$tsAdmin->getExtraIcons('med', 16));
				//RANGOS DISPONIBLES
				$smarty->assign("tsRangos",$tsAdmin->getAllRangos());
            
			} elseif($act === 'showassign'){
				$smarty->assign("tsAsignaciones",$tsMedal->adGetAssign());
         } elseif($act === 'editar'){
            if($_POST['edit']){
                $editar = $tsMedal->editMedal();
				if($editar == 1) $tsCore->redirectTo($tsCore->settings['url'].'/admin/medals?act=editar&mid='.$_GET['mid'].'&save=true');
				else $smarty->assign("tsError",$editar); $smarty->assign("tsMed",array(m_title => $_POST['med_title'], m_description => $_POST['med_desc'], m_image => $_POST['med_img'], m_cant => $_POST['med_cant'], m_type => $_POST['med_type'], m_cond_user => $_POST['med_cond_user'], m_cond_user_rango => $_POST['med_cond_user_rango'], m_cond_post => $_POST['med_cond_post'], m_cond_foto => $_POST['med_cond_foto']));
            }else $smarty->assign("tsMed",$tsMedal->adGetMedal());  //DATOS DE LA MEDALLA
				// ICONOS PARA LA MEDALLA
				$smarty->assign("tsIcons",$tsAdmin->getExtraIcons('med', 16));
				//RANGOS DISPONIBLES
				$smarty->assign("tsRangos",$tsAdmin->getAllRangos());
                
        }
	} elseif($action === 'afs'){
        // CLASS
        include("../class/c.afiliado.php");
        $tsAfiliado = new tsAfiliado;
        // QUE HACER
	   if($act === ''){
        // AFILIADOS
        $smarty->assign("tsAfiliados",$tsAfiliado->getAfiliados('admin'));
	   } elseif($act === 'editar'){
            if($_POST['edit']){
                if($tsAfiliado->EditarAfiliado()) $tsCore->redirectTo($tsCore->settings['url'].'/admin/afs?act=editar&aid='.$_GET['aid'].'&save=true');
            }
				$smarty->assign("tsAf",$tsAfiliado->getAfiliado('admin'));

                
        }
   // Categorías
	} elseif($action === 'cats'){
		$tsTitle = 'Todas las categor&iacute;as';
		if(!empty($_GET['ordenar'])) $tsAdmin->saveOrden();
		elseif(in_array($act, ['editar', 'nueva'])){
			$tsTitle = ucfirst($act) . ' categor&iacute;a';
			if($_POST['save']){
				$both = ($act === 'editar') ? $tsAdmin->saveCat() : $tsAdmin->newCat();
				if($both) $tsCore->redireccionar('admin', $action, 'save=true');
			} else {
				$smarty->assign("tsType", $_GET['t']);
				if($act === 'editar') $smarty->assign("tsCat", $tsAdmin->getCat());
				if($act === 'nueva') $smarty->assign("tsCID", $_GET['cid']);
				// SOLO LAS CATEGORIAS TIENEN ICONOS
				$smarty->assign("tsIcons", $tsAdmin->getExtraIcons());
			}
		} elseif($act === 'change'){
			$tsTitle = 'Cambiar categor&iacute;a';
			if($_POST['save']){
				if($tsAdmin->MoveCat()) $tsCore->redireccionar('admin', $action, 'save=true');
			}
		} elseif($act === 'borrar'){
			$tsTitle = 'Borrar categor&iacute;a';
			if($_POST['save']){
				// BORRAR CATEGORIA
				if($_GET['t'] === 'cat'){
					$save = $tsAdmin->delCat();
					if($save == 1) $tsCore->redireccionar('admin', $action, 'save=true');
					else $smarty->assign("tsError",$save); 
				} 
			}
			//
			$smarty->assign("tsType", $_GET['t']);
			$smarty->assign("tsCID", $_GET['cid']);
			$smarty->assign("tsSID", $_GET['sid']);
		}
	// Rangos
	} elseif($action === 'rangos') {
		$tsTitle = 'Todos los Rangos';
		// PORTADA
		if(empty($act)) $smarty->assign("tsRangos",$tsAdmin->getRangos());
		// LISTAR USUARIOS DEPENDIENDO EL RANGO
		elseif($act === 'list') {
			$smarty->assign("tsMembers", $tsAdmin->getRangoUsers());
		// EDITAR RANGO
		} elseif(in_array($act, ['editar', 'nuevo'])) {
			$tsTitle = ucfirst($act) . " rango";
			if(!empty($_POST['save'])){
				$both = ($act === 'editar') ? $tsAdmin->saveRango() : $tsAdmin->newRango();
				if($both) $tsCore->redireccionar('admin', $action, 'save=true');
			} else {
				if($act === 'editar') $smarty->assign("tsRango", $tsAdmin->getRango());
            if($act === 'nuevo') $smarty->assign("tsError", $save); 
            $smarty->assign("tsType", $_GET['t']);
				$smarty->assign("tsIcons", $tsAdmin->getExtraIcons('ran'));
			}
		// NUEVO RANGO
		} elseif($act === 'borrar'){
			$tsTitle = ucfirst($act) . " rango";
			if(empty($_POST['save'])) $smarty->assign("tsRangos", $tsAdmin->getAllRangos());
			else {
				if($tsAdmin->delRango()) $tsCore->redireccionar('admin', $action, 'save=true');
			}
		// CAMBIAR RANGO PREDETERMINADO DEL REGISTRO
		} elseif($act === 'setdefault') {
			if($tsAdmin->SetDefaultRango()) $tsCore->redireccionar('admin', $action, 'save=true');
		}
	// Usuarios
	} elseif($action === 'users'){
	   if(empty($act)){
	       $smarty->assign("tsMembers",$tsAdmin->getUsuarios());
	   }elseif($act === 'show'){
	       $do = $_GET['t'];
           $user_id = $_GET['uid'];
           // HACER
           switch($do){
				case 5:
        	       if(!empty($_POST['save'])){
        	           $update = $tsAdmin->setUserPrivacidad($user_id);
        	           if($update === 'OK') $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user_id.'&save=true');
                       else $smarty->assign("tsError",$update);
                    }
					include('../ext/datos.php');
                    $smarty->assign("tsPerfil",$tsAdmin->getUserPrivacidad());
					$smarty->assign("tsPrivacidad",$tsPrivacidad);
                break;
                case 6:
        	       if(!empty($_POST['save'])){
        	           $delete = $tsAdmin->deleteContent($user_id);
        	           if($delete === 'OK') $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user_id.'&save=true');
                       else $smarty->assign("tsError",$delete);
                    }
					include('../ext/datos.php');
                    $smarty->assign("tsPerfil",$tsAdmin->getUserPrivacidad());
					$smarty->assign("tsPrivacidad",$tsPrivacidad);
                break;
                case 7:
        	       if(!empty($_POST['save'])){
        	           $update = $tsAdmin->setUserRango($user_id);
        	           if($update === 'OK') $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user_id.'&save=true');
                       else $smarty->assign("tsError",$update);
                    }
                    $smarty->assign("tsUserR",$tsAdmin->getUserRango($user_id));
                break;
				case 8:
        	       if(!empty($_POST['save'])){
        	           $update = $tsAdmin->setUserFirma($user_id);
        	           if($update === 'OK') $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user_id.'&save=true');
                       else $smarty->assign("tsError",$update);
                    }
					$smarty->assign("tsUserF",$tsAdmin->getUserData());
                break;
                default:
                    if(!empty($_POST['save'])){
        	           $update = $tsAdmin->setUserData($user_id);
        	           if($update === 'OK') $tsCore->redirectTo($tsCore->settings['url'].'/admin/users?act=show&uid='.$user_id.'&save=true');
                       else $smarty->assign("tsError",$update);
                    }
    	           $smarty->assign("tsUserD",$tsAdmin->getUserData());
                break;
           }
           // TIPO
           $smarty->assign("tsType",$_GET['t']);
           $smarty->assign("tsUserID",$user_id);
           $smarty->assign("tsUsername",$tsUser->getUserName($user_id));
	   }
	}

/**********************************\

* (AGREGAR DATOS GENERADOS | SMARTY) *

\*********************************/
	// ACCION?
	$smarty->assign("tsAction",$action);
	//
	$smarty->assign("tsAct",$act);
	//
	}

if(empty($tsAjax)) {	// SI LA PETICION SE HIZO POR AJAX DETENER EL SCRIPT Y NO MOSTRAR PLANTILLA, SI NO ENTONCES MOSTRARLA.

	$smarty->assign("tsTitle",$tsTitle);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL
	
	$smarty->assign("tsSave",$_GET['save']);	// AGREGAR EL TITULO DE LA PAGINA ACTUAL
	
	/*++++++++ = ++++++++*/
	include("../../footer.php");
	/*++++++++ = ++++++++*/
}
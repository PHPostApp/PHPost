<?php if ( ! defined('TS_HEADER')) exit('No se permite el acceso directo al script');
/**
 * Modelo para el control de los borradores
 *
 * @name    c.borradores.php
 * @author  PHPost Team
 */
require_once TS_CLASS . 'c.upload.php';
$tsUpload = new tsUpload();

class tsDrafts {

	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*\
								BORRADORES
	/*++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++*/
	/*
		newDraft()
	*/
	function newDraft($save = false){
		global $tsCore, $tsUser, $tsUpload;
		//
		$_POST['date'] = time();
		//
		$cover = $tsCore->cover('draft');
		if(empty($_POST['borrador_id'])) unset($_POST['borrador_id']);
		
		if(!empty($_POST['title'])) {
			if(!empty($_POST['category']) && $_POST['category'] > 0) {
				if($save) {
					// UPDATE
					$bid = (int)$_POST['borrador_id'];
					$updates = $tsCore->getIUP($_POST, 'b_');
					//
					if(db_exec([__FILE__, __LINE__], 'query', "UPDATE p_borradores SET $updates WHERE bid = $bid AND b_user = {$tsUser->info['user_id']}")) return '1: '.$bid;
					else return '0: '.show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db');
			   } else {
					// INSERT
					$_POST['status'] = 1;
					$_POST['causa'] = '';
					$_POST['portada'] = $_POST['key'];
					if($_POST['myportada'] === 'pc') {
						unset($_POST['ext']);
						unset($_POST['key']);
					}
					unset($_POST['url']);
					unset($_POST['myportada']);
						
				   if(insertInto([__FILE__, __LINE__], 'p_borradores', $_POST, 'b_')) {
				   	return '1: '.db_exec('insert_id');
				   } else return '0: '.show_error('Error al ejecutar la consulta de la l&iacute;nea '.__LINE__.' de '.__FILE__.'.', 'db');
				}
			} else $return = 'Categor&iacute;a';
		} else $return = 'T&iacute;tulos';
		//
		return '0: El campo <b>'.$return.'</b> es requerido para esta operaci&oacute;n';
		//
	}
	/*
		getDrafts()
	*/
	function getDrafts(){
		global $tsCore, $tsUser;
		//
		$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT c.c_nombre, c.c_seo, c.c_img, b.bid, b.b_title, b.b_date, b.b_status, b.b_causa FROM p_categorias AS c LEFT JOIN p_borradores AS b ON c.cid = b.b_category WHERE b.b_user = \''.$tsUser->info['user_id'].'\' ORDER BY b.b_date');
		//
		$drafts = result_array($query);
		return $drafts;
	}
	/*
		getDraft()
	*/
	function getDraft($status = 1){
		global $tsCore, $tsUser;
		//
		$bid = intval($_GET['action']);
        $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT bid, b_user, b_date, b_title, b_body, b_tags, b_category, b_private, b_block_comments, b_sponsored, b_sticky, b_smileys, b_post_id, b_status, b_causa FROM `p_borradores` WHERE `bid` = \''.(int)$bid.'\' AND `b_user` = \''.$tsUser->info['user_id'].'\' AND b_status = \''.$status.'\' LIMIT 1');
		//
		return db_exec('fetch_assoc', $query);
	}
	/*
		delDraft()
	*/
	function delDraft(){
		global $tsCore, $tsUser;
		//
		$bid = intval($_POST['borrador_id']);
        if(db_exec([__FILE__, __LINE__], 'query', 'DELETE FROM `p_borradores` WHERE `bid` = \''.(int)$bid.'\' AND `b_user` = \''.$tsUser->info['user_id'].'\'')) return '1: Borrador eliminado';
		else return '0: Ocurri&oacute; un error';
	}

	
	
}
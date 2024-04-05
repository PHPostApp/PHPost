<?php

if (!defined('TS_HEADER'))
	 exit('No se permite el acceso directo al script');

/**
 * Basado en el mod de Vellenger
*/

class tsControlMensajes {

	function getControlMp()
     {
      global $tsCore;
      //
      $max = 21; // MAXIMO A MOSTRAR
      $limit = $tsCore->setPageLimit($max, true);

	  $retorno['data']=result_array(db_exec([__FILE__, __LINE__], 'query', 'SELECT m.mp_id, m.mp_to, m.mp_from, m.mp_subject, m.mp_preview, m.mp_date, u.user_id, u.user_name, u.user_rango, r.rango_id, r.r_name, r.r_color, r.r_image FROM u_mensajes AS m LEFT JOIN u_miembros AS u ON u.user_id = m.mp_from LEFT JOIN u_rangos AS r ON r.rango_id = u.user_rango ORDER BY m.mp_date DESC LIMIT '.$limit));


      // PAGINAS
        $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT COUNT(mp_id) FROM u_mensajes');
        list($total) = db_exec('fetch_row', $query);

      $retorno['pages'] = $tsCore->pageIndex($tsCore->settings['url'] .
            '/admin/mensajes?', $_GET['s'], $total, $max);

      return $retorno;
     }
	 
	 

	//RESPUESTAS DE MENDAJES ******************************************************************************************************************************************************
	function getDatmp()
    {
        global $tsCore;
        //
        $mpid = $tsCore->setSecure($_GET['mpid']);
        //
        $query = db_exec([__FILE__, __LINE__], 'query', 'SELECT mp_id, mp_to, mp_from, mp_subject  FROM u_mensajes WHERE mp_id = \'' .$mpid . '\' LIMIT 1');
        $data = db_exec('fetch_assoc', $query);

        //
        return $data;
    }
	function getLeermp(){
	global $tsCore, $tsUser;
	$mpid = $tsCore->setSecure($_GET['mpid']);
	$query = db_exec([__FILE__, __LINE__], 'query', 'SELECT  m.mp_id, m.mp_to, m.mp_from, m.mp_subject, m.mp_preview, m.mp_date, u.user_id, u.user_name, u.user_rango, r.rango_id, r.r_name, r.r_color, r.r_image, v.mr_id, v.mp_id, v.mr_from, v.mr_body, v.mr_ip, v.mr_date  FROM u_mensajes AS m LEFT JOIN u_miembros AS u ON u.user_id = m.mp_from LEFT JOIN u_rangos AS r ON r.rango_id = u.user_rango LEFT JOIN u_respuestas AS v ON v.mp_id = m.mp_id WHERE m.mp_id = \'' . $mpid . '\' ORDER BY v.mr_date ASC');
	$data = result_array($query);

	//
	return $data;
	}	
}
<?php 

/**
 * Al no ser instalado con composer se debe 
 * añadir todo manualmente
*/
define('EXCEPTION', TS_WHOOPS . 'Exception' . TS_PATH);
define('HANDLER', TS_WHOOPS . 'Handler' . TS_PATH);
define('INSPECTOR', TS_WHOOPS . 'Inspector' . TS_PATH);
define('RESOURCES', TS_WHOOPS . 'Resources' . TS_PATH);
define('UTIL', TS_WHOOPS . 'Util' . TS_PATH);

require_once INSPECTOR . 'InspectorInterface.php';
require_once INSPECTOR . 'InspectorFactoryInterface.php';
require_once INSPECTOR . 'InspectorFactory.php';

require_once EXCEPTION . 'Formatter.php';
require_once EXCEPTION . 'Frame.php';
require_once EXCEPTION . 'FrameCollection.php';
require_once EXCEPTION . 'Inspector.php';

require_once HANDLER . 'HandlerInterface.php';
require_once HANDLER . 'Handler.php';
require_once HANDLER . 'PlainTextHandler.php';
require_once HANDLER . 'PrettyPageHandler.php';

require_once UTIL . 'Misc.php';
require_once UTIL . 'SystemFacade.php';
require_once UTIL . 'TemplateHelper.php';

require_once TS_WHOOPS . 'RunInterface.php';
require_once TS_WHOOPS . 'Run.php';

/**
 * Manejo de errores
*/

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class WhoopsException {

	public $title = 'Upps! Aquí hay un error.';

	public $editor = 'sublime';

	public function __construct() {
	}

	public function getException($setException) {
		$WhoopsRun = new Run;

		$PrettyPageHandler = new PrettyPageHandler;

		$PrettyPageHandler->setPageTitle($this->title);

		$PrettyPageHandler->setEditor($this->editor);

		$WhoopsRun->pushHandler($PrettyPageHandler);

		return $WhoopsRun->handleException($setException);
	}

}
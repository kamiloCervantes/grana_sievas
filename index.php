<?php

define('DS',DIRECTORY_SEPARATOR);

define('APP_PATH',dirname(__FILE__));
define('PUBLIC_PATH',APP_PATH.DS.'public'.DS);
define('CORE_PATH',APP_PATH.DS.'core'.DS);
define('LIBS_PATH',CORE_PATH.'libs'.DS);
define('TEMPLATE_PATH',APP_PATH.DS.'template'.DS);
define('MODULES_PATH',APP_PATH.DS.'modules'.DS);
define('MOD_PATH',MODULES_PATH.$_GET['mod'].DS);

require CORE_PATH.'FrontController.php';

FrontController::main();

?>
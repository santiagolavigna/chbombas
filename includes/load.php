
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
// -----------------------------------------------------------------------
// DEFINE SEPERATOR ALIASES
// -----------------------------------------------------------------------
define("URL_SEPARATOR", '/');

define("DS", DIRECTORY_SEPARATOR);

// -----------------------------------------------------------------------
// DEFINE ROOT PATHS
// -----------------------------------------------------------------------
defined('SITE_ROOT')? null: define('SITE_ROOT', realpath(dirname(__FILE__)).DS."..".DS);
define("LIB_PATH_INC", SITE_ROOT."includes".DS);

date_default_timezone_set('America/Argentina/Buenos_Aires');

require_once(LIB_PATH_INC.'config.php');
require_once(LIB_PATH_INC.'functions.php');
require_once(LIB_PATH_INC.'session.php');
require_once(LIB_PATH_INC.'upload.php');
require_once(LIB_PATH_INC.'database.php');
require_once(LIB_PATH_INC.'sql.php');
require_once(LIB_PATH_INC.'Utils.php');
require_once(LIB_PATH_INC.'HTMLConstructor.php');



?>

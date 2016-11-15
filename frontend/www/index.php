<?php
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// +  This is the main entrance of the lifephp framework
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

//Record the first usage of mermory
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
if (MEMORY_LIMIT_ON) {
    $GLOBALS['startUseMems'] = memory_get_usage();
}

//Define the dirname of the framework system
defined('ROOT_PATH') or define('ROOT_PATH', dirname(dirname(__FILE__)).'/');
defined('LIFE_PATH') or define('LIFE_PATH', dirname(__FILE__).'/');
//Register version info
defined('LIFE_VERSION') or define('LIFE_VERSION', '1.0');
//Class file suffix
defined('EXT_SUFFIX') or define('EXT_SUFFIX', '.class.php');
defined('VIEW_SUFFIX') or define('VIEW_SUFFIX', '.php');

//The dirname of the system core library file
defined('COMMON_PATH') or define('COMMON_PATH', LIFE_PATH . 'common/');
defined('CORE_PATH') or define('CORE_PATH', LIFE_PATH . 'core/');

//Enviroment check
define('SAPI_IS_CGI', (0 === strpos(PHP_SAPI, 'cgi') || false !== strpos(PHP_SAPI, 'fcgi')) ? 1 : 0);
define('OS_IS_WIN', strstr(PHP_OS, 'WIN') ? 1 : 0);
define('SAPI_IS_CLI', PHP_SAPI == 'cli' ? 1 : 0);

//Load LifePHP core framework class
require LIFE_PATH . 'Life.php';




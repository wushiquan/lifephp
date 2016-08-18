<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + The entrance of the lifephp application
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

//Define the important params of the application
defined('APP_NAME') or define('APP_NAME', 'frontend');
defined('APP_PATH') or define('APP_PATH',dirname(dirname(__FILE__).'/'));

ini_set("display_errors", "On");
error_reporting(E_ALL);

//Load the core lifephp framework
$app_config = require(APP_PATH.'/config/main.php');
require('../../lifephp/LifePHP.php');
(new Life())->runApplication($app_config);

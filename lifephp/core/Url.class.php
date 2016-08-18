<?php
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + LifePHP url route core class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------
namespace lifephp\core;
class Url
{

    /**
     * @uses   parse the url route
     * @access public
     * @return void
     */
    public static function route()
    {
        if (false !== strpos($_SERVER['REQUEST_URI'],'index.php') && isset($_GET['r'])) {
			$pathInfo = $_SERVER['QUERY_STRING']; 
			list($pathName, $pathStr)  = explode('=',$pathInfo);
			if ('r' == $pathName ) {
				list($controllerName,$actionName)  = explode('/',$pathStr);
				define('CONTROLLER_NAME', ucfirst($controllerName) . 'Controller');
				define('ACTION_NAME', 'action' . ucfirst($actionName));
			} else {
				throw new Exception("Error Processing Request", '505');			
			}
        } else {
			list($controllerName,$actionName)  = explode('/',substr($_SERVER['REQUEST_URI'],1));
			define('CONTROLLER_NAME', ucfirst($controllerName) . 'Controller');
			define('ACTION_NAME', 'action' . ucfirst($actionName));	
        }

    }

}

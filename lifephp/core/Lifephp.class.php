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

use \Exception;
class Url
{

    /**
     * @var default controller name
     */
    protected static $controllerSuffix = 'Controller';

    /**
     * @var default controller name
     */
    protected static $actionPrefix = 'action';

    /**
     * @var default controller name
     */
    protected static $defaultContName = 'site';

    /**
     * @var default action name
     */
    protected static $defaultActionName = 'index';

    /**
     * @uses   parse the url route
     * @access public
     * @return void
     */
    public static function route()
    { 
        if (false !== strpos($_SERVER['REQUEST_URI'], 'index.php') && isset($_GET['r'])) {
			$pathInfo = $_SERVER['QUERY_STRING']; 
			$queryParam = explode('&', $pathInfo);
            $routeSerh = false;
            foreach($queryParam as $param){               
                list($pathName, $pathStr) = explode('=', $param);
                if ('r' == $pathName ) {
                    list($controllerName, $actionName)  = explode('/', $pathStr);
                    $routeSerh = true;
                    unset($_GET['r']);
                } else {
                    continue;                          
                }
            }

            if($routeSerh === false) 
                throw new Exception("Error Processing Request", '505');       
        }  elseif (false !== strpos(trim($_SERVER['REQUEST_URI'], '/'), '/')) {
			list($controllerName, $actionName)  = explode('/',substr($_SERVER['REQUEST_URI'],1));
        }  else {
			$controllerName = $actionName = '';
        }

        define('CONTROLLER_NAME', self::getControllerName($controllerName));
		define('ACTION_NAME', self::getActionName($actionName));	
    }

    /**
     * @uses   get the true controller name
     * @access private
     * @return void
     */
    private static function getControllerName($controllerName)
    {
    	if ('' === $controllerName) {
    		$controllerName	= self::$defaultContName;  
    	}
    	define('CONTROLLER_ALIAS', strtolower($controllerName));
    	return strip_tags(ucfirst($controllerName)) . self::$controllerSuffix;
    }

    /**
     * @uses   get the true action name
     * @access private
     * @return void
     */
    private static function getActionName($actionName)
    {
    	if ('' === $actionName) {
    		$actionName	= self::$defaultActionName;  
    	}
    	define('ACTION_ALIAS', strtolower($actionName));
    	return self::$actionPrefix . strip_tags(ucfirst($actionName));
    }
}

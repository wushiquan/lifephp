<?php
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + LifePHP application core class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------
namespace lifephp\core;
class Application
{

    /**
     * @uses   initialize application
     * @access public
     * @return void
     */
    public static function init()
    { 
        // define current system constant
        define('REQUEST_TIME', $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
        // define if the request type is ajax
		define('IS_AJAX', ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || !empty($_POST['FORM_AJAX_SUBMIT']) || !empty($_GET['FORM_AJAX_SUBMIT'])) ? true : false);

        //locate the url route 
        Url::route();    
    }

    /**
     * @uses   execute application
     * @access public
     * @return void
     */
    public static function exec()
    {

        if (!preg_match('/^[A-Za-z](\/|\w)*$/', CONTROLLER_NAME)) {
            $module = false;
        } elseif ( CONTROLLER_NAME && ACTION_NAME ) {
            $layer = 'controller';
            $controllerPath = APP_PATH . '/' . $layer . '/';
            if (is_dir($controllerPath)) {
                $namespace = APP_NAME . '\\' . $layer . '\\' . CONTROLLER_NAME;
            } else {
                // empty controller
                $namespace = APP_NAME . '\\' . $layer . '\\_empty';
            }    
            if (class_exists($namespace) && is_file($controllerPath . CONTROLLER_NAME . EXT_SUFFIX)) {
                $class = $namespace;
            } else {
            	exit('CONTROLLER_NOT_EXIST' . ':' . CONTROLLER_NAME);    
            }
            $module = new $class;
        }

        // get current action name
        if (!isset($action) && ACTION_NAME) {
            $action = ACTION_NAME;
        } else {
			$action = 'actionEmpty';
        }

        try {
            self::invokeAction($module,$action);
        } catch (\ReflectionException $e) {
            // if calling function failed,execute the  '_call' function
            $method = new \ReflectionMethod($module, '__call');
            $method->invokeArgs($module,array($action, ''));
        }
        return;
    }

    private static function invokeAction($module, $action)
    {
        if (!preg_match('/^[A-Za-z](\w)*$/', $action)) {
            throw new \ReflectionException();
        }

        //execute current action
        $method = new \ReflectionMethod($module, $action);
        if ($method->isPublic() && !$method->isStatic()) {
            $class = new \ReflectionClass($module);

            // invoke before action
            if ($class->hasMethod('_before_' . $action)) {
                $before = $class->getMethod('_before_' . $action);
                if ($before->isPublic()) {
                    $before->invoke($module);
                }
            }

            // check if binds the URL parameters
            if ($method->getNumberOfParameters() > 0){
                switch(REQUEST_METHOD) {
                    case 'POST':
                        $vars = array_merge($_GET, $_POST);
                        break;
                    case 'PUT':
                        parse_str(file_get_contents('php://input'), $vars);
                        break;
                   	case 'GET':
                        $vars = $_GET;
                        break;    
                    default:
                        $vars = $_GET;
                }
                $params = $method->getParameters();
                foreach ($params as $param) {
                    $name = $param->getName();
  					if (isset($vars[$name])) {
                        $args[] = $vars[$name];
                    } elseif ($param->isDefaultValueAvailable()) {
                        $args[] = $param->getDefaultValue();
                    } else {
                        exit('PARAM_ERROR'.':'.$name);
                    }
                }
                $method->invokeArgs($module, $args);
            } else {
                $method->invoke($module);
            }

            // invoke after action 
            if ($class->hasMethod('_after_' . $action)) {
                $after = $class->getMethod('_after_' . $action);
                if ($after->isPublic()) {
                    $after->invoke($module);
                }
            }
        } else {
            throw new \ReflectionException();
        }
    }

    /**
     * @uses   run the instance of the application
     * @access public
     * @return void
     */
    public static function run()
    {   
        self::init();
        self::exec();
        return;
    }
}

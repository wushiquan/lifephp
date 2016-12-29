<?php
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + LifePHP core class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace lifephp\core;
use Life;
class Lifephp
{
    /**
     * @uses   initialize application program
     * @access public
     * @param  void
     * @return void
     */
    public static function run()
    {
        // register autoload function
        spl_autoload_register('lifephp\core\Lifephp::autoload');
        // set system timezone
        date_default_timezone_set(!empty(Life::$frame->app_config['timeZone'])?Life::$frame->app_config['timeZone']:'Asia/Shanghai');

        // set error and exception handler
        register_shutdown_function('lifephp\core\Lifephp::getFatalError');
        set_error_handler('lifephp\core\Lifephp::lifeError');
        set_exception_handler('lifephp\core\Lifephp::lifeException');

        //Init the database connection if the '$db' object is null.
        Life::$frame->db = self::getDb();

        //For framework requirement, it is necessary to init other framework core component.
        self::initOtherCoreComponent();

        // run the application
        Application::run();
    }

    /**
     * @uses   autoload the library class
     * @param  string $class the class name of object
     * @return void
     */
    public static function autoload($class)
    {
		if (false !== strpos($class, '\\')) {
            $name = strstr($class, '\\', true);
            if (false !== strpos($name,'lifephp') || is_dir(LIFE_PATH.$name)) {
                // Locate the namespace in the Life directory automatically
                $path = ROOT_PATH;         
            } elseif (false !== strpos($name,APP_NAME)){
                // Locate the namespace in the current application directory
                $path  = ROOT_PATH;
            } else {
            	$path  = ROOT_PATH;
            }
        } else {
        	$path = CORE_PATH;
        } 
       
        $filename = $path . str_replace('\\', '/', $class) . EXT_SUFFIX;
        if (is_file($filename)) {
            // Window environment strictly distinguish case
            if(OS_IS_WIN && false === strpos(str_replace('/', '\\', realpath($filename)), $class.EXT_SUFFIX)) {
                return;
            }
            include $filename;
       }
    }    

    /**
     * @uses   define exception handler
     * @access private
     * @param  mixed $e exception object
     */
    public static function lifeException($e)
    {
        $error            = [];
        $error['message'] = $e->getMessage();
        $trace            = $e->getTrace();
        if ('E' == $trace[0]['function']) {
            $error['file'] = $trace[0]['file'];
            $error['line'] = $trace[0]['line'];
        } else {
            $error['file'] = $e->getFile();
            $error['line'] = $e->getLine();
        }
        $error['trace'] = $e->getTraceAsString();

        // send 404 status information
        header('HTTP/1.1 404 Not Found');
        header('Status:404 Not Found');
        self::printError($error);	
    }

    /**
     * @uses   define error handler
     * @access private
     * @param  int $errno  the error type
     * @param  string $errstr the error info
     * @param  string $errfile the error file
     * @param  int $errline the error line
     * @return void
     */
    public static function lifeError($errno, $errstr, $errfile, $errline)
    {
        switch ($errno) {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
                ob_end_clean();
                $errorStr = "$errstr " . $errfile . " the $errline line.";
                self::printError($errorStr);	
                break;
            default:
                $errorStr = "[$errno] $errstr " . $errfile . " the $errline line.";
                self::printError($errorStr);	
                break;
        }
    }

     /**
     * @uses   capture fatal error
     * @access private
     * @return void
     */
    public static function getFatalError()
    {
        if ($e = error_get_last()) {
            switch ($e['type']) {
                case E_ERROR:
                case E_PARSE:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                    ob_end_clean();
                    //echo error info
					self::printError($e);	  
                    break;
            }
        }
    }

   /**
     * @uses   output the error info
     * @access private
     * @return void
     */
    private static function printError($e)
    {   
        //echo error info
		if (!is_array($e)) {
            $trace   = debug_backtrace();
            $message = $e;
            $e       = [];
            $e['message'] = $message;
            $e['file']    = $trace[0]['file'];
            $e['line']    = $trace[0]['line'];

            ob_start();
            debug_print_backtrace();
            $e['trace'] = ob_get_clean();
		} else {
			$e['trace'] = '';	
		}

		$info = "The LifePHP framework occurs an error or throws execption : <br/>"
			  . "The error message : {$e['message']}<br/>"
			  . "The error file location : {$e['file']}<br/>"
			  . "The error file line : {$e['line']}<br/>"
			  . "The error trace : {$e['trace']}<br/>";
		exit($info);  
    }

    /**
     * @uses  Get the db connnetion object. 
     * @param defaults to null.
     */
    protected static function getDb()
    {
        if (!empty(Life::$frame->app_config) && isset(Life::$frame->app_config['db'])) {
            $dbConfig   = Life::$frame->app_config['db'];
            $connnetion = !empty($dbConfig['class']) ? $dbConfig['class'] : 'lifephp\database\Connection';
            unset($dbConfig['class']);
            return new $connnetion($dbConfig);
        } else {
            return null;
        }
    }

    /**
     * @uses  Init other core component object.
     * @param defaults to null.
     */
    private static function initOtherCoreComponent()
    {
        $componentClassArr = Life::$frame->getComponentClassName();
        foreach($componentClassArr as $class_name => $class_namespace){
            Life::$frame->$class_name = new $class_namespace;
        }
    }
}

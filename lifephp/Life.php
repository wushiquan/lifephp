<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// +  This is the LifePHP core framework class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

//Load core life-php class
require CORE_PATH . 'Lifephp' . EXT_SUFFIX;

//Lifephp php framework calling entrance
final class Life extends lifephp\core\Lifephp
{
	/**
     * @var array the main configuration of this application. Defaults to [].
     */
    public $app_config = [];

	/**
     * @var object the current object of Life class. Defaults to null.
     */
    public static $frame = null;

    /**
     * @var object the database object of lifephp framework. Defaults to null.
     */
    public $db = null;

    /**
     * @var 
     */
    public $core_class_tag = [ 
    	'request' => 'lifephp\http\Request',
    ];

	/**
	 * @uses lifephp framework execute application
	 * @param application params configuration
	 */
	public function runApplication($config = [])
	{
		// Set the configuration
		if(!empty($config) && is_array($config)){
			$this->app_config = $config;
		}
		//Init the application
		Life::$frame = $this;
		parent::run();
	}

	/**
	 * @uses  Get the default component class namespace of current component class name.
	 * @param string $class_name the current component class name
	 * @return mixed if exists, returns component class namespace ; otherwise, returns null.
	 */
	public function getComponentClassName($class_name = '')
	{
		if($class_name !== ''){
			return isset($this->core_class_tag[$class_name]) ? $this->core_class_tag[$class_name] : null;
		}else{
			return $this->core_class_tag;
		}
		
	}

}

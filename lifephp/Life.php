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
     * @var object the current object of Life class. Defaults to null.
     */
    public $db = null;

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

}

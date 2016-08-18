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

namespace ubox\controller;

use lifephp\mvc\Controller;
use lifephp\common\Tool;

class SiteController extends Controller
{
    /**
     * @uses   the index page 
     * @access public
     * @return void
     */
    public function actionIndex()
    {
    	$this->display('index',['order'=>'SM23748934','username'=>'13824394613']);
    }
}
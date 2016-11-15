<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + Application test controller
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace frontend\controller;

use Life;
use lifephp\mvc\Controller;
use lifephp\common\Tool;
class TestController extends Controller
{
    /**
     * @uses   the index page 
     * @access public
     * @return void
     */
    public function actionIndex()
    {
        $assignAction = $_GET;
        Tool::printr($assignAction);
    }
}
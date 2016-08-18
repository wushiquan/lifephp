<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + lifephp MVC controller base class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace lifephp\mvc;
class Controller
{
    /**
     * @var view the view instance object of lifephp
     * @access protected
     */
    protected $view = null;

    /**
     * @uses   construct function
     * @access public
     */
    public function __construct()
    {
        //instantiate the view class of lifephp
        $this->view = new \lifephp\mvc\View;

        //initialize lifephp controller
        if (method_exists($this, 'initialize')) {
            $this->initialize();
        }
    }

    /**
     * @uses   diplay the template file and assign params
     * @access protected
     * @param string $templateFile  the template file name, default ''
     * @param array $paramArr  the template params which will be assigned to the view
     * @param string $charset the template file charset
     * @param string $contentType the template content type
     * @return void
     */
    protected function display($templateFile = '', $paramArr = [], $content = '', $charset = '', $contentType = '')
    {
        $this->view->display($templateFile, $paramArr, $charset, $contentType);
    }

    /**
     * @uses   destruct function
     * @access public
     */
    public function __destruct()
    {

    }
}

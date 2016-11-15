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
     * @uses   Redirect to the specified url with the message after the specified time
     * @access protected
     * @param string  $url   the specified redirect url, default ''
     * @param integer $delay the delay time, default '0'
     * @param string  $msg   the tip massage 
     * @return void
     */
    protected function redirect($url, $delay = 0, $msg = '')
    {
        if (empty($msg)) {
            $msg = "The system will redirect to {$url} after {$delay} second automatically!";
        }

        if (!headers_sent()) {
            if ($delay === 0) {
                header('Location:' . $url);
            } else {
                header("refresh:{$delay};url={$url}");
                echo($msg);
            }
            exit();
        } else {
            $str = "<meta http-equiv='Refresh' content='{$delay};URL={$url}'>";
            if ($delay != 0) {
                $str .= $msg;
            }
            exit($str);
        }
    }

    /**
     * @uses   destruct function
     * @access public
     */
    public function __destruct()
    {
        unset($this->view);
    }
}

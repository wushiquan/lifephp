<?php
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + lifephp MVC view base class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------
namespace lifephp\mvc;
class View
{
    /**
     * @var tempVar the template variables 
     * @access protected
     */
    protected $tempVar = [];

    /**
     * @use    assign the value to the template
     * @access private
     * @param  mixed $name
     * @param  mixed $value
     */
    private function assignParams($params)
    {
        if (is_array($params)  && !empty($params)) {
            $this->tempVar = array_merge($this->tempVar, $params);
        } else {
            return false;
        }
    }

    /**
     * @uses  load the template and display the view file
     * @access public
     * @param string $templateFile the template file name
     * @param string $charset the template file charset
     * @param string $contentType the template content type
     * @param string $content the template content
     * @return mixed
     */
    public function display($templateFile = '', $params = [], $charset = '', $contentType = '')
    {
        // parse and get the template content
		$this->assignParams($params);
        $content = $this->fetchContent($templateFile);

        // output the template content to the browser
        header('Content-Type:text/html; charset=utf-8');
        header('X-Powered-By:LifePHP');
        echo $content;

    }

    /**
     * @uses   parse and get the template content
     * @access public
     * @param  string $templateFile the template file name
     * @param  string $content the template content
     * @return string
     */
    public function fetchContent($templateFile = '', $content = '')
    {
 
        $templateFile = $this->getTemplatePath($templateFile);
        // throw exception if not exists the template file
        if (!is_file($templateFile) || !file_exists($templateFile)) {
             throw new \exception('the template file not exists:' . $templateFile,404);
        }
        // ob cache start
        ob_start();
        ob_implicit_flush(0);

        // use PHP native template
        extract($this->tempVar, EXTR_OVERWRITE);
        include $templateFile;

        $content = ob_get_clean();
        return $content;
    }

    /**
     * @uses   get the template path
     * @access private
     * @param  string $template name
     * @return string
     */
    private function getTemplatePath($template = '')
    {
        if (is_file($template)) {
            return $template;
        }

        // get the view file path
        $tmplPath = APP_PATH .'/view/';
        // if the template name is empty
        $controllerName = substr(strtolower(CONTROLLER_NAME), 0,-strlen('controller'));
        if ('' == $template) {
            $template = $controllerName . '/' . ACTION_NAME;
        } elseif (FALSE === strpos($template, '/')) {
            $template = $controllerName . '/' . $template;
        }
        return $tmplPath . $template . VIEW_SUFFIX;       
    }
}

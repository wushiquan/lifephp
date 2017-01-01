<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + lifephp database active record class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------

namespace lifephp\http;
class Request 
{
    /**
     * @uses   Returns the server name.
     * @return string server name, null if not available
     */
    public function getServerName()
    {
        return isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : null;
    }

    /**
     * @uses Returns the server port.
     * @return mixed server port, null if not available
     */
    public function getServerPort()
    {
        return isset($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : null;
    }

    /**
     * @uses Returns the URL referrer.
     * @return mixed URL referrer, null if not available
     */
    public function getReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    /**
     * @uses   Returns the user agent.
     * @return mixed the user agent, null if not available
     */
    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * @uses   Returns the user IP address.
     * @return mixed user IP address, null if not available
     */
    public function getUserIP()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
    }

    /**
     * @uses   Returns the user host name.
     * @return mixed user host name, null if not available
     */
    public function getUserHost()
    {
        return isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : null;
    }

    /**
     * @uses   Return the username sent HTTP authentication
     * @return mixed the username sent HTTP authentication, null if the username is not given
     */
    public function getAuthUser()
    {
        return isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;
    }

    /**
     * @uses   Return the password sent HTTP authentication
     * @return mixed the password sent HTTP authentication, null if the password is not given
     */
    public function getAuthPassword()
    {
        return isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW'] : null;
    }

    /**
     * @uses   Returns part of the request URL.
     * @return string part of the request URL that is after the question mark
     */
    public function getQueryString()
    {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    }

    /**
     * @uses   Return whether if the request is sent via https secure protocol.
     * @return boolean whether if the request is sent via https secure protocol
     */
    public function getIsSecureProtocol()
    {
        return (isset($_SERVER['HTTPS']) && (strcasecmp($_SERVER['HTTPS'], 'on') === 0 || $_SERVER['HTTPS'] == 1))
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0);
    }

    /**
     * @uses   Returns request content-type
     * @return string request content-type. Null will be returned if this information is not available.
     */
    public function getContentType()
    {
        if (isset($_SERVER['CONTENT_TYPE'])) {
            return $_SERVER['CONTENT_TYPE'];
        } elseif (isset($_SERVER['HTTP_CONTENT_TYPE'])) {           
            return $_SERVER['HTTP_CONTENT_TYPE'];
        }
        return null;
    }

    /**
     * @uses   Returns the method of the current request.
     * @return string request method, such as GET, POST, HEAD, PUT, PATCH, OPTIONS, DELETE.
     */
    public function getReqMethod()
    {        
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }
        
        if(defined('REQUEST_METHOD')){
            return strtoupper(REQUEST_METHOD);
        }

        if (isset($_SERVER['REQUEST_METHOD'])) {
            return strtoupper($_SERVER['REQUEST_METHOD']);
        } 
        // If system fails to get, returns 'GET' method by default.       
        return 'GET';
    }

    /**
     * @uses   Returns whether this is a GET request.
     * @return boolean whether this is a GET request.
     */
    public function isGet()
    {
        return $this->getReqMethod() === 'GET';
    }

    /**
     * @uses   Returns whether this is a POST request.
     * @return boolean whether this is a POST request.
     */
    public function isPost()
    {
        return $this->getReqMethod() === 'POST';
    }

    /**
     * @uses   Returns whether this is a PUT request.
     * @return boolean whether this is a PUT request.
     */
    public function isPut()
    {
        return $this->getReqMethod() === 'PUT';
    }

   /**
     * @uses   Returns whether this is a DELETE request.
     * @return boolean whether this is a DELETE request.
     */
    public function isDelete()
    {
        return $this->getReqMethod() === 'DELETE';
    }

    /**
     * @uses   Returns whether this is a HEAD request.
     * @return boolean whether this is a HEAD request.
     */
    public function isHead()
    {
        return $this->getReqMethod() === 'HEAD';
    }

   /**
     * @uses   Returns whether this is an OPTIONS request.
     * @return boolean whether this is a OPTIONS request.
     */
    public function isOptions()
    {
        return $this->getReqMethod() === 'OPTIONS';
    }

    /**
     * @uses   Returns whether this is a PATCH request.
     * @return boolean whether this is a PATCH request.
     */
    public function isPatch()
    {
        return $this->getReqMethod() === 'PATCH';
    }

    /**
     * @uses   Returns whether this is a XMLHttpRequest request.
     * @return boolean whether this is a XMLHttpRequest request.
     */
    public function isAjax()
    {
        if (defined('IS_AJAX')) {
            return IS_AJAX;    
        } else {
            return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
        }               
    }

    /**
     * @uses   Returns post data with parameter name;otherwise,returns the whole post data.
     * @return mixed the post data array or the post value of parameter name $post_name.
     */
    public function post($post_name = '')
    {
        return ($post_name !== '' && isset($_POST[$post_name])) ? $_POST[$post_name]: $_POST;
    }

    /**
     * @uses   Returns get data with parameter name;otherwise,returns the whole get data.
     * @return mixed the get data array or the get value of parameter name $get_name.
     */
    public function get($get_name = '')
    {
        return ($get_name !== '' && isset($_GET[$get_name])) ? $_GET[$get_name]: $_GET;
    }
}	

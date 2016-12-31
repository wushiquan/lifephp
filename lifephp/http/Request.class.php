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
}	

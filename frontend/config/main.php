<?php 
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + LifePHP application configuration array
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------
return [
    'name' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'language'   => 'zh-CN',
    'timeZone' => 'Asia/Shanghai',
    'controllerNamespace' => 'frontend\controller',
    'domain' => 'http://www.lifephp.com',
    'db' => require(__DIR__ . '/' . 'dbconfig.php'),
];

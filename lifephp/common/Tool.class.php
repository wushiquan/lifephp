<?php
// -----------------------------------------------------------------------
// + LifePHP - Make coding life more efficient,Let the program run faster
// -----------------------------------------------------------------------
// + LifePHP tool class
// -----------------------------------------------------------------------
// + Copyright (c) 2016 http://www.lifephp.net  All rights reserved
// -----------------------------------------------------------------------
// + Author:  shiquanwu <2448154608@qq.com>
// -----------------------------------------------------------------------
// + Version: 1.0
// -----------------------------------------------------------------------
namespace lifephp\common;
class Tool
{

    /**
     * @uses   print mixed value,such as string,boolean,array and object
     * @param  mixed $mix_value the value which needs to be output 
     * @param  string $exit check if exits current program
     * @access public
     * @return void
     */
    public static function printr($mix_value, $exit = true)
    { 
    	echo '<pre>';
 		if (is_array($mix_value)) {
 			print_r($mix_value);
 		} else {
 		 	var_dump($mix_value);
 		}
 		echo '</pre>';

 		if (true == $exit) {
 			exit('***************************');
 		}
    }

}

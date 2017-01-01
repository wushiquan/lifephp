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

    /**
     * @uses  XML encoding
     * @param mixed $data data
     *        string $root root node name
     *        string $item sub node name with numbers as index
     *        string $attr root node attribute
     *        string $id   the attribute name changed by the key of sub node name with numbers as index
     *        string $encoding the encoded data 
     * @return string
     */
    public static function xml_encode($data, $root = 'lifephp', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
    {
        if (is_array($attr)) {
            $_attr = array();
            foreach ($attr as $key => $value) {
                $_attr[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $_attr);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml  = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= self::data_to_xml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * @uses  data XML encoding
     * @param mixed  $data data
     * @param string $item sub node name with numbers as index
     * @param string $id   the attribute name changed by the key of sub node name with numbers as index
     * @return string
     */
    private static function data_to_xml($data, $item = 'item', $id = 'id')
    {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key         = $item;
            }
            $xml .= "<{$key}{$attr}>";
            $xml .= (is_array($val) || is_object($val)) ? self::data_to_xml($val, $item, $id) : $val;
            $xml .= "</{$key}>";
        }
        return $xml;
    }
}

<?php

/**
 * Copyright (C) 2012  Nicolas Oelgart
 * 
 * @author Nicolas Oelgart
 * @license GPL 3 http://www.gnu.org/copyleft/gpl.html
 *
 * This engine handles HTTP requests using native PHP functions.
 *
**/

namespace TM4B\Engines\HTTP;

use TM4B\Interfaces\HTTPEngine;

class NativeEngine implements HTTPEngine
{
    /**
     * Posts $data to a given $url and returns the response.
     *
     * @param string $url   Request URL
     * @param array  $data  POST data.
     * @return string
     *
    **/
    public function post($url, array $data)
    {
        $data = http_build_query($data, '', '&');
        
        $context = stream_context_create(array(
            'http' => array(
                'method'  => 'POST',
                'header'  =>
                    "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " . strlen($data) . "\r\n",
                'content'  => $data
            )
        ));
       
        return @file_get_contents($url, false, $context);
    }
}

?>
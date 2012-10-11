<?php

/**
 * Copyright (C) 2012  Nicolas Oelgart
 * 
 * @author Nicolas Oelgart
 * @license GPL 3 http://www.gnu.org/copyleft/gpl.html
 *
 * This engine handles HTTP requests using cURL.
 *
**/

namespace TM4B\Engines\HTTP;

use TM4B\Interfaces\HTTPEngine;

class CurlEngine implements HTTPEngine
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
        $ch = curl_init();
        
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_CAINFO         => __TM4B_ROOT__ . '\Certificates\AddTrustExternalCARoot.crt'
        ));

        return curl_exec($ch);
    }
}

?>
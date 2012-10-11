<?php

/**
 * Copyright (C) 2012  Nicolas Oelgart
 * 
 * @author Nicolas Oelgart
 * @license GPL 3 http://www.gnu.org/copyleft/gpl.html
 *
 * This class helps the HTTP engines to do common tasks.
 *
**/

namespace TM4B\Helpers;

use TM4B\Exceptions\TM4BErrorException;

abstract class HTTPHelper
{
    
    /**
     * URL to the API resource.
     *
    **/
    const API_URL = 'https://www.tm4b.com/client/api/http.php';
    
    /**
     * variables to be sent to TM4B.
     *
    **/
    protected $data = array(
        'version'  => '2.1',
        'username' => '',
        'password' => '',
        'to'       => '',
        'from'     => '',
        'msg'      => '',
        'type'     => '',
        'sim'      => '',
    );
    
    
    /**
     * Holds the instance of the HTTP engine.
     *
    **/
    protected static $httpEngine = null;
    
    
    /**
     * Parses the response from TM4B. Throws an exception on error,
     * or an array on success.
     *
     * @param string $response   HTTP response from TM4B.
     * @return mixed
     * @throws TM4B\Exceptions\TM4BErrorException
     *
    **/
    protected function parseResponse($response)
    {
        if (substr($response, 0, 5) === 'error')
        {
            if (preg_match('~^error\(\d+\|(.*?)\)~', $response, $error))
            {
                throw new TM4BErrorException($error[1]);
            }
            
            return false;
        }
        
        if ($response)
        {
            $xml = new \SimpleXMLElement($response);
            return (array) $xml;
        }
        
        return false;
    }
    
    
    /**
     * Returns an instance of a HTTP engine.
     * Uses cURL wherever available, and native functions
     * anywhere else.
     *
     * @return HTTPEngine
     *
    **/
    protected static function getHTTPEngine()
    {
        if (!isset(static::$httpEngine))
        {
            if (function_exists('curl_init'))
            {
                $engine = 'Curl';
            }
            else
            {
                $engine = 'Native';
            }
            
            $class = 'TM4B\Engines\HTTP\\' . $engine . 'Engine';
            static::$httpEngine = new $class();
        }
        
        return static::$httpEngine;
    }
    
    
    /**
     * Sends an HTTP request to TM4B, parses the response, and returns it.
     * Returns an array on success, and false on error.
     *
     * @return mixed
     * @throws TM4B\Exceptions\TM4BErrorException
     *
    **/
    public function sendRequest()
    {
        $response = static::getHTTPEngine()->post(static::API_URL, $this->data);
        $response = $this->parseResponse($response);
        
        return $response;
    }

}

?>
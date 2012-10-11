<?php

/**
 * Copyright (C) 2012  Nicolas Oelgart
 * 
 * @author Nicolas Oelgart
 * @license GPL 3 http://www.gnu.org/copyleft/gpl.html
 *
 * All HTTP engines must implement this interface
 *
**/

namespace TM4B\Interfaces;

interface HTTPEngine
{
    /**
     * Posts $data to a given $url and returns the response.
     *
     * @param string $url   Request URL
     * @param array  $data  POST data.
     * @return string
     *
    **/
    public function post($url, array $data);
}

?>
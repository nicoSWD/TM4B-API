<?php

/**
 * Copyright (C) 2012  Nicolas Oelgart
 * 
 * @author Nicolas Oelgart
 * @license GPL 3 http://www.gnu.org/copyleft/gpl.html
 *
 * API wrapper for TM4B. Sends SMS messages to mobile phones.
 * Requires an account, and credits, which you can create here:
 *
 * https://www.tm4b.com/register/
 *
 * The official API documentation can be found here for further
 * reference:
 *
 * http://www.tm4b.com/kb/docs/tm4b-http-api-ca-2.1.pdf
 *
 *
 * @license
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
**/

namespace TM4B;

use TM4B\Helpers\HTTPHelper;

class API extends HTTPHelper
{
    
    /**
     * Class constructor, initiates the class and sets the optional
     * uername and password for TM4B.
     *
     * @param string $username    TM4B username
     * @param string $password    TM4B password.
     * @return void
     *
    **/
    public function __construct($username = '', $password = '')
    {
        if ($username)
        {
            $this->data['username'] = $username;
        }
        
        if ($password)
        {
            $this->data['password'] = $password;
        }
    }
    
    
    /**
     * Broadcasts a message to given phone numbers.
     * $recipients can either be a single number, or an array of
     * numbers.
     *
     * @param mixed  $recipients    Mobile phone numbers.
     * @param string $message       Message to be sent.
     * @param string $from          OPTIONAL - 'From' name to be seen by recipients.
     * @return mixed
     * @throws TM4B\Exceptions\TM4BErrorException
     *
    **/
    public function broadcast($recipients, $message, $from = '')
    {
        if (is_array($recipients))
        {
            $recipients = implode('|', $recipients);
        }
        
        $this->data['to']  = $recipients;
        $this->data['msg'] = $message;
        $this->data['type'] = 'broadcast';
        
        if ($from)
        {
            $this->data['from'] = $from;
        }
        
        return $this->sendRequest();
    }
    
    
    /**
     * Checks the credit left on your account.
     * Returns an array on success, false on error.
     *
     * @return mixed
     * @throws TM4B\Exceptions\TM4BErrorException
     *
    **/
    public function checkCredit()
    {
        $this->data['type'] = 'check_credit';
        return $this->sendRequest();
    }
    
    
    /**
     * Checks a message status. Useful to check if and when it
     * has been sent.
     *
     * Note: $timestamp may be 'false' if the message has been submitted,
     * but failed to actually send at the given moment.
     *
     * @param string $messageID   Message ID returned by self::broadcast().
     * @return mixed
     * @throws TM4B\Exceptions\TM4BErrorException
     *
    **/
    public function checkStatus($messageID)
    {
        if (preg_match('~^MT[a-z0-9-]{10,15}$~i', $messageID))
        {
            $this->data['smsid'] = $messageID;
        }
        else
        {
            $this->data['custom'] = $messageID;
        }
       
        $this->data['type'] = 'check_status'; 
        $response = $this->sendRequest();
        
        if (!empty($response['report']))
        {
            list($status, $timestamp) = explode('|', $response['report']);
            
            if (!empty($timestamp))
            {
                list($year, $month, $day, $hour, $minute) = str_split($timestamp, 2);
                $timestamp =  mktime($hour, $minute, 0, $month, $day, $year);
            }
            else
            {
                $timestamp = false;
            }
            
            return array(
                'status'    => $status,
                'timestamp' => $timestamp
            );
        }
        
        return false;
    }
    
    
    /**
     * Sets your TM4B username.
     *
     * @param string $username   TM4B username.
     * @return void
     *
    **/
    public function setUsername($username)
    {
        $this->data['username'] = $username;
    }
    
    
    /**
     * Sets your TM4B password.
     *
     * @param string $password   TM4B password.
     * @return void
     *
    **/
    public function setPassword($password)
    {
        $this->data['password'] = $password;
    }
    
   
    /**
     * Sets your 'from' name to be seen by others.
     *
     * @param string $from   'From' name.
     * @return void
     *
    **/
    public function setFrom($name)
    {
        $this->data['from'] = $name;
    }
    
    
    /**
     * Tells TM4B which split method to use if the message is larger
     * than permitted.
     * 
     *   '0' - No split (default)
     *   '1' - Simple Strict
     *   '2' - Simple Graceful
     *   '3' - SMS Numbering Strict
     *   '4' - SMS Numbering Graceful
     *   '5' - Three Dots Strict
     *   '6' - Three Dots Graceful
     *   '7' - Concatenation Strict
     *   '8' - Concatenation Graceful
     *
     * @param integer $method   Split method.
     * @return void
     *   
    **/
    public function setSplitMethod($method = 0)
    {
        $this->data['split_method'] = $method;
    }
    
    
    /**
     * Sets the data type of the message.
     * Possible values: 'plain' (default), 'unicode'
     *
     * @param string $type    Message data type
     * @return void
     *
    **/
    public function setDataType($type)
    {
        $this->data['data_type'] = $type;
    }
    
    
    /**
     * Sets the message expiry date. Defaults to the broadcast time plus 72 hours.
     * Note: Format must be: YYMMDDhhmm
     *
     * @param string $time    Expiry time.
     * @return void
     *
    **/
    public function setExpiry($time)
    {
        $this->data['expiry'] = $time;
    }
    
    
    /**
     * Sets the broadcast source.
     *
     * @param string $source   Broadcast source. Text, max. 200 characters
     * @return void
     *
    **/
    public function setSource($source)
    {
        $this->data['source'] = $source;
    }
    
    
    /**
     * Sets broadcast notes for further reference.
     *
     * @param string $notes    Broadcast notes. Text, max. 200 characters
     * @return void
     *
    **/
    public function setNotes($notes)
    {
        $this->data['notes'] = $notes;
    }
    
    
    /**
     * Toggles simulation mode on and off. For development phases.
     * No credits will be used in simulation mode, but no messages will be sent either.
     *
     * @param boolean $bool    True turns simulation on, false turns it off
     * @return void
     *
    **/
    public function setSimulation($bool)
    {
        $this->data['sim'] = ($bool ? 'yes' : 'no');
    }
    
}

define('__TM4B_ROOT__', __DIR__);

?>
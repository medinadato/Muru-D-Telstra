<?php
/**
 * MDN Solutions
 * @package     MDN_SMS
 * @author      Renato Medina <medina@mdnsolutions.com>
 * @copyright   Copyright (c) 2003 - 2014 MDN Solutions (http://magento.mdnsolutions.com)
 * @license     http://magento.mdnsolutions.com/license
 */
class MDN_SMS_Helper_Interface 
{
    private $appKey;
    
    private $appSecret;
    
//    private $recipient;
//    
//    private $message;
    
    private $accessToken;
    
    private $messageId;

    /**
     * 
     */
    public function send();

    /**
     * 
     */
    public function getStatus();

}
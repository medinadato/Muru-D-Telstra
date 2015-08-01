<?php
/**
 * MDN Solutions
 * @package     MDN_SMS
 * @author      Renato Medina <medina@mdnsolutions.com>
 * @copyright   Copyright (c) 2003 - 2014 MDN Solutions (http://magento.mdnsolutions.com)
 * @license     http://magento.mdnsolutions.com/license
 */
class MDN_SMS_Model_Telstra extends Mage_Core_Model_Abstract
{
    /**
     * @var string
     */
    private $appKey;

    /**
     * @var string
     */
    private $appSecret;
    
    /**
     * @var string
     */
    private $accessToken;

    /**
     * 
     */
    public function __construct()
    {
        $this->appKey = trim(Mage::getStoreConfig('mdn_sms/telstra/app_key'));
        $this->appSecret = trim(Mage::getStoreConfig('mdn_sms/telstra/app_secret'));
    }

    /**
     * 
     * @return string
     */
    private function getAuthenticate()
    {
        $url_oauth = Mage::getStoreConfig('mdn_sms/telstra/app_url_oauth');
        $url_oauth .= "?grant_type=client_credentials&scope=SMS";
        $url_oauth .= "&client_id=" . $this->appKey . "&client_secret=" . $this->appSecret;
        

//var_dump($url_oauth);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url_oauth);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Telstra SMS");
        //curl_setopt($curl, CONNECTTIMEOUT, 1);
        $content = curl_exec($curl);
//var_dump(curl_error ( $curl ));   
//var_dump($content);
        curl_close($curl);
       
        return json_decode($content, true)["access_token"];
    }

    /**
     * 
     * @param string $recipient
     * @param string $message
     * @return string
     */
    public function sendMessage($recipient, $message)
    {
        $url_sms = Mage::getStoreConfig('mdn_sms/telstra/app_url_sms');
//var_dump($url_sms);        
        $this->accessToken = $this->getAuthenticate();
//var_dump($this->accessToken);        
        $post_fields = array(
            "to" => $recipient,
            "body" => $message
        );
        
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url_sms);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Telstra SMS");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(("Authorization: Bearer " . $this->accessToken)));
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_fields));
        //curl_setopt($curl, CONNECTTIMEOUT, 1);
        $content = curl_exec($curl);
        curl_close($curl);
//var_dump(json_decode($content, true)["messageId"]);exit;        
        return json_decode($content, true)["messageId"];
    }

    /**
     * 
     * @return string
     */
    public function getStatus($message_id)
    {
        $url_sms = Mage::getStoreConfig('mdn_sms/telstra/app_url_sms') . "/" . $message_id;
//var_dump($url_sms);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url_sms);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Telstra SMS");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(("Authorization: Bearer " . $this->accessToken)));
        //curl_setopt($curl, CONNECTTIMEOUT, 1);
        $content = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($content, true);
    }
}
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
        $this->accessToken = $this->getAuthenticate();
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
        
        $response = $this->executeCurl($url_oauth);
       
        return json_decode($response, true)["access_token"];
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
       
        $post_fields = array(
            "to" => $recipient,
            "body" => $message
        );
        
        $response = $this->executeCurl($url_sms, $this->accessToken, $post_fields);
             
        return json_decode($response, true)["messageId"];
    }

    /**
     * 
     * @param string $message_id
     * @return string
     */
    public function getStatus($message_id)
    {
        $url_sms = Mage::getStoreConfig('mdn_sms/telstra/app_url_sms') . "/" . $message_id;
        
        $response = $this->executeCurl($url_sms, $this->accessToken);
        
        return json_decode($response, true);
    }
    
    /**
     * 
     * @param string $url
     * @param string $token
     * @param array $post_fields
     * @return mixed
     */
    private function executeCurl($url, $token = '', array $post_fields = array())
    {   
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Telstra SMS");
        //curl_setopt($curl, CONNECTTIMEOUT, 1);
        
        if(!empty($token)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(("Authorization: Bearer " . $token)));
        }
        
        if(count($post_fields) > 0) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_fields));
        }
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        
        return $response;
    }
}
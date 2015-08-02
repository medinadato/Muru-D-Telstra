<?php

/**
 * 
 */
class MDN_SMS_Model_Order_Observer
{ 
    /**
     * This function is only for testing purposes
     */
    public function sendTest($recipient, $message)
    {        
        $telstra = Mage::getModel('mdn_sms/telstra');
        return $telstra->sendMessage($recipient, $message);
    }
    
    /**
     * 
     * @param string $recipient
     * @param string $message
     */
    public function send(Varien_Event_Observer $observer)
    {
        $order_increment_id = $observer->getEvent()->getOrder()->getIncrementId();  
        
        $order = Mage::getModel('sales/order')->loadByIncrementId($order_increment_id);

        $billing = $order->getBillingAddress();

        $customer = $billing->getFirstname() . ' ' . $billing->getLastname();
        $recipient = $billing->getTelephone();

        $message = $customer . ', thanks for choosing MDNSolutions. Your order #' . $order_increment_id . ' has been received.';

        unset($order);
        
        $telstra = Mage::getModel('mdn_sms/telstra');
        
        return $telstra->sendMessage($recipient, $message);
    }
    
    /**
     * 
     * @param string $message_id
     * @return string
     */
    public function getStatus($message_id)
    {
        $telstra = Mage::getModel('mdn_sms/telstra');
        
        return $telstra->getStatus($message_id);
    }
}

<?php

/**
 * 
 */
class MDN_SMS_Model_Order_Observer
{   
    /**
     * 
     * @param string $recipient
     * @param string $message
     */
    //public function send($recipient, $message)
    public function send(Varien_Event_Observer $observer)
    {
        $order_tmp = $observer->getEvent()->getOrder();
//    var_dump($order);    
//    var_dump($order->getCustomer());        
        
//        $orders = array(145000022);
        $order = Mage::getModel('sales/order')->loadByIncrementId($order_tmp->getIncrementId());

        $billing = $order->getBillingAddress();

        $order_id = $order->getIncrementId();
        $customer = $billing->getFirstname() . ' ' . $billing->getLastname();
        $recipient = $billing->getTelephone();

        $message = $customer . ', thanks for choosing MDNSolutions. Your order #' . $order_id . ' has been received.';

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
